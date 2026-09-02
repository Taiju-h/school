<?php
/*
 $key = ".#'Q\\6aVHt'O";  // バックスラッシュをエスケープ

* method:POST
 https://kanaeru.heartf.com/api/creditsadd
 body:
 {
 "user_id":$_POST['id'],
 "price":$_POST['price'],
 "item_name":$_POST['item'],
 "transaction_id":$_POST['transaction_id'],
 "timestamp":time(),
 "hash": $key
 }
 
 */


// ログファイルのパスを設定
$log_file = './logfile' . ("-Ym") . '.log'; // ログファイルのパスを指定してください

// IPアドレスを取得
$ip_address = $_SERVER['REMOTE_ADDR'];

// ホスト名やプロバイダ名を取得（ただし、リバースDNSが有効でない場合は失敗する可能性があります）
$host_name = gethostbyaddr($ip_address);

// ブラウザ情報を取得
$user_agent = $_SERVER['HTTP_USER_AGENT'];

// サーバーのHTTPヘッダー情報
$server_info = $_SERVER;

// ログに環境変数とユーザー情報を書き出す
error_log("User Environment Info:\n", 3, $log_file);
error_log("IP Address: " . $ip_address . "\n", 3, $log_file);
error_log("Host Name / Provider: " . $host_name . "\n", 3, $log_file);
error_log("User Agent: " . $user_agent . "\n", 3, $log_file);
error_log("Server Info: " . print_r($server_info, true) . "\n", 3, $log_file);



?><?php

    // POSTデータを取得
    $user_id = $_GET['user_id'];
    $price = $_GET['price'];
    $item_name = $_GET['item_name'];
    $transaction_id = $_GET['transaction_id'];
    //$transaction_id ="tx_" . rand(1000, 9999);
    $timestamp = time(); // 現在のタイムスタンプ
    $key = ".#'Q\\6aVHt'O";  // バックスラッシュをエスケープ

    $salt = "lionking";
    $data = "{$user_id}:{$item_name}:{$transaction_id}:{$timestamp}:{$salt}";

    // saltとdataを結合
    $combined_data = $data;

    // SHA-256ハッシュを生成
    $hash = hash('sha256', $combined_data);

    // ログに生成されたデータとハッシュを書き出す
    error_log("Generated hash data: " . $combined_data . "\nHash: " . $hash, 3, $log_file);


    // APIに送信するデータを設定
    $data = [
        'user_id' => $user_id,
        'price' => $price,
        'item_name' => $item_name,
        'transaction_id' => $transaction_id,
        'timestamp' => $timestamp,
        'hash' => $hash
    ];

    echo "<pre><hr>";
    var_dump($data);
    echo "</pre><hr>";

    // APIレスポンスのログ
    error_log("API Response: " . $response, 3, $log_file);


    // cURLを使用してAPIにPOSTリクエストを送信
    $ch = curl_init('https://kanaeru.heartf.com/api/creditsadd');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); // JSON形式でデータを送信
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json', // JSONデータとして送信
    ]);

    // APIからのレスポンスを取得
    $response = curl_exec($ch);

    // cURLエラーの確認
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
        exit;
    }

    curl_close($ch);

    // APIのレスポンスをデコード
    $callbackData = json_decode($response, true);

    // APIレスポンスのコールバックデータが取得できているか確認
    //レスポンスが成功なら
    //if (isset($callbackData['callback'])) {
    if ($callbackData['status'] == 'success') {
        $callback = $_GET['callback']; // APIからのコールバックデータ
        // ログに成功のメッセージを記録
        error_log("API Success: Redirecting to thanks page", 3, $log_file);

        // リダイレクトURLを構築
        $redirectUrl = "https://kanaeru.heartf.com/credits/thanks?callback=" . urlencode($callback) . "&item_name=" . urlencode($item_name) . "&transaction_id=" . urlencode($transaction_id);
        // リダイレクト
        header("Location: " . $redirectUrl);
        exit;
    } else {

        error_log("API Response Error: " . print_r($response, true), 3, $log_file);

        // エラーハンドリング（APIのレスポンスが正しくない場合）
        echo 'API response error: ' . $response;
    }
    ?>
