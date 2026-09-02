<?php

require_once '../vendor/autoload.php'; // Composerのオートローダー

use \Firebase\JWT\JWT;

$key = ".#'Q\\6aVHt'O";  // バックスラッシュをエスケープ
// 5秒の時刻ズレを許容
JWT::$leeway = 200;


// トークンがGETパラメータとして渡されているか確認
if (isset($_GET['token'])) {
    $jwt = $_GET['token'];  // GETで渡されたトークンを取得
    try {
        // トークンをデコード（アルゴリズムはHS256を指定）
        $decoded = JWT::decode($jwt, $key, array('HS256'));
        $decoded_array = (array) $decoded;  // デコード結果を配列に変換
        $user_email = $decoded_array['user']->account;  // ユーザーのメールアドレス取得
        $user_id = $decoded_array['user']->id;  // ユーザーのメールアドレス取得
    } catch (Exception $e) {
        // エラーが発生した場合にメッセージを表示
        echo 'Error decoding JWT: ' . $e->getMessage();
        exit;
    }
} else {
    echo "No token provided.";
    exit;
}

// GETパラメータから価格と商品名を取得
$callback = isset($_GET['callback']) ? str_replace("_", "/", $_GET['callback']) : '不明';
$price = isset($_GET['price']) ? $_GET['price'] : '不明';
$item_name = isset($_GET['item_name']) ? $_GET['item_name'] : '不明';

// 金額を整数に変換し、「円」を付ける
$price_int = intval($price);
$price_with_unit = number_format($price_int) . '円';

// 金額を整数に変換して送信する（例: 66000）
$money = intval($price_int);

$transaction_id = '';

if (strpos($referrer, 'https://kanaeru.heartf.com') !== false) {
    // `kanaeru.heartf.com` からのアクセスなら新規に `transaction_id` を生成
    $transaction_id = uniqid('txn_', true) . '_' . rand(100, 999) . '_' . time();
    $_SESSION['transaction_id'] = $transaction_id; // セッションに保存
} elseif (isset($_SESSION['transaction_id'])) {
    // それ以外の場合はセッションから `transaction_id` を読み込む
    $transaction_id = $_SESSION['transaction_id'];
} else {
    // `transaction_id` がセッションにない場合、エラーを返す
    $transaction_id = uniqid('txn_', true) . '_' . rand(1000, 9999) . '_' . time();
    $_SESSION['transaction_id'] = $transaction_id; // セッションに保存

    //    die('Transaction ID not found.');
}


// 金額を整数に変換し、「円」を付ける
$price_int = intval($price);
$price_with_unit = number_format($price_int) . '円';

// 取得した情報を次のページへGETリクエストで渡すURLを構築
$redirect_url = "https://school.heartf.com/kanaeru/rep.php?user_id=" . urlencode($user_id) . "&price=" . urlencode($price_int) . "&item_name=" . urlencode($item_name) . "&transaction_id=" . urlencode($transaction_id) . "&callback=" . urlencode($callback);



// 商品名をNクレジットに変換
if (preg_match('/item_jp_new_(\d+)/', $item_name, $matches)) {
    $item_name = $matches[1] . 'クレジット';  // Nクレジットに変換
} else {
    $item_name = '不明';
}


?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ライセンス購入</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-image: url('kanaeruheader.jpg');
            background-size: cover;
            height: 200px;
            color: white;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            background-position: center;
        }

        header h1 {
            font-size: 3rem;
            background-color: rgba(0, 0, 0, 0.5);
            padding: 10px 20px;
            border-radius: 10px;
        }

        .content {
            margin: 20px auto;
            padding: 20px;
            max-width: 800px;
        }

        .contact_form {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .contact_form th,
        .contact_form td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .contact_form th {
            background-color: #f4f4f4;
        }

        .c_right {
            text-align: left;
        }

        .c_left {
            text-align: right;
        }

        .total {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .submit {
            text-align: center;
            margin-top: 20px;
        }

        .submit button {
            background-color: #007bff;
            color: white;
            font-size: 1.2rem;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .submit button:hover {
            background-color: #0056b3;
        }

        #footer {
            width: 100%;
            text-align: center;
            clear: both;
            padding-bottom: 15px;
            background-color: #019795;
            color: #FFFFFF;
            min-width: 1100px;
            padding: 10px 0;
        }
    </style>
</head>

<body>
    <header>
        <h1>ライセンス購入</h1>
    </header>

    <div class="content">
        <p>以下のフォームに必要事項を入力して購入手続きを行ってください。</p>

        <form action="https://secure.telecomcredit.co.jp/inetcredit/secure/order.pl" method="post" target="_top">
            <div align="center">
                <table class="contact_form">
                    <tbody>
                        <tr>
                            <th>商品名</th>
                            <th>料金</th>
                        </tr>
                        <tr>
                            <td class="c_right">商品名: <?= htmlspecialchars($item_name) ?>&nbsp;</td>
                            <td class="c_left"><?= (htmlspecialchars($price_with_unit)) ?>&nbsp;</td>
                        </tr>
                        <tr class="total">
                            <td class="c_right">合計</td>
                            <td class="c_left"><?= (htmlspecialchars($price_with_unit)) ?>&nbsp;</td>
                        </tr>
                    </tbody>
                </table>

                <!-- hidden fields -->
                <input type="hidden" name="clientip" value="72531"> <!-- 固定値 -->
                <input type="hidden" name="money" value="<?= ($price_int) ?>"> <!-- 価格の整数表記（例: 66000） -->
                <input type="hidden" name="usrtel" value="08020926854"> <!-- 固定値 -->
                <input type="hidden" name="usrmail" value="<?= htmlspecialchars($user_email) ?>"> <!-- デコードしたメールアドレス -->
                <input type="hidden" name="sendid" value="SHCOOL000000007"> <!-- 固定値 -->
                <input type="hidden" name="redirect_url" value="<?= htmlspecialchars($redirect_url) ?>">
                <!-- ユーザーデータ付きのリダイレクトURL -->

                <div class="submit">
                    <button type="submit">クレジット決済画面へ</button>
                </div>
            </div>
        </form>
    </div>

    <div id="footer">
        &copy; 2024 Kanaeru. All Rights Reserved.
    </div>
</body>

</html>