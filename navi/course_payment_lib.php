<?php

/** 料金は入口PHPが選ぶ。GET/POSTの金額・区分は使用しない。 */
function course_pricing(string $plan): array
{
    $plans = [
        'special' => ['price' => 2980, 'label' => '特別価格', 'page' => 'course.php'],
        'student' => ['price' => 3980, 'label' => '生徒・先生価格', 'page' => 'course_student.php'],
        'general' => ['price' => 4980, 'label' => '一般価格', 'page' => 'course_general.php'],
    ];
    if (!isset($plans[$plan])) {
        throw new RuntimeException('料金区分が不正です。');
    }
    return $plans[$plan];
}

/**
 * kanaeru/index.phpの都度購入と同じ接続先・加盟店値・戻り先を使用する。
 * rebill_param_idやcont等の継続課金パラメータは含めない。
 */
function course_one_time_payment_fields(array $purchase, string $transactionId): array
{
    $pricing = course_pricing($purchase['pricing_plan'] ?? 'special');
    if (($purchase['payment_type'] ?? '') !== 'one_time' || ($purchase['price'] ?? null) !== $pricing['price']) {
        throw new RuntimeException('購入情報が不正です。');
    }
    return [
        'clientip' => '72531',
        'money' => $pricing['price'],
        'usrtel' => '08020926854',
        'usrmail' => (string)$purchase['email'],
        'sendid' => 'SHCOOL000000007',
        'redirect_url' => 'https://school.heartf.com/kanaeru/rep.php?' . http_build_query([
            'user_id' => (string)$purchase['user_id'],
            'price' => $pricing['price'],
            'item_name' => (string)$purchase['item_name'],
            'transaction_id' => $transactionId,
            'callback' => (string)$purchase['callback'],
        ]),
    ];
}
