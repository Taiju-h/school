<?php

/**
 * NAVIからユーザーIDを受け取り、課金状態をJSONで返します。
 */
require_once __DIR__ . '/subscription_lib.php';

header('Content-Type: application/json; charset=UTF-8');
$data = subscription_request_data();

function subscription_status_json(int $httpCode, array $body): void
{
    http_response_code($httpCode);
    echo json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        subscription_status_json(405, ['success' => false, 'error' => 'POSTのみ使用できます。']);
    }

    $userId = trim((string)($data['user_id'] ?? ''));
    $timestamp = (int)($data['timestamp'] ?? 0);
    $receivedHash = (string)($data['hash'] ?? '');
    $receivedKey = (string)($data['key'] ?? '');

    if ($userId === '' || strlen($userId) > 64 || $timestamp <= 0 || $receivedHash === '') {
        subscription_status_json(400, ['success' => false, 'error' => 'リクエスト項目が不足しています。']);
    }
    if ((string)($data['subscription'] ?? '') !== '1') {
        subscription_status_json(400, ['success' => false, 'error' => 'subscriptionが不正です。']);
    }
    if (!hash_equals((string)subscription_config()['jwt_key'], $receivedKey)) {
        subscription_status_json(401, ['success' => false, 'error' => 'keyが一致しません。']);
    }
    if (abs(time() - $timestamp) > 300) {
        subscription_status_json(401, ['success' => false, 'error' => 'timestampの有効期限が切れています。']);
    }

    // NAVIとの合意仕様: sha256(user_id:timestamp:HASH_SALT)
    $hashSalt = (string)subscription_config()['hash_salt'];
    $expectedHash = hash('sha256', "{$userId}:{$timestamp}:{$hashSalt}");
    if (!hash_equals($expectedHash, $receivedHash)) {
        subscription_status_json(401, ['success' => false, 'error' => 'hashが一致しません。']);
    }

    $pdo = subscription_pdo();
    $stmt = $pdo->prepare(
        'SELECT n.*, p.plan_code, p.plan_name
           FROM navi_user n
           LEFT JOIN subscription_plans p ON p.id = n.plan_id
          WHERE n.user_id = :user_id
          LIMIT 1'
    );
    $stmt->execute([':user_id' => $userId]);
    $subscription = $stmt->fetch();
    if (!$subscription) {
        subscription_status_json(404, [
            'success' => false,
            'user_id' => $userId,
            'error' => '月額クレジットの登録がありません。',
        ]);
    }

    $eventStmt = $pdo->prepare(
        'SELECT settle_uuid, rel, money, settle_count, received_at, processed_at
           FROM telecom_payment_events
          WHERE user_id = :user_id AND event_type = \'payment\'
          ORDER BY received_at DESC, id DESC
          LIMIT 1'
    );
    $eventStmt->execute([':user_id' => $userId]);
    $lastPayment = $eventStmt->fetch();

    $status = (string)$subscription['subscription_status'];
    if ($lastPayment) {
        $paymentResult = strtolower((string)$lastPayment['rel']) === 'yes' ? 'success' : 'failure';
    } elseif ($status === 'past_due') {
        $paymentResult = 'failure';
    } else {
        $paymentResult = 'pending';
    }

    $transactionId = (string)(
        ($lastPayment['settle_uuid'] ?? '')
        ?: ($subscription['last_settle_uuid'] ?? '')
        ?: 'status_' . $userId
    );
    $price = (int)$subscription['monthly_amount'];
    $credits = (int)$subscription['credits_per_cycle'];
    $responseTimestamp = time();
    $startedAt = $subscription['started_at'] ?: null;
    $nextBillingDate = in_array($status, ['cancelled'], true)
        ? null
        : subscription_next_billing_date($startedAt, $responseTimestamp);

    subscription_status_json(200, [
        'success' => true,
        'user_id' => $userId,
        'transaction_id' => $transactionId,
        'subscription_status' => $status,
        'payment_result' => $paymentResult,
        'payment_type' => 'subscription',
        'billing_type' => 'recurring',
        'is_subscription' => true,
        'subscription' => 1,
        'started_at' => $startedAt,
        'next_billing_date' => $nextBillingDate,
        'next_billing_date_source' => $nextBillingDate === null ? null : 'calculated',
        'price' => $price,
        'credits' => $credits,
        'plan_id' => isset($subscription['plan_id']) ? (int)$subscription['plan_id'] : null,
        'plan_code' => $subscription['plan_code'] ?: null,
        'plan_name' => '月' . $credits . 'クレジット',
        'settle_count' => isset($lastPayment['settle_count'])
            ? (int)$lastPayment['settle_count']
            : null,
        'last_payment_at' => $lastPayment['received_at'] ?? null,
        'timestamp' => $responseTimestamp,
        'hash' => subscription_api_hash(
            $transactionId,
            $userId,
            $paymentResult,
            $price,
        ),
    ]);
} catch (Throwable $e) {
    subscription_log('契約状態API処理失敗', ['error' => $e->getMessage(), 'data' => $data]);
    subscription_status_json(500, ['success' => false, 'error' => '契約状態を取得できませんでした。']);
}
