<?php
session_start(); // セッション開始
$navi_mode = $_SESSION['navi_mode'] ?? 'prod';

$log_file = './logfile' . date("-Ym") . '.log';
error_log("[" . date('Y-m-d H:i:s') . "] --- 定期課金リターン処理開始 ---\n", 3, $log_file);

// 1. 設定読み込み
$db_config = require '/home/heartf/heartf.com/public_html/db_config.php';
$dsn = 'mysql:host=' . $db_config['host'] . ';dbname=' . $db_config['db'] . ';charset=utf8';

// 2. パラメータ受け取り
$navi_mode = $_GET['navi_mode'] ?? 'prod';
$navi_domain = ($navi_mode === 'stg') ? "https://navi-stg.v-mm.com" : "https://navi.uranai.pw";

$transaction_id = $_GET['transaction_id'] ?? '';
$price          = $_GET['price'] ?? '';
$callback_path  = $_GET['callback'] ?? '';
$token          = $_GET['token'] ?? '';
$user_id        = $_GET['user_id'] ?? '';
$relcode        = $_REQUEST['relcode'] ?? 'test_rebill_' . time();
$result         = 'success';
$timestamp      = time();

// 3. DB登録
if (!empty($user_id)) {
    try {
        $pdo = new PDO($dsn, $db_config['user'], $db_config['pass']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO navi_user (user_id, telecom_rebill_id, subscription_status, created_at, updated_at) 
                VALUES (:user_id, :relcode, 'active', NOW(), NOW())
                ON DUPLICATE KEY UPDATE 
                telecom_rebill_id = :relcode, 
                subscription_status = 'active', 
                updated_at = NOW()";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':user_id' => $user_id, ':relcode' => $relcode]);
        error_log("[" . date('Y-m-d H:i:s') . "] 【DB登録成功】user_id: {$user_id}\n", 3, $log_file);
    } catch (PDOException $e) {
        error_log("[" . date('Y-m-d H:i:s') . "] 【DB登録エラー】" . $e->getMessage() . "\n", 3, $log_file);
    }
}

// 4. ハッシュ計算
$salt = (require __DIR__ . '/legacy_private.php')['teigaku_rep_php_salt'];
$formula = "{$transaction_id}:{$result}:{$price}:{$timestamp}:{$salt}";
$hash = hash('sha256', $formula);

// 5. リダイレクトURL作成
$callback_path = ltrim($callback_path, '/');
$target_url = rtrim($navi_domain, '/') . '/' . $callback_path;
$params = http_build_query([
    'transaction_id' => $transaction_id,
    'result'         => $result,
    'price'          => $price,
    'timestamp'      => $timestamp,
    'hash'           => $hash,
    'token'          => $token
]);

$redirect_url = $target_url . (strpos($target_url, '?') === false ? '?' : '&') . $params;
error_log("[" . date('Y-m-d H:i:s') . "] 【リダイレクト先】: " . $redirect_url . "\n", 3, $log_file);

header("Location: " . $redirect_url);
exit;
