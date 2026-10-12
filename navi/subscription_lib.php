<?php

function subscription_config(): array
{
    static $config;
    if ($config === null) {
        $config = require __DIR__ . '/config.php';
    }
    return $config;
}

function subscription_pdo(): PDO
{
    static $pdo;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $path = __DIR__ . '/db_config.php';
    if (!is_file($path)) {
        throw new RuntimeException('db_config.php がありません。');
    }
    $db = require $path;
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $db['host'], $db['db']);
    $pdo = new PDO($dsn, $db['user'], $db['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    return $pdo;
}

function subscription_log(string $message, array $context = []): void
{
    $line = '[' . date('Y-m-d H:i:s') . '] ' . $message;
    if ($context) {
        $line .= ' ' . json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    error_log($line . PHP_EOL, 3, __DIR__ . '/subscription_' . date('Ym') . '.log');
}

/**
 * 決済通知1件をsettle_uuid単位で追跡するJSON Linesログです。
 * APIキー・パスワードなどの秘密値だけを伏せ、決済データは確認可能な形で残します。
 */
function subscription_trace_log(string $traceId, string $stage, array $context = []): void
{
    $record = [
        'time' => date('Y-m-d H:i:s'),
        'trace_id' => $traceId,
        'stage' => $stage,
        'context' => subscription_redact_log_data($context),
    ];

    $json = json_encode($record, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($json === false) {
        $json = json_encode([
            'time' => date('Y-m-d H:i:s'),
            'trace_id' => $traceId,
            'stage' => $stage,
            'context' => ['log_encode_error' => json_last_error_msg()],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    file_put_contents(
        __DIR__ . '/subscription_trace_' . date('Ymd') . '.log',
        $json . PHP_EOL,
        FILE_APPEND | LOCK_EX
    );
}

function subscription_redact_log_data($value, ?string $parentKey = null)
{
    $secretKeys = [
        'key',
        'jwt_key',
        'hash_salt',
        'sendpass',
        'password',
        'telecom_password',
        'test_signature',
        'authorization',
        'cookie',
    ];

    if ($parentKey !== null && in_array(strtolower($parentKey), $secretKeys, true)) {
        return '[REDACTED]';
    }

    if (!is_array($value)) {
        return $value;
    }

    $result = [];
    foreach ($value as $key => $item) {
        $result[$key] = subscription_redact_log_data($item, (string)$key);
    }
    return $result;
}

function subscription_request_log_context(array $data): array
{
    $requestUri = (string)($_SERVER['REQUEST_URI'] ?? '');
    $requestPath = parse_url($requestUri, PHP_URL_PATH);

    return [
        'method' => (string)($_SERVER['REQUEST_METHOD'] ?? ''),
        'request_path' => is_string($requestPath) ? $requestPath : '',
        'remote_addr' => (string)($_SERVER['REMOTE_ADDR'] ?? ''),
        'user_agent' => (string)($_SERVER['HTTP_USER_AGENT'] ?? ''),
        'query' => $_GET,
        'post' => $_POST,
        'merged_data' => $data,
    ];
}

function subscription_start_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    if (PHP_VERSION_ID >= 70300) {
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    } else {
        session_set_cookie_params(0, '/; SameSite=Lax', '', true, true);
    }
    session_start();
}

function subscription_mode(string $mode): string
{
    return in_array($mode, ['stg', 'prod'], true) ? $mode : 'stg';
}

function subscription_callback_path(string $path): string
{
    $path = str_replace('_', '/', $path);
    $path = ltrim($path, '/');
    if ($path === '' || strpos($path, '..') !== false || !preg_match('#^[A-Za-z0-9/_\-.]+$#', $path)) {
        return 'my';
    }
    return $path;
}

function subscription_member_id(string $userId): string
{
    if (preg_match('/^[0-9]{1,9}$/', $userId)) {
        return 'SHCOOL' . str_pad($userId, 9, '0', STR_PAD_LEFT);
    }
    return 'NV_' . strtoupper(substr(hash('sha256', $userId), 0, 13));
}

/**
 * 新規の継続決済申込みごとに、未使用のsendidを発行します。
 *
 * NAVIのuser_idはoptionで連携するため、sendidへ埋め込みません。
 * 発行履歴をDBへ保存し、同時申込みを含めて同じsendidを再利用しないようにします。
 */
function subscription_new_member_id(PDO $pdo, string $userId): string
{
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS telecom_sendid_history (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            sendid VARCHAR(16) NOT NULL,
            user_id VARCHAR(64) NOT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY uq_telecom_sendid_history_sendid (sendid)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
    );

    for ($attempt = 0; $attempt < 30; $attempt++) {
        $sendid = 'SHCOOL' . str_pad((string)random_int(0, 999999999), 9, '0', STR_PAD_LEFT);
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO telecom_sendid_history (sendid, user_id, created_at)
                 VALUES (:sendid, :user_id, NOW())'
            );
            $stmt->execute([
                ':sendid' => $sendid,
                ':user_id' => $userId,
            ]);
            return $sendid;
        } catch (PDOException $e) {
            if ((string)$e->getCode() !== '23000') {
                throw $e;
            }
        }
    }

    throw new RuntimeException('継続決済用sendidを発行できませんでした。');
}

function subscription_password(): string
{
    return strtoupper(substr(bin2hex(random_bytes(8)), 0, 16));
}

function subscription_api_hash(
    string $transactionId,
    string $userId,
    string $result,
    int $price,
    int $timestamp
): string
{
    $salt = subscription_config()['hash_salt'];
    return hash('sha256', "{$transactionId}:{$userId}:{$result}:{$price}:{$timestamp}:{$salt}");
}

function subscription_return_hash(
    string $transactionId,
    string $userId,
    string $result,
    int $price,
    int $timestamp
): string {
    return subscription_api_hash($transactionId, $userId, $result, $price, $timestamp);
}

/**
 * NAVI /api/sub_creditsadd の形式B署名（item_nameなし）。
 * transaction_id:user_id:result:price:timestamp:HASH_SALT
 */
function subscription_credit_hash(
    string $transactionId,
    string $userId,
    string $result,
    int $price,
    int $timestamp
): string {
    return subscription_api_hash($transactionId, $userId, $result, $price, $timestamp);
}

function subscription_credit_hash_source(
    string $transactionId,
    string $userId,
    string $result,
    int $price,
    int $timestamp
): string {
    $salt = subscription_config()['hash_salt'];
    return "{$transactionId}:{$userId}:{$result}:{$price}:{$timestamp}:{$salt}";
}

/**
 * receive_test.php からのクレジット数上書きを検証する署名です。
 * ブラウザへhash_saltを渡さず、通常のテレコム通知では上書きできないようにします。
 */
function subscription_receive_test_signature(string $settleUuid, int $credits): string
{
    return hash_hmac(
        'sha256',
        "receive-test:{$settleUuid}:{$credits}",
        (string)subscription_config()['hash_salt']
    );
}

function subscription_verify_receive_test_signature(
    string $settleUuid,
    int $credits,
    string $signature
): bool {
    if ($signature === '') {
        return false;
    }
    return hash_equals(subscription_receive_test_signature($settleUuid, $credits), $signature);
}

/**
 * receive_test.php のユーザー・コース選択を保護する署名です。
 * 選択したユーザー、プラン、金額、クレジット数をすべて署名対象にします。
 */
function subscription_receive_plan_test_signature(
    string $settleUuid,
    string $userId,
    int $planId,
    int $amount,
    int $credits,
    string $result = 'success'
): string {
    return hash_hmac(
        'sha256',
        "receive-plan-test:{$settleUuid}:{$userId}:{$planId}:{$amount}:{$credits}:{$result}",
        (string)subscription_config()['hash_salt']
    );
}

function subscription_verify_receive_plan_test_signature(
    string $settleUuid,
    string $userId,
    int $planId,
    int $amount,
    int $credits,
    string $signature,
    string $result = 'success'
): bool {
    if ($signature === '') {
        return false;
    }
    return hash_equals(
        subscription_receive_plan_test_signature(
            $settleUuid,
            $userId,
            $planId,
            $amount,
            $credits,
            $result
        ),
        $signature
    );
}

function subscription_status_request_hash(string $userId, int $timestamp): string
{
    $salt = subscription_config()['hash_salt'];
    return hash('sha256', "status:{$userId}:{$timestamp}:{$salt}");
}

/**
 * 課金開始日の月次応当日から次回課金予定日を計算します。
 * 31日などが存在しない月は、その月の末日に丸めます。
 */
function subscription_next_billing_date(?string $startedAt, ?int $nowTimestamp = null): ?string
{
    if (!$startedAt) {
        return null;
    }

    try {
        $start = new DateTimeImmutable($startedAt);
    } catch (Throwable $e) {
        return null;
    }

    $now = (new DateTimeImmutable('@' . ($nowTimestamp ?? time())))
        ->setTimezone($start->getTimezone());
    $day = (int)$start->format('j');
    $year = (int)$now->format('Y');
    $month = (int)$now->format('n');

    for ($offset = 0; $offset < 24; $offset++) {
        $monthBase = (new DateTimeImmutable(sprintf('%04d-%02d-01 00:00:00', $year, $month)))
            ->modify('+' . $offset . ' month');
        $lastDay = (int)$monthBase->format('t');
        $candidate = $monthBase->setDate(
            (int)$monthBase->format('Y'),
            (int)$monthBase->format('n'),
            min($day, $lastDay)
        );
        if ($candidate > $now) {
            return $candidate->format('Y-m-d');
        }
    }

    return null;
}

function subscription_post_api(string $url, array $data): array
{
    if ($url === '') {
        return ['ok' => false, 'http_code' => 0, 'response' => 'API URLが未設定です。'];
    }

    $startedAt = microtime(true);
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($data),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
    ]);
    $response = curl_exec($ch);
    $error = curl_error($ch);
    $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return [
        'ok' => $response !== false && $httpCode >= 200 && $httpCode < 300,
        'http_code' => $httpCode,
        'response' => $response === false ? $error : (string)$response,
        'curl_error' => $error,
        'elapsed_ms' => (int)round((microtime(true) - $startedAt) * 1000),
    ];
}

/**
 * 稼働中のrep.phpと同じJSON形式でNAVI APIへPOSTします。
 */
function subscription_post_json_api(string $url, array $data): array
{
    if ($url === '') {
        return ['ok' => false, 'http_code' => 0, 'response' => 'API URLが未設定です。'];
    }

    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($json === false) {
        return [
            'ok' => false,
            'http_code' => 0,
            'response' => 'JSON生成エラー: ' . json_last_error_msg(),
            'curl_error' => '',
            'elapsed_ms' => 0,
        ];
    }

    $startedAt = microtime(true);
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $json,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($json),
        ],
    ]);
    $response = curl_exec($ch);
    $error = curl_error($ch);
    $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return [
        'ok' => $response !== false && $httpCode >= 200 && $httpCode < 300,
        'http_code' => $httpCode,
        'response' => $response === false ? $error : (string)$response,
        'curl_error' => $error,
        'elapsed_ms' => (int)round((microtime(true) - $startedAt) * 1000),
        'request_json' => $json,
    ];
}

