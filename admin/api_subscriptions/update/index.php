<?php
// ログファイルのパス（updateフォルダ内に生成されます）
$log_file = './logfile' . date("-Ym") . '.log';
header('Content-Type: application/json; charset=utf-8');

// ==========================================
// 🔍 【デバッグ】NAVIから来たデータを全てログに記録する
// ==========================================
$raw_input = file_get_contents('php://input');
error_log("\n[" . date('Y-m-d H:i:s') . "] =============================\n", 3, $log_file);
error_log("【通信メソッド】: " . ($_SERVER['REQUEST_METHOD'] ?? 'Unknown') . "\n", 3, $log_file);
error_log("【Content-Type】: " . ($_SERVER['CONTENT_TYPE'] ?? 'Unknown') . "\n", 3, $log_file);
error_log("【受信Raw JSON】: " . $raw_input . "\n", 3, $log_file);
error_log("【受信POST配列】: " . print_r($_POST, true) . "\n", 3, $log_file);

// JSONまたはPOSTデータの受け取り
$post_data = json_decode($raw_input, true) ?: $_POST;

// パラメータの抽出
$user_id        = $post_data['user_id'] ?? null;
$action         = $post_data['action'] ?? null;
$transaction_id = $post_data['transaction_id'] ?? null;
$timestamp      = $post_data['timestamp'] ?? null;
$received_hash  = $post_data['hash'] ?? null;
// 退会ステータスが直接送られてきた場合用
$status_param   = $post_data['subscription_status'] ?? null;

// 何が足りなくてエラーになったのかをログに書き出す
if (!$user_id || !$action || !$transaction_id || !$timestamp || !$received_hash) {
    error_log("【⚠️パラメータ不足エラー】\n", 3, $log_file);
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Missing required parameters']);
    exit;
}

// ----------------------------------------------------
// 認証とDB更新
// ----------------------------------------------------
// ⚠️注意: 現在 $key が2回定義されています。NAVI側（Node.js）で使っている
// 正しいシークレットキーの方を残してください！
$key = ".#'Q\\6aVHt'O";
// $key = "lionking"; // ←もしNAVIがこっちを使っているなら上をコメントアウト

$formula = implode(':', [$user_id, $action, $transaction_id, $timestamp, $key]);
$calculated_hash = hash('sha256', $formula);

if ($received_hash !== $calculated_hash) {
    error_log("【API認証エラー】ハッシュ不一致\n", 3, $log_file);
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Invalid signature']);
    exit;
}

try {
    $db_config = require __DIR__ . '/db_config.php';

    // ★ 修正：薄らボケカスな localhost を完全排除し、正しいホストへ！
    $host = isset($db_config['host']) ? $db_config['host'] : 'mysql10086.xserver.jp';
    $dsn = 'mysql:host=' . $host . ';dbname=' . $db_config['db'] . ';charset=utf8';

    $pdo = new PDO($dsn, $db_config['user'], $db_config['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // 2. 二重処理防止のチェック（処理の最初に移動して無駄な通信を防ぐ）
    $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM navi_user WHERE last_transaction_id = :transaction_id");
    $check_stmt->execute([':transaction_id' => $transaction_id]);

    if ($check_stmt->fetchColumn() > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Duplicate transaction ignored']);
        exit;
    }

    // 1. 金庫（DB）から REBUILD ID を引っ張り出す
    $stmt = $pdo->prepare("SELECT telecom_rebill_id, subscription_status FROM navi_user WHERE user_id = :user_id");
    $stmt->execute([':user_id' => $user_id]);
    $user = $stmt->fetch();

    if ($user) {
        $telecom_rebill_id = $user['telecom_rebill_id'];
        error_log("[" . date('Y-m-d H:i:s') . "] 【ID抽出成功】ユーザー({$user_id})の REBUILD ID: {$telecom_rebill_id} を使用して処理を実行します\n", 3, $log_file);

        // ====================================================
        // 🎯 テレコムへの退会（キャンセル）処理
        // ====================================================
        // NAVIから「退会」の指示が来た場合（action名やステータスで判定）
        if ($action === 'update_status' || $action === 'cancel' || $status_param === 'inactive') {

            if (!empty($telecom_rebill_id)) {
                // ※マニュアルに合わせてURLの末尾を .cgi か .pl に調整してください
                $telecom_cancel_url = "https://secure.telecomcredit.co.jp/inetcredit/secure/cancel.cgi";

                $cancel_params = [
                    'clientip' => '72531',            // ハートフル様のIPコード
                    'relcode'  => $telecom_rebill_id, // 止めたいID
                    'sendpass' => 'NA'                // マニュアル指定の固定値
                ];

                $ch = curl_init($telecom_cancel_url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($cancel_params));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 15);
                $response = curl_exec($ch);
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                error_log("[" . date('Y-m-d H:i:s') . "] 【テレコム退会通信】HTTP:{$http_code}, レスポンス:{$response}\n", 3, $log_file);
            }

            // DBのステータスを「inactive (退会)」に更新
            $update_stmt = $pdo->prepare("UPDATE navi_user SET subscription_status = 'inactive', last_transaction_id = :tx_id, updated_at = NOW() WHERE user_id = :user_id");
            $update_stmt->execute([':tx_id' => $transaction_id, ':user_id' => $user_id]);

            echo json_encode(['status' => 'success', 'message' => 'Subscription canceled successfully']);
            exit;
        }

        // 🎯 (もし他のaction、例えば 'reset_credit' などがあればここに追記できます)

    } else {
        error_log("[" . date('Y-m-d H:i:s') . "] 【API通知】未登録ユーザー(ID: {$user_id})のため自動登録します\n", 3, $log_file);
        $insert_stmt = $pdo->prepare("INSERT INTO navi_user (user_id, subscription_status, created_at, updated_at) VALUES (:user_id, 'active', NOW(), NOW())");
        $insert_stmt->execute([':user_id' => $user_id]);
    }

    // 通常の処理完了として last_transaction_id を更新
    $update_stmt = $pdo->prepare("UPDATE navi_user SET last_transaction_id = :tx_id, updated_at = NOW() WHERE user_id = :user_id");
    $update_stmt->execute([':tx_id' => $transaction_id, ':user_id' => $user_id]);

    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    error_log("【DBエラー】" . $e->getMessage() . "\n", 3, $log_file);
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database error occurred']);
}
exit;
