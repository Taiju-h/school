<?php

/**
 * navi/webhook.php
 * テレコムクレジットからの「決済完了通知（裏側）」を受け取るエンドポイント
 * ※ CakePHPには依存せず、純粋なPHPとPDOで動作します。
 */

// ログ設定（naviディレクトリ内に webhook_log_YYYYMM.log を生成）
$log_file = __DIR__ . '/webhook_log_' . date("Ym") . '.log';
error_log("\n[" . date('Y-m-d H:i:s') . "] === テレコムWebhook受信開始 ===\n", 3, $log_file);

// 1. IPアドレス制限（セキュリティ強化）
$allowedIps = ['54.65.177.67', '52.196.8.0', '54.238.8.174', '172.104.99.119', '54.95.89.20'];
$clientIp = $_SERVER['REMOTE_ADDR'] ?? '';

if (!in_array($clientIp, $allowedIps)) {
    error_log("【エラー】未許可IPからのアクセス: {$clientIp}\n", 3, $log_file);
    echo 'SuccessOK'; // エラー時も必ずSuccessOKを返す（再送ループ防止）
    exit;
}

// POST以外は弾く
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo 'SuccessOK';
    exit;
}

// 2. テレコムからのPOSTデータを取得
$rebillId      = $_POST['relcode'] ?? null;       // 継続決済ID
$transactionId = $_POST['transcode'] ?? null;     // トランザクションID
$userIdParam   = $_POST['user_id_param'] ?? null; // 入口で仕込んだ NAVI_ID
$userMail      = $_POST['usrmail'] ?? null;       // 入口で仕込んだ メアド（保険）
$money         = $_POST['money'] ?? 0;            // 金額

error_log("【受信データ】relcode: {$rebillId}, transcode: {$transactionId}, user_id_param: {$userIdParam}, usrmail: {$userMail}\n", 3, $log_file);

// 必須パラメータチェック
if (empty($rebillId) || empty($transactionId)) {
    error_log("【エラー】必須パラメータ(relcode/transcode)不足\n", 3, $log_file);
    echo 'SuccessOK';
    exit;
}

// 3. データベース接続
try {
    $db_config = require __DIR__ . '/db_config.php';

    // 薄らボケカスなハードコードを排除し、正しいホストを指定
    $host = isset($db_config['host']) ? $db_config['host'] : 'mysql10086.xserver.jp';
    $dsn = 'mysql:host=' . $host . ';dbname=' . $db_config['db'] . ';charset=utf8';

    $pdo = new PDO($dsn, $db_config['user'], $db_config['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // 4. ユーザーを特定する（3段構え）
    $user = null;

    // 【ルート1】大本命: 入口で仕込んだ NAVI_ID (navi_user_id) で検索
    if (!empty($userIdParam)) {
        $stmt = $pdo->prepare("SELECT * FROM musers WHERE navi_user_id = :navi_user_id LIMIT 1");
        $stmt->execute([':navi_user_id' => $userIdParam]);
        $user = $stmt->fetch();
    }

    // 【ルート2】保険: user_id_param が消された場合、メアドで検索
    if (!$user && !empty($userMail)) {
        $stmt = $pdo->prepare("SELECT * FROM musers WHERE usrmail = :usrmail LIMIT 1");
        $stmt->execute([':usrmail' => $userMail]);
        $user = $stmt->fetch();
    }

    // 【ルート3】継続課金: 2ヶ月目以降の自動引き落とし時は、リビルIDで検索
    if (!$user) {
        $stmt = $pdo->prepare("SELECT * FROM musers WHERE telecom_rebill_id = :rebill_id LIMIT 1");
        $stmt->execute([':rebill_id' => $rebillId]);
        $user = $stmt->fetch();
    }

    // 5. ユーザーが見つかった場合の更新処理
    if ($user) {
        // 冪等性（重複処理防止）チェック
        if ($user['last_transaction_id'] === $transactionId) {
            error_log("【スキップ】既に処理済みのトランザクションです: {$transactionId}\n", 3, $log_file);
            echo 'SuccessOK';
            exit;
        }

        // 更新クエリのベース
        $update_sql = "UPDATE musers SET 
                        credit_balance = 50, 
                        subscription_status = 'active', 
                        last_transaction_id = :transcode";

        $params = [
            ':transcode' => $transactionId,
            ':id'        => $user['id']
        ];

        // 初回決済の場合（リビルIDが空の場合）は、IDを金庫にしまう！
        if (empty($user['telecom_rebill_id'])) {
            $update_sql .= ", telecom_rebill_id = :rebill_id";
            $params[':rebill_id'] = $rebillId;
            error_log("【初回登録】生徒ID: {$user['id']} に 新規リビルID: {$rebillId} を紐付けました。\n", 3, $log_file);
        }

        $update_sql .= " WHERE id = :id";

        // 更新実行
        $update_stmt = $pdo->prepare($update_sql);
        $update_stmt->execute($params);

        error_log("【課金成功】生徒ID: {$user['id']} のクレジットを更新しました。\n", 3, $log_file);
    } else {
        error_log("【エラー】該当する生徒が見つかりませんでした。\n", 3, $log_file);
    }
} catch (PDOException $e) {
    error_log("【DBエラー】" . $e->getMessage() . "\n", 3, $log_file);
}

// 6. 必須レスポンス（テレコムへの返事）
// 何があっても絶対に「SuccessOK」だけを出力して終了する
echo 'SuccessOK';
exit;