function subscription_request_data(): array
{
    $data = array_merge($_GET, $_POST);
    $contentType = (string)($_SERVER['CONTENT_TYPE'] ?? '');

    if (stripos($contentType, 'application/json') !== false) {
        $rawBody = file_get_contents('php://input');
        if (is_string($rawBody) && trim($rawBody) !== '') {
            $jsonData = json_decode($rawBody, true);
            if (is_array($jsonData)) {
                $data = array_merge($data, $jsonData);
            }
        }
    }

    return $data;
}

function subscription_telecom_request(array $data): bool
{
    $hasEventId = isset($data['settle_uuid']) || isset($data['transcode']);
    $hasResult = isset($data['rel']);
    $clientIpMatches = !isset($data['clientip'])
        || (string)$data['clientip'] === (string)subscription_config()['clientip'];

    // テレコムの日次決済通知はGETで届くため、HTTPメソッドでは除外しません。
    return $hasEventId && $hasResult && $clientIpMatches;
}

function subscription_output_success(?string $traceId = null, array $context = [])
{
    if ($traceId !== null) {
        subscription_trace_log($traceId, 'telecom_response_success', array_merge([
            'http_code' => 200,
            'body' => 'SuccessOK',
        ], $context));
    }
    header('Content-Type: text/plain; charset=UTF-8');
    echo 'SuccessOK';
    exit;
}

function subscription_output_retry(string $message, ?string $traceId = null, array $context = [])
{
    subscription_log('テレコム再送要求', ['message' => $message]);
    if ($traceId !== null) {
        subscription_trace_log($traceId, 'telecom_response_retry', array_merge([
            'http_code' => 500,
            'body' => 'TemporaryError',
            'message' => $message,
        ], $context));
    }
    http_response_code(500);
    header('Content-Type: text/plain; charset=UTF-8');
    echo 'TemporaryError';
    exit;
}

function subscription_event_id(array $data, string $eventType): string
{
    $id = (string)($data['settle_uuid'] ?? $data['transcode'] ?? '');
    if ($id !== '') {
        return $id;
    }
    return $eventType . '_' . hash('sha256', json_encode($data, JSON_UNESCAPED_UNICODE));
}
