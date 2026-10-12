<?php

/**
 * 初回決済成功後のブラウザ戻り先。
 * 契約登録やクレジット付与は行わず、receive.php の処理結果だけをNAVIへ返します。
 */
require_once __DIR__ . '/subscription_lib.php';

try {
    $memberId = (string)($_GET['member_id'] ?? '');
    $option = (string)($_GET['option'] ?? '');
    if (
        !preg_match('/^[A-Za-z0-9_]{1,16}$/', $memberId)
        || !preg_match('/^[A-Za-z0-9]{10,64}$/', $option)
    ) {
        throw new RuntimeException('戻り先パラメータが不正です。');
    }

    $pdo = subscription_pdo();
    $subscription = null;

    // 通知とブラウザ遷移の順番が前後する場合に短時間だけ待つ。
    for ($attempt = 0; $attempt < 6; $attempt++) {
        $stmt = $pdo->prepare('SELECT * FROM navi_user WHERE telecom_member_id = :member_id LIMIT 1');
        $stmt->execute([':member_id' => $memberId]);
        $subscription = $stmt->fetch();
        if (!$subscription) {
            throw new RuntimeException('契約情報が見つかりません。');
        }
        if ($subscription['subscription_status'] !== 'pending') {
            break;
        }
        usleep(250000);
    }

    $mode = array_key_exists($subscription['navi_mode'], subscription_config()['navi_domains'])
        ? $subscription['navi_mode']
        : 'prod';
    $domain = subscription_config()['navi_domains'][$mode];
    $callbackPath = subscription_callback_path($subscription['callback_path']);

    if ($subscription['subscription_status'] === 'active') {
        $result = 'success';
    } elseif ($subscription['subscription_status'] === 'past_due') {
        $result = 'failure';
    } else {
        $result = 'pending';
    }

    $transactionId = (string)($subscription['last_settle_uuid'] ?: $option);
    $price = (int)$subscription['monthly_amount'];
    $credits = (int)$subscription['credits_per_cycle'];
    $timestamp = time();
    $hash = subscription_callback_hash($transactionId, $result, $price, $credits, $timestamp);

    $params = http_build_query([
        'transaction_id' => $transactionId,
        'result' => $result,
        'price' => $price,
        'credits' => $credits,
        'timestamp' => $timestamp,
        'hash' => $hash,
        'key' => subscription_config()['jwt_key'],
        'subscription' => 1,
    ]);
    $targetUrl = rtrim($domain, '/') . '/' . $callbackPath . '?' . $params;

    subscription_log('subscription', 'NAVIへ戻す', [
        'member_id' => $memberId,
        'result' => $result,
        'target_domain' => $domain,
    ]);
    header('Location: ' . $targetUrl, true, 302);
    exit;
} catch (Throwable $e) {
    subscription_log('subscription', '戻り処理エラー', ['error' => $e->getMessage()]);
    http_response_code(400);
    echo '<!doctype html><html lang="ja"><meta charset="UTF-8"><title>決済確認中</title>';
    echo '<h2>決済結果を確認できませんでした。</h2><p>';
    echo htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    echo '</p><p>NAVIへ戻り、しばらくしてから契約状態をご確認ください。</p>';
}
