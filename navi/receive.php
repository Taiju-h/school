<?php

require_once __DIR__ . '/subscription_lib.php';
subscription_start_session();
$data = subscription_request_data();
$traceId = subscription_event_id($data, 'receive');

subscription_trace_log($traceId, 'request_received', subscription_request_log_context($data));

// テレコムからのサーバー通知を最優先で判定します。
if (subscription_telecom_request($data)) {
    subscription_trace_log($traceId, 'telecom_request_detected', [
        'rel' => $data['rel'] ?? null,
        'settle_uuid' => $data['settle_uuid'] ?? null,
        'settle_count' => $data['settle_count'] ?? null,
        'money' => $data['money'] ?? null,
        'sendid' => $data['sendid'] ?? null,
        'option' => $data['option'] ?? null,
        'rebill_param_id' => $data['rebill_param_id'] ?? null,
    ]);

    try {
        $pdo = subscription_pdo();
        $transactionId = subscription_event_id($data, 'payment');
        $traceId = $transactionId;
        $memberId = (string)($data['sendid'] ?? $data['member_id'] ?? '');
        $userIdOption = (string)($data['option'] ?? $data['user_id_param'] ?? '');
        $rel = strtolower((string)($data['rel'] ?? 'yes'));
        $money = (int)($data['money'] ?? 0);
        $settleCount = isset($data['settle_count']) ? (int)$data['settle_count'] : null;

        $subscription = null;
        $matchedBy = null;
        $isPlanSelectionTestRequest =
            isset($data['test_plan_id'])
            || isset($data['test_credits'])
            || isset($data['test_signature']);

        // 署名付きテストだけは、プルダウンで選択したoptionのユーザーを優先します。
        if ($isPlanSelectionTestRequest && $userIdOption !== '') {
            $stmt = $pdo->prepare('SELECT * FROM navi_user WHERE user_id = :user_id LIMIT 1');
            $stmt->execute([':user_id' => $userIdOption]);
            $subscription = $stmt->fetch();
            if ($subscription) {
                $matchedBy = 'option_user_id';
            }
        }
        if (!$subscription && $memberId !== '') {
            $stmt = $pdo->prepare('SELECT * FROM navi_user WHERE telecom_member_id = :member_id LIMIT 1');
            $stmt->execute([':member_id' => $memberId]);
            $subscription = $stmt->fetch();
            if ($subscription) {
                $matchedBy = 'telecom_member_id';
            }
        }
        if (!$subscription && $userIdOption !== '') {
            $stmt = $pdo->prepare('SELECT * FROM navi_user WHERE user_id = :user_id LIMIT 1');
            $stmt->execute([':user_id' => $userIdOption]);
            $subscription = $stmt->fetch();
            if ($subscription) {
                $matchedBy = 'option_user_id';
            }
        }

        if (!$subscription) {
            throw new RuntimeException('対象契約を特定できません。');
        }

        /*
         * receive_test.php からの署名付きテストだけ、選択したコースを
         * DBへ保存せず今回の通知へ適用します。本番通知はこの分岐を通りません。
         */
        $requestedTestPlanId = isset($data['test_plan_id']) ? (int)$data['test_plan_id'] : null;
        $requestedTestCredits = isset($data['test_credits']) ? (int)$data['test_credits'] : null;
        $requestedTestResult = strtolower(trim((string)($data['test_result'] ?? 'success')));
        $testSignature = (string)($data['test_signature'] ?? '');
        $hasTestOverride =
            $requestedTestPlanId !== null
            || $requestedTestCredits !== null
            || $testSignature !== '';

        if ($hasTestOverride) {
            if ($requestedTestPlanId === null || $requestedTestCredits === null || $testSignature === '') {
                throw new RuntimeException('テスト用ユーザー・コース情報が不足しています。');
            }
            if (!in_array($requestedTestResult, ['success', 'pending'], true)) {
                throw new RuntimeException('テスト用resultが不正です。');
            }
            if (subscription_mode((string)$subscription['navi_mode']) !== 'stg') {
                throw new RuntimeException('テストページから本番NAVI契約は実行できません。');
            }

            $stmt = $pdo->prepare(
                'SELECT id, plan_code, plan_name, monthly_amount, credits_per_cycle
                   FROM subscription_plans
                  WHERE id = :plan_id AND is_active = 1
                  LIMIT 1'
            );
            $stmt->execute([':plan_id' => $requestedTestPlanId]);
            $testPlan = $stmt->fetch();
            if (!$testPlan) {
                throw new RuntimeException('選択したテストコースが見つかりません。');
            }

            $databaseTestCredits = (int)$testPlan['credits_per_cycle'];
            $effectiveTestCredits = max(1, $databaseTestCredits);
            $validTestSelection =
                $money === (int)$testPlan['monthly_amount']
                && $requestedTestCredits === $effectiveTestCredits
                && subscription_verify_receive_plan_test_signature(
                    $transactionId,
                    (string)$subscription['user_id'],
                    (int)$testPlan['id'],
                    $money,
                    $requestedTestCredits,
                    $testSignature,
                    $requestedTestResult
                );
            if (!$validTestSelection) {
                throw new RuntimeException('テスト用ユーザー・コース署名が不正です。');
            }

            $databasePlan = [
                'plan_id' => $subscription['plan_id'],
                'monthly_amount' => $subscription['monthly_amount'],
                'credits_per_cycle' => $subscription['credits_per_cycle'],
            ];
            $subscription['plan_id'] = (int)$testPlan['id'];
            $subscription['monthly_amount'] = (int)$testPlan['monthly_amount'];
            $subscription['credits_per_cycle'] = $effectiveTestCredits;

            subscription_trace_log($traceId, 'test_plan_selected', [
                'user_id' => (string)$subscription['user_id'],
                'database_contract' => $databasePlan,
                'selected_plan' => [
                    'plan_id' => (int)$testPlan['id'],
                    'plan_code' => (string)$testPlan['plan_code'],
                    'monthly_amount' => (int)$testPlan['monthly_amount'],
                    'database_credits_per_cycle' => $databaseTestCredits,
                    'navi_send_credits' => $effectiveTestCredits,
                    'navi_result' => $requestedTestResult,
                ],
            ]);
        }

        subscription_trace_log($traceId, 'subscription_matched', [
            'matched_by' => $matchedBy,
            'user_id' => $subscription['user_id'],
            'plan_id' => $subscription['plan_id'],
            'subscription_status' => $subscription['subscription_status'],
            'monthly_amount' => $subscription['monthly_amount'],
            'credits_per_cycle' => $subscription['credits_per_cycle'],
            'navi_mode' => $subscription['navi_mode'],
        ]);

        $stmt = $pdo->prepare('SELECT * FROM telecom_payment_events WHERE settle_uuid = :uuid LIMIT 1');
        $stmt->execute([':uuid' => $transactionId]);
        $event = $stmt->fetch();
        if ($event && $event['api_status'] === 'success') {
            subscription_trace_log($traceId, 'duplicate_already_processed', [
                'event_id' => $event['id'] ?? null,
                'api_status' => $event['api_status'],
                'api_response' => $event['api_response'] ?? null,
            ]);
            subscription_output_success($traceId, ['reason' => 'already_processed']);
        }

        if (!$event) {
            $stmt = $pdo->prepare(
                'INSERT INTO telecom_payment_events
                    (settle_uuid, user_id, plan_id, event_type, rel, money, settle_count,
                     received_data, api_status, received_at)
                 VALUES
                    (:uuid, :user_id, :plan_id, \'payment\', :rel, :money, :settle_count,
                     :received_data, \'pending\', NOW())'
            );
            $stmt->execute([
                ':uuid' => $transactionId,
                ':user_id' => $subscription['user_id'],
                ':plan_id' => $subscription['plan_id'],
                ':rel' => $rel,
                ':money' => $money,
                ':settle_count' => $settleCount,
                ':received_data' => json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ]);

            subscription_trace_log($traceId, 'payment_event_inserted', [
                'user_id' => $subscription['user_id'],
                'plan_id' => $subscription['plan_id'],
                'rel' => $rel,
                'money' => $money,
                'settle_count' => $settleCount,
            ]);
        }

        if ($rel !== 'yes') {
            $stmt = $pdo->prepare(
                'UPDATE navi_user SET subscription_status = \'past_due\', last_transaction_id = :tx,
                 last_settle_uuid = :uuid, updated_at = NOW() WHERE user_id = :user_id'
            );
            $stmt->execute([':tx' => $transactionId, ':uuid' => $transactionId, ':user_id' => $subscription['user_id']]);
            $stmt = $pdo->prepare(
                'UPDATE telecom_payment_events SET api_status = \'success\', processed_at = NOW()
                 WHERE settle_uuid = :uuid'
            );
            $stmt->execute([':uuid' => $transactionId]);
            subscription_trace_log($traceId, 'payment_failure_recorded', [
                'user_id' => $subscription['user_id'],
                'rel' => $rel,
                'navi_credit_api_called' => false,
            ]);
            subscription_output_success($traceId, ['reason' => 'rel_no_recorded']);
        }

        if ($money !== (int)$subscription['monthly_amount']) {
            throw new RuntimeException('通知金額が契約金額と一致しません。');
        }

        subscription_trace_log($traceId, 'payment_amount_verified', [
            'received_money' => $money,
            'contract_money' => (int)$subscription['monthly_amount'],
        ]);

        $mode = subscription_mode((string)$subscription['navi_mode']);
        $creditsToSend = (int)$subscription['credits_per_cycle'];
        if ($hasTestOverride) {
            subscription_trace_log($traceId, 'test_credits_override_applied', [
                'user_id' => (string)$subscription['user_id'],
                'selected_plan_id' => $requestedTestPlanId,
                'navi_send_credits' => $creditsToSend,
            ]);
        }

        // 実際のテレコム通知でも、STGのuser_id=99999だけは最低1クレジットで疎通可能にします。
        if (
            !$hasTestOverride
            && $mode === 'stg'
            && (string)$subscription['user_id'] === '99999'
            && $creditsToSend < 1
        ) {
            $creditsToSend = 1;
            subscription_trace_log($traceId, 'stg_test_minimum_credit_applied', [
                'user_id' => '99999',
                'database_credits' => (int)$subscription['credits_per_cycle'],
                'navi_send_credits' => $creditsToSend,
            ]);
        }

        $timestamp = time();
        $result = $hasTestOverride ? $requestedTestResult : 'success';
        $hashSource = subscription_credit_hash_source(
            $transactionId,
            (string)$subscription['user_id'],
            $result,
            $money,
            $timestamp
        );
        $postData = [
            'user_id' => $subscription['user_id'],
            'transaction_id' => $transactionId,
            'result' => $result,
            'price' => $money,
            'timestamp' => $timestamp,
            'hash' => subscription_credit_hash(
                $transactionId,
                (string)$subscription['user_id'],
                $result,
                $money,
                $timestamp
            ),
            'subscription' => 1,
            'credits' => $creditsToSend,
            'settle_count' => $settleCount,
        ];
        $naviUrl = subscription_config()['navi'][$mode]['credits_api_url'];

        subscription_trace_log($traceId, 'navi_request_sending', [
            'url' => $naviUrl,
            'content_type' => 'application/json',
            'hash_format' => 'B (item_nameなし)',
            'hash_formula' => 'transaction_id:user_id:result:price:timestamp:hash_salt',
            'hash_source' => $hashSource,
            'post_data' => $postData,
        ]);

        $api = subscription_post_json_api($naviUrl, $postData);

        subscription_trace_log($traceId, 'navi_response_received', [
            'url' => $naviUrl,
            'ok' => $api['ok'],
            'http_code' => $api['http_code'],
            'response' => $api['response'],
            'curl_error' => $api['curl_error'] ?? '',
            'elapsed_ms' => $api['elapsed_ms'] ?? null,
        ]);

        $stmt = $pdo->prepare(
            'UPDATE telecom_payment_events
                SET api_status = :status, api_response = :response, processed_at = NOW()
              WHERE settle_uuid = :uuid'
        );
        $stmt->execute([
            ':status' => $api['ok'] ? 'success' : 'failed',
            ':response' => $api['response'],
            ':uuid' => $transactionId,
        ]);
        if (!$api['ok']) {
            throw new RuntimeException('NAVIクレジット追加APIが失敗しました。HTTP ' . $api['http_code']);
        }

        if ($hasTestOverride) {
            subscription_trace_log($traceId, 'test_completed_without_contract_update', [
                'user_id' => $subscription['user_id'],
                'navi_result' => $result,
                'database_subscription_status_unchanged' => true,
            ]);
        } else {
            $stmt = $pdo->prepare(
                'UPDATE navi_user
                    SET subscription_status = \'active\', last_transaction_id = :tx,
                        last_settle_uuid = :uuid, started_at = COALESCE(started_at, NOW()), updated_at = NOW()
                  WHERE user_id = :user_id'
            );
            $stmt->execute([
                ':tx' => $transactionId,
                ':uuid' => $transactionId,
                ':user_id' => $subscription['user_id'],
            ]);
            subscription_trace_log($traceId, 'subscription_activated', [
                'user_id' => $subscription['user_id'],
                'subscription_status' => 'active',
                'last_settle_uuid' => $transactionId,
            ]);
            subscription_log('定額決済処理成功', ['user_id' => $subscription['user_id'], 'settle_uuid' => $transactionId]);
        }

        if (
            $hasTestOverride
            && (string)($data['test_browser_continue'] ?? '') === '1'
            && $mode === 'stg'
            && (string)$subscription['user_id'] === '99999'
        ) {
            subscription_trace_log($traceId, 'test_browser_continue_to_navi', [
                'user_id' => '99999',
                'navi_http_code' => $api['http_code'],
            ]);
            header('Location: receive.php', true, 302);
            exit;
        }

        subscription_output_success($traceId, [
            'user_id' => $subscription['user_id'],
            'navi_http_code' => $api['http_code'],
        ]);
    } catch (Throwable $e) {
        subscription_log('定額決済処理失敗', ['error' => $e->getMessage(), 'data' => $data]);
        subscription_trace_log($traceId, 'payment_processing_exception', [
            'exception_class' => get_class($e),
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);
        subscription_output_retry($e->getMessage(), $traceId);
    }
}

// ここから下は、決済後にブラウザが戻ってきた場合の処理です。
$session = $_SESSION['teigaku_payment'] ?? null;
$requestScheme = (!empty($_SERVER['HTTPS']) && strtolower((string)$_SERVER['HTTPS']) !== 'off')
    ? 'https'
    : 'http';
$currentRequestUrl = $requestScheme
    . '://'
    . (string)($_SERVER['HTTP_HOST'] ?? '')
    . (string)($_SERVER['REQUEST_URI'] ?? '');
subscription_trace_log($traceId, 'browser_return_detected', [
    'request_url' => $currentRequestUrl,
    'referer' => (string)($_SERVER['HTTP_REFERER'] ?? ''),
    'session_id' => session_id(),
    'session_cookie_name' => session_name(),
    'has_session_cookie' => isset($_COOKIE[session_name()]),
    'has_teigaku_payment_session' => is_array($session),
    'session_data' => is_array($session) ? $session : null,
]);
if (!is_array($session) || empty($session['user_id'])) {
    subscription_trace_log($traceId, 'browser_return_session_missing', [
        'request_url' => $currentRequestUrl,
        'referer' => (string)($_SERVER['HTTP_REFERER'] ?? ''),
        'session_cookie_name' => session_name(),
        'has_session_cookie' => isset($_COOKIE[session_name()]),
    ]);
    http_response_code(400);
    echo '<meta charset="UTF-8"><h2>決済情報を確認できませんでした。</h2><p>NAVIから契約状態をご確認ください。</p>';
    exit;
}

try {
    $pdo = subscription_pdo();
    $stmt = $pdo->prepare('SELECT * FROM navi_user WHERE user_id = :user_id LIMIT 1');
    $stmt->execute([':user_id' => $session['user_id']]);
    $subscription = $stmt->fetch();
    if (!$subscription) {
        throw new RuntimeException('契約情報がありません。');
    }

    $status = (string)$subscription['subscription_status'];
    $result = $status === 'active' ? 'success' : ($status === 'past_due' ? 'failure' : 'pending');
    $result = "pending"; //$status === 'active' ? 'success' : ($status === 'past_due' ? 'failure' : 'pending');

    $transactionId = (string)($subscription['last_settle_uuid'] ?: 'pending_' . session_id());
    $timestamp = time();
    $price = (int)$subscription['monthly_amount'];
    $credits = (int)$subscription['credits_per_cycle'];
    $mode = subscription_mode((string)$subscription['navi_mode']);
    $callback = subscription_callback_path((string)$subscription['callback_path']);
    $params = http_build_query([
        'user_id' => (string)$subscription['user_id'],
        'transaction_id' => $transactionId,
        'result' => $result,
        'price' => $price,
        'credits' => $credits,
        'timestamp' => $timestamp,
        'hash' => subscription_return_hash(
            $transactionId,
            (string)$subscription['user_id'],
            $result,
            $price,
            $timestamp
        ),
        'key' => subscription_config()['jwt_key'],
        'subscription' => 1,
    ]);

    $url = rtrim(subscription_config()['navi'][$mode]['base_url'], '/') . '/' . $callback . '?' . $params;
    $loggedUrl = preg_replace('/([?&]key=)[^&]*/', '$1%5BREDACTED%5D', $url);
    if (!is_string($loggedUrl)) {
        $loggedUrl = $url;
    }

    subscription_trace_log($traceId, 'browser_redirect_to_navi', [
        'redirect_url' => $loggedUrl,
        'base_url' => subscription_config()['navi'][$mode]['base_url'],
        'callback' => $callback,
        'params' => [
            'user_id' => (string)$subscription['user_id'],
            'transaction_id' => $transactionId,
            'result' => $result,
            'price' => $price,
            'credits' => $credits,
            'timestamp' => $timestamp,
            'hash' => subscription_return_hash(
                $transactionId,
                (string)$subscription['user_id'],
                $result,
                $price,
                $timestamp
            ),
            'key' => subscription_config()['jwt_key'],
            'subscription' => 1,
        ],
    ]);
    subscription_log('NAVIブラウザ遷移URL生成', [
        'url' => $loggedUrl,
        'user_id' => (string)$subscription['user_id'],
        'transaction_id' => $transactionId,
        'result' => $result,
    ]);
    unset($_SESSION['teigaku_payment']);
    session_write_close();
    header('Location: ' . $url, true, 302);
    exit;
} catch (Throwable $e) {
    subscription_log('ブラウザ戻り処理失敗', ['error' => $e->getMessage()]);
    subscription_trace_log($traceId, 'browser_return_exception', [
        'exception_class' => get_class($e),
        'error' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
    ]);
    http_response_code(400);
    echo '<meta charset="UTF-8"><h2>決済結果を確認できませんでした。</h2><p>NAVIから契約状態をご確認ください。</p>';
}
