<?php
// 外部決済・DB・JWTを呼び出さずに金額と通知パラメータを確認する。
require_once __DIR__ . '/../course_payment_lib.php';

function course_check(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

$purchase = [
    'user_id' => 'test-buyer',
    'email' => 'buyer@example.invalid',
    'item_name' => 'course_tarot',
    'callback' => 'my/courses',
    'payment_type' => 'one_time',
];
foreach (['special' => 2980, 'student' => 3980, 'general' => 4980] as $plan => $price) {
    $fields = course_one_time_payment_fields($purchase + [
        'pricing_plan' => $plan, 'price' => $price,
    ], 'test-transaction');
    course_check($fields['money'] === $price, '決済金額が料金区分と不一致');
    $returnUrl = parse_url($fields['redirect_url']);
    course_check($returnUrl['host'] === 'school.heartf.com', '戻り先ホストが不正');
    course_check($returnUrl['path'] === '/kanaeru/rep.php', '単発決済の戻り先が不正');
    parse_str($returnUrl['query'], $query);
    course_check((int)$query['price'] === $price, '購入後通知の金額が不一致');
    course_check($query['item_name'] === 'course_tarot', '商品コードが変化');
    course_check($query['transaction_id'] === 'test-transaction', '取引IDが変化');
    course_check($query['user_id'] === 'test-buyer', '購入者が変化');
    course_check($query['callback'] === 'my/courses', '戻り先パスが変化');
    course_check(count($fields) === 6, '単発購入以外の項目が追加');
    foreach (['rebill_param_id', 'cont', 'sendpass'] as $field) {
        course_check(!isset($fields[$field]), '継続課金パラメータが混入');
    }
}

foreach ([
    ['pricing_plan' => 'general', 'price' => 2980],
    ['pricing_plan' => 'student', 'price' => 2980],
    ['pricing_plan' => 'special', 'price' => 1],
    ['pricing_plan' => 'unknown', 'price' => 2980],
    ['pricing_plan' => 'general', 'price' => 4980, 'payment_type' => 'recurring'],
] as $invalid) {
    try {
        course_one_time_payment_fields(array_merge($purchase, $invalid), 'test-transaction');
    } catch (RuntimeException $e) {
        continue;
    }
    throw new RuntimeException('不正な金額・区分・支払方式を受け入れた');
}
echo "course_payment_test: OK\n";
