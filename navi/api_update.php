<?php

/**
 * navi/api_update.php
 * NAVIからのサブスク更新・退会API (CakePHP完全非依存のスクラッチ版)
 */

header('Content-Type: application/json; charset=utf-8');

$log_file = __DIR__ . '/api_update_' . date("Ym") . '.log';
error_log("\n[" . date('Y-m-d H:i:s') . "] === API Update (独立版) 開始 ===\n", 3, $log_file);

// 1. NAVIから送信されたJSONペイロードの取得
$raw_input = file_get_contents('php://input');
$data = json_decode($raw_input, true);

error_log("【受信データ】" . $raw_input . "\n", 3, $log_file);

$userId = $data['user_id'] ?? null;
$status = $data['subscription_status'] ?? null;

// パラメータチェック
if (empty($userId)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Missing required parameters']);
    exit;
}

try {
    // 2. データベース接続（CakePHPを使わず直接接続）
    $db_config = require __DIR__ . '/db_config.php';
    $host = isset($db_config['host']) ? $db_config['host'] : 'mysql10086.xserver.jp';
    $dsn = 'mysql:host=' . $host . ';dbname=' . $db_config['db'] . ';charset=utf8';
    $pdo = new PDO($dsn, $db_config['user'], $db_config['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // ==========================================
    // 退会（inactive）処理
    // ==========================================
    if ($status === 'inactive') {

        // テレコム継続課金IDの取得
        $stmt = $pdo->prepare("SELECT telecom_rebill_id FROM navi_user WHERE user_id = :user_id LIMIT 1");
        $stmt->execute([':user_id' => $userId]);
        $user = $stmt->fetch();

        if ($user && !empty($user['telecom_rebill_id'])) {
            $rebillId = $user['telecom_rebill_id'];

            // テレコム退会用APIへ送信 (裏側通信)
            $telecomCancelUrl = "https://secure.telecomcredit.co.jp/inetcredit/secure/cancel.pl";
            $cancelParams = [
                'clientip' => '72531',  // ハートフル様クライアントIP
                'relcode'  => $rebillId // テレコム継続課金ID
            ];

            $ch = curl_init($telecomCancelUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($cancelParams));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            error_log("【テレコム退会通信】UID:{$userId}, relcode:{$rebillId}, HTTP:{$httpCode}, Resp:{$response}\n", 3, $log_file);
        } else {
            error_log("【警告】UID:{$userId} のテレコムIDが存在しません。\n", 3, $log_file);
        }

        // DBのステータスを更新 (テレコム通信の結果に関わらず停止させる)
        $update_stmt = $pdo->prepare("UPDATE navi_user SET subscription_status = 'inactive', updated_at = NOW() WHERE user_id = :user_id");
        $update_stmt->execute([':user_id' => $userId]);

        // 仕様書通りの成功レスポンスを返す
        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'message' => 'User subscription updated successfully.'
        ]);
        exit;
    }

    // inactive以外の処理が来た場合のとりあえずのエラー
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Unknown action or status']);
} catch (Exception $e) {
    error_log("【DB・システムエラー】" . $e->getMessage() . "\n", 3, $log_file);
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'System error occurred']);
}
exit;
