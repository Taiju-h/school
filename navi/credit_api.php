<?php

/**
 * NAVIから定額クレジット残高を参照・消費するAPI。
 *
 * POST action=balance token=<JWT>
 * POST action=consume token=<JWT> credits=<正数> usage_id=<一意ID>
 */
require_once '../vendor/autoload.php';
require_once __DIR__ . '/subscription_lib.php';

header('Content-Type: application/json; charset=UTF-8');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new RuntimeException('POST required');
    }

    $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    $token = (string)($_POST['token'] ?? '');
    if ($token === '' && preg_match('/^Bearer\s+(.+)$/i', $authHeader, $match)) {
        $token = $match[1];
    }
    if ($token === '') {
        throw new RuntimeException('token required');
    }

    $user = subscription_jwt_user(subscription_decode_jwt($token));
    $action = (string)($_POST['action'] ?? 'balance');
    $pdo = subscription_pdo();

    if ($action === 'balance') {
        $stmt = $pdo->prepare(
            'SELECT credit_balance, subscription_status, monthly_amount, credits_per_cycle,
                    last_payment_at, cancelled_at
             FROM navi_user WHERE user_id = :user_id LIMIT 1'
        );
        $stmt->execute([':user_id' => $user['id']]);
        $row = $stmt->fetch();
        echo json_encode([
            'ok' => true,
            'balance' => (int)($row['credit_balance'] ?? 0),
            'subscription_status' => $row['subscription_status'] ?? 'inactive',
            'monthly_amount' => (int)($row['monthly_amount'] ?? 0),
            'credits_per_cycle' => (int)($row['credits_per_cycle'] ?? 0),
            'last_payment_at' => $row['last_payment_at'] ?? null,
            'cancelled_at' => $row['cancelled_at'] ?? null,
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($action !== 'consume') {
        throw new RuntimeException('unknown action');
    }

    $credits = filter_var($_POST['credits'] ?? null, FILTER_VALIDATE_INT);
    $usageId = (string)($_POST['usage_id'] ?? '');
    if ($credits === false || $credits <= 0 || $credits > 100000) {
        throw new RuntimeException('invalid credits');
    }
    if (!preg_match('/^[A-Za-z0-9_.:-]{8,64}$/', $usageId)) {
        throw new RuntimeException('invalid usage_id');
    }

    $pdo->beginTransaction();
    $stmt = $pdo->prepare('SELECT id, credit_balance FROM navi_user WHERE user_id = :user_id FOR UPDATE');
    $stmt->execute([':user_id' => $user['id']]);
    $row = $stmt->fetch();
    if (!$row) {
        throw new RuntimeException('subscription account not found');
    }

    $stmt = $pdo->prepare('SELECT balance_after FROM navi_credit_usage WHERE usage_id = :usage_id LIMIT 1');
    $stmt->execute([':usage_id' => $usageId]);
    $duplicate = $stmt->fetch();
    if ($duplicate) {
        $pdo->commit();
        echo json_encode(['ok' => true, 'duplicate' => true, 'balance' => (int)$duplicate['balance_after']]);
        exit;
    }

    $balance = (int)$row['credit_balance'];
    if ($balance < $credits) {
        $pdo->rollBack();
        http_response_code(409);
        echo json_encode(['ok' => false, 'error' => 'insufficient_balance', 'balance' => $balance]);
        exit;
    }

    $balanceAfter = $balance - $credits;
    $stmt = $pdo->prepare(
        'UPDATE navi_user SET credit_balance = :balance, updated_at = NOW() WHERE id = :id'
    );
    $stmt->execute([':balance' => $balanceAfter, ':id' => $row['id']]);
    $stmt = $pdo->prepare(
        'INSERT INTO navi_credit_usage (usage_id, user_id, credits, balance_after, created_at)
         VALUES (:usage_id, :user_id, :credits, :balance_after, NOW())'
    );
    $stmt->execute([
        ':usage_id' => $usageId,
        ':user_id' => $user['id'],
        ':credits' => $credits,
        ':balance_after' => $balanceAfter,
    ]);
    $pdo->commit();

    echo json_encode(['ok' => true, 'duplicate' => false, 'balance' => $balanceAfter]);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    subscription_log('credit_api', 'APIエラー', ['error' => $e->getMessage()]);
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
