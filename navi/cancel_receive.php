<?php

require_once __DIR__ . '/subscription_lib.php';
$data = subscription_request_data();

try {
    $pdo = subscription_pdo();
    $memberId = (string)($data['member_id'] ?? $data['sendid'] ?? '');
    if ($memberId === '') {
        throw new RuntimeException('member_idがありません。');
    }
    $stmt = $pdo->prepare('SELECT * FROM navi_user WHERE telecom_member_id = :member_id LIMIT 1');
    $stmt->execute([':member_id' => $memberId]);
    $subscription = $stmt->fetch();
    if (!$subscription) {
        throw new RuntimeException('対象契約がありません。');
    }

    $eventId = subscription_event_id($data, 'cancel');
    $stmt = $pdo->prepare('SELECT * FROM telecom_payment_events WHERE settle_uuid = :uuid LIMIT 1');
    $stmt->execute([':uuid' => $eventId]);
    $event = $stmt->fetch();
    if ($event && $event['api_status'] === 'success') {
        subscription_output_success();
    }
    if (!$event) {
        $stmt = $pdo->prepare(
            'INSERT INTO telecom_payment_events
                (settle_uuid, user_id, plan_id, event_type, rel, money, received_data, api_status, received_at)
             VALUES
                (:uuid, :user_id, :plan_id, \'cancel\', \'yes\', 0, :received_data, \'pending\', NOW())'
        );
        $stmt->execute([
            ':uuid' => $eventId,
            ':user_id' => $subscription['user_id'],
            ':plan_id' => $subscription['plan_id'],
            ':received_data' => json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ]);
    }

    $mode = subscription_mode((string)$subscription['navi_mode']);
    $timestamp = time();
    $postData = [
        'user_id' => $subscription['user_id'],
        'transaction_id' => $eventId,
        'result' => 'cancel',
        'price' => 0,
        'timestamp' => $timestamp,
        'hash' => subscription_api_hash($eventId, 'cancel', 0, $timestamp),
        'key' => subscription_config()['jwt_key'],
        'subscription' => 1,
    ];
    $api = subscription_post_api(subscription_config()['navi'][$mode]['cancel_api_url'], $postData);
    $stmt = $pdo->prepare(
        'UPDATE telecom_payment_events
            SET api_status = :status, api_response = :response, processed_at = NOW()
          WHERE settle_uuid = :uuid'
    );
    $stmt->execute([
        ':status' => $api['ok'] ? 'success' : 'failed',
        ':response' => $api['response'],
        ':uuid' => $eventId,
    ]);
    if (!$api['ok']) {
        throw new RuntimeException('NAVI停止APIが失敗しました。HTTP ' . $api['http_code']);
    }

    $stmt = $pdo->prepare(
        'UPDATE navi_user SET subscription_status = \'cancelled\', cancelled_at = NOW(), updated_at = NOW()
         WHERE user_id = :user_id'
    );
    $stmt->execute([':user_id' => $subscription['user_id']]);
    subscription_output_success();
} catch (Throwable $e) {
    subscription_log('退会通知処理失敗', ['error' => $e->getMessage(), 'data' => $data]);
    subscription_output_retry($e->getMessage());
}
