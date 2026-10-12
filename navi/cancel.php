<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/subscription_lib.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

subscription_start_session();

function cancel_html(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function cancel_decode_user(string $token): array
{
    if ($token === '') {
        throw new RuntimeException('tokenがありません。');
    }
    if (class_exists('\\Firebase\\JWT\\Key')) {
        $decoded = JWT::decode($token, new Key(subscription_config()['jwt_key'], 'HS256'));
    } else {
        $decoded = JWT::decode($token, subscription_config()['jwt_key'], ['HS256']);
    }

    $userId = (string)($decoded->user->id ?? '');
    $userEmail = (string)($decoded->user->account ?? '');
    if ($userId === '') {
        throw new RuntimeException('ユーザーを確認できません。');
    }
    return ['id' => $userId, 'email' => $userEmail];
}

function cancel_prepare_table(PDO $pdo): void
{
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS subscription_cancel_requests (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id VARCHAR(64) NOT NULL,
            telecom_member_id VARCHAR(32) NULL,
            plan_id BIGINT UNSIGNED NULL,
            monthly_amount INT UNSIGNED NOT NULL DEFAULT 0,
            subscription_status VARCHAR(32) NOT NULL,
            request_status ENUM(\'pending\', \'completed\', \'rejected\') NOT NULL DEFAULT \'pending\',
            user_email VARCHAR(255) NULL,
            request_data LONGTEXT NULL,
            requested_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            completed_at DATETIME NULL,
            email_sent_at DATETIME NULL,
            email_error TEXT NULL,
            PRIMARY KEY (id),
            KEY idx_cancel_request_user (user_id),
            KEY idx_cancel_request_status (request_status),
            KEY idx_cancel_request_requested_at (requested_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
    );
}

function cancel_send_admin_mail(array $request): array
{
    $to = 'uranai@heartf.com';
    $subject = '【ハートフルスクール】月額クレジット解約申請 #' . $request['request_id'];
    $body = implode("\n", [
        '月額クレジットの解約申請を受け付けました。',
        '',
        '申請番号: ' . $request['request_id'],
        '申請日時: ' . $request['requested_at'],
        'NAVI user_id: ' . $request['user_id'],
        'NAVIメール: ' . ($request['user_email'] !== '' ? $request['user_email'] : '未取得'),
        'テレコム sendid: ' . $request['telecom_member_id'],
        'plan_id: ' . ($request['plan_id'] !== null ? $request['plan_id'] : 'NULL'),
        '月額: ' . number_format((int)$request['monthly_amount']) . '円',
        '現在の契約状態: ' . $request['subscription_status'],
        '',
        'テレコム側で継続決済を手動停止し、完了後に学校DBの契約状態を更新してください。',
    ]);

    $headers = implode("\r\n", [
        'From: uranai@heartf.com',
        'Reply-To: uranai@heartf.com',
        'Content-Type: text/plain; charset=UTF-8',
    ]);

    try {
        if (function_exists('mb_send_mail')) {
            $sent = mb_send_mail($to, $subject, $body, $headers);
        } else {
            $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
            $sent = mail($to, $encodedSubject, $body, $headers);
        }
        return ['sent' => (bool)$sent, 'error' => $sent ? '' : 'メール送信関数がfalseを返しました。'];
    } catch (Throwable $e) {
        return ['sent' => false, 'error' => $e->getMessage()];
    }
}

$token = (string)($_REQUEST['token'] ?? '');
$completed = false;
$alreadyRequested = false;
$mailSent = false;
$requestId = null;
$requestedAt = null;

try {
    $user = cancel_decode_user($token);
    $pdo = subscription_pdo();
    cancel_prepare_table($pdo);

    $stmt = $pdo->prepare(
        'SELECT * FROM navi_user
          WHERE user_id = :user_id
            AND subscription_status IN (\'active\', \'past_due\')
          LIMIT 1'
    );
    $stmt->execute([':user_id' => $user['id']]);
    $subscription = $stmt->fetch();
    if (!$subscription) {
        throw new RuntimeException('有効な定額契約がありません。');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $receivedCsrf = (string)($_POST['csrf_token'] ?? '');
        $sessionCsrf = (string)($_SESSION['cancel_csrf_token'] ?? '');
        if ($receivedCsrf === '' || $sessionCsrf === '' || !hash_equals($sessionCsrf, $receivedCsrf)) {
            throw new RuntimeException('画面の有効期限が切れています。もう一度開き直してください。');
        }

        $pdo->beginTransaction();
        try {
            $lockStmt = $pdo->prepare('SELECT id FROM navi_user WHERE user_id = :user_id FOR UPDATE');
            $lockStmt->execute([':user_id' => $user['id']]);

            $pendingStmt = $pdo->prepare(
                'SELECT id, requested_at, email_sent_at
                   FROM subscription_cancel_requests
                  WHERE user_id = :user_id AND request_status = \'pending\'
                  ORDER BY id DESC
                  LIMIT 1'
            );
            $pendingStmt->execute([':user_id' => $user['id']]);
            $pending = $pendingStmt->fetch();

            if ($pending) {
                $requestId = (int)$pending['id'];
                $requestedAt = (string)$pending['requested_at'];
                $mailSent = !empty($pending['email_sent_at']);
                $alreadyRequested = true;
            } else {
                $requestedAt = date('Y-m-d H:i:s');
                $requestData = [
                    'remote_addr' => (string)($_SERVER['REMOTE_ADDR'] ?? ''),
                    'user_agent' => (string)($_SERVER['HTTP_USER_AGENT'] ?? ''),
                    'request_uri' => (string)($_SERVER['REQUEST_URI'] ?? ''),
                ];
                $insertStmt = $pdo->prepare(
                    'INSERT INTO subscription_cancel_requests
                        (user_id, telecom_member_id, plan_id, monthly_amount,
                         subscription_status, request_status, user_email,
                         request_data, requested_at)
                     VALUES
                        (:user_id, :member_id, :plan_id, :amount,
                         :subscription_status, \'pending\', :user_email,
                         :request_data, :requested_at)'
                );
                $insertStmt->execute([
                    ':user_id' => $user['id'],
                    ':member_id' => (string)$subscription['telecom_member_id'],
                    ':plan_id' => isset($subscription['plan_id']) ? (int)$subscription['plan_id'] : null,
                    ':amount' => (int)$subscription['monthly_amount'],
                    ':subscription_status' => (string)$subscription['subscription_status'],
                    ':user_email' => $user['email'],
                    ':request_data' => json_encode($requestData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    ':requested_at' => $requestedAt,
                ]);
                $requestId = (int)$pdo->lastInsertId();
            }
            $pdo->commit();
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }

        if (!$alreadyRequested) {
            $mailResult = cancel_send_admin_mail([
                'request_id' => $requestId,
                'requested_at' => $requestedAt,
                'user_id' => $user['id'],
                'user_email' => $user['email'],
                'telecom_member_id' => (string)$subscription['telecom_member_id'],
                'plan_id' => isset($subscription['plan_id']) ? (int)$subscription['plan_id'] : null,
                'monthly_amount' => (int)$subscription['monthly_amount'],
                'subscription_status' => (string)$subscription['subscription_status'],
            ]);
            $mailSent = $mailResult['sent'];
            $mailStmt = $pdo->prepare(
                'UPDATE subscription_cancel_requests
                    SET email_sent_at = :email_sent_at, email_error = :email_error
                  WHERE id = :id'
            );
            $mailStmt->execute([
                ':email_sent_at' => $mailSent ? date('Y-m-d H:i:s') : null,
                ':email_error' => $mailSent ? null : (string)$mailResult['error'],
                ':id' => $requestId,
            ]);
        }

        unset($_SESSION['cancel_csrf_token']);
        subscription_trace_log('cancel_request_' . $requestId, 'cancel_request_saved', [
            'request_id' => $requestId,
            'user_id' => $user['id'],
            'telecom_member_id' => (string)$subscription['telecom_member_id'],
            'monthly_amount' => (int)$subscription['monthly_amount'],
            'already_requested' => $alreadyRequested,
            'mail_to' => 'uranai@heartf.com',
            'mail_sent' => $mailSent,
        ]);
        $completed = true;
    } else {
        $_SESSION['cancel_csrf_token'] = bin2hex(random_bytes(24));
    }
} catch (Throwable $e) {
    subscription_log('解約申請処理失敗', ['error' => $e->getMessage()]);
    http_response_code(400);
    echo '<meta charset="UTF-8"><h2>解約手続きを開始できませんでした。</h2><p>'
        . cancel_html($e->getMessage()) . '</p>';
    exit;
}
?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>月額クレジットの解約申請</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; background: #f5f7fb; margin: 0; color: #1f2937; }
        main { max-width: 680px; margin: 40px auto; padding: 28px; background: #fff; border-radius: 14px; box-shadow: 0 5px 22px rgba(15, 23, 42, .1); }
        h2 { margin-top: 0; }
        dl { display: grid; grid-template-columns: 180px 1fr; gap: 0; border-top: 1px solid #dbe2ea; }
        dt, dd { margin: 0; padding: 12px; border-bottom: 1px solid #dbe2ea; }
        dt { font-weight: 700; background: #f8fafc; }
        .notice { padding: 15px; background: #fff8dd; border: 1px solid #ead37a; border-radius: 8px; line-height: 1.7; }
        .success { padding: 16px; background: #eaf8ee; border: 1px solid #85c996; border-radius: 8px; line-height: 1.7; }
        button { display: block; margin: 24px auto 0; padding: 13px 28px; border: 0; border-radius: 7px; background: #d9473f; color: #fff; font-size: 17px; cursor: pointer; }
        @media (max-width: 600px) { main { margin: 16px; padding: 20px; } dl { grid-template-columns: 1fr; } dd { padding-top: 5px; } }
    </style>
</head>
<body>
<main>
<?php if ($completed): ?>
    <h2>解約申請を受け付けました</h2>
    <div class="success">
        申請番号：<?= (int)$requestId ?><br>
        申請日時：<?= cancel_html((string)$requestedAt) ?><br>
        <?php if ($alreadyRequested): ?>
            この申請はすでに受け付け済みです。
        <?php elseif ($mailSent): ?>
            運営へ解約申請を通知しました。
        <?php else: ?>
            申請はDBへ保存しましたが、運営メールの送信に失敗しました。運営側のログにも記録されています。
        <?php endif; ?>
    </div>
    <p>テレコム側の継続決済停止を運営が確認後、解約完了となります。</p>
<?php else: ?>
    <h2>月額クレジットを解約申請しますか？</h2>
    <div class="notice">
        この操作ではテレコムの継続決済は即時停止されません。<br>
        運営が申請内容を確認し、テレコム側で停止手続きを行います。
    </div>
    <dl>
        <dt>NAVI user_id</dt><dd><?= cancel_html($user['id']) ?></dd>
        <dt>テレコム sendid</dt><dd><?= cancel_html((string)$subscription['telecom_member_id']) ?></dd>
        <dt>月額</dt><dd><?= number_format((int)$subscription['monthly_amount']) ?>円</dd>
        <dt>現在の状態</dt><dd><?= cancel_html((string)$subscription['subscription_status']) ?></dd>
    </dl>
    <form method="post">
        <input type="hidden" name="token" value="<?= cancel_html($token) ?>">
        <input type="hidden" name="csrf_token" value="<?= cancel_html((string)$_SESSION['cancel_csrf_token']) ?>">
        <button type="submit">解約を申請する</button>
    </form>
<?php endif; ?>
</main>
</body>
</html>
