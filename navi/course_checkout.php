<?php
// course.phpでJWT・CSRF・商品を検証した後にだけ読み込む。
if (!defined('COURSE_CHECKOUT_AUTHORIZED') || COURSE_CHECKOUT_AUTHORIZED !== true || !isset($coursePurchase)) {
    http_response_code(404);
    exit;
}

require_once __DIR__ . '/course_payment_lib.php';

$transactionId = 'txn_course_' . bin2hex(random_bytes(16));
$fields = course_one_time_payment_fields($coursePurchase, $transactionId);
// 定額クレジット購入のセッションとは分ける。
$_SESSION['course_payment'] = [
    'user_id' => $coursePurchase['user_id'],
    'item_name' => $coursePurchase['item_name'],
    'price' => $coursePurchase['price'],
    'pricing_plan' => $coursePurchase['pricing_plan'],
    'transaction_id' => $transactionId,
    'created_at' => time(),
];
subscription_log('course_checkout', [
    'user_id' => $coursePurchase['user_id'],
    'item_name' => $coursePurchase['item_name'],
    'price' => $coursePurchase['price'],
    'pricing_plan' => $coursePurchase['pricing_plan'],
    'transaction_id' => $transactionId,
]);
session_write_close();
?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="referrer" content="no-referrer">
    <title>コース購入内容の確認</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; background: #f6f8fa; color: #24333e; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; line-height: 1.7; }
        header { padding: 28px 16px; background: #019795; color: #fff; text-align: center; }
        h1 { margin: 0; font-size: 1.6rem; }
        main { max-width: 700px; margin: 28px auto; padding: 0 16px; }
        .box { padding: 24px; background: #fff; border: 1px solid #dce3e8; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px 8px; text-align: left; border-bottom: 1px solid #dce3e8; }
        td:last-child, th:last-child { text-align: right; }
        .total { font-weight: 700; font-size: 1.15rem; }
        button { width: 100%; padding: 14px; border: 0; background: #147bb9; color: #fff; font: inherit; font-weight: 700; cursor: pointer; }
        button:hover { background: #106596; }
        .back { display: block; margin-top: 16px; text-align: center; color: #147bb9; }
    </style>
</head>
<body>
<header><h1>コース購入内容の確認</h1></header>
<main>
    <div class="box">
        <table>
            <thead><tr><th scope="col">商品名</th><th scope="col">料金（税込）</th></tr></thead>
            <tbody>
                <tr><td><?= $escape($coursePurchase['course_name']) ?></td><td><?= $escape($priceLabel) ?></td></tr>
                <tr class="total"><td>合計</td><td><?= $escape($priceLabel) ?></td></tr>
            </tbody>
        </table>
        <p><?= $escape($pricing['label']) ?>・買い切りの1回払いです。毎月の課金・自動更新はありません。</p>
        <form action="https://secure.telecomcredit.co.jp/inetcredit/secure/order.pl" method="post" target="_top">
            <?php foreach ($fields as $name => $value): ?>
                <input type="hidden" name="<?= $escape($name) ?>" value="<?= $escape((string)$value) ?>">
            <?php endforeach; ?>
            <button type="submit">クレジットカード決済画面へ</button>
        </form>
        <a class="back" href="<?= $escape($coursePage) ?>?<?= $escape(http_build_query([
            'token' => $token,
            'callback' => $coursePurchase['callback'],
            'navi_mode' => $coursePurchase['navi_mode'],
            'course' => $coursePurchase['item_name'],
        ])) ?>">コースを選び直す</a>
    </div>
</main>
</body>
</html>
