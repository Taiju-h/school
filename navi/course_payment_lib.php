<?php

/**
 * kanaeru/index.phpの都度購入と同じ接続先・加盟店値・戻り先を使用する。
 * rebill_param_idやcont等の継続課金パラメータは含めない。
 */
function course_one_time_payment_fields(array $purchase, string $transactionId): array
{
    if (($purchase['payment_type'] ?? '') !== 'one_time' || (int)($purchase['price'] ?? 0) !== 2980) {
        throw new RuntimeException('購入情報が不正です。');
    }
    return [
        'clientip' => '72531',
        'money' => 2980,
        'usrtel' => '08020926854',
        'usrmail' => (string)$purchase['email'],
        'sendid' => 'SHCOOL000000007',
        'redirect_url' => 'https://school.heartf.com/kanaeru/rep.php?' . http_build_query([
            'user_id' => (string)$purchase['user_id'],
            'price' => 2980,
            'item_name' => (string)$purchase['item_name'],
            'transaction_id' => $transactionId,
            'callback' => (string)$purchase['callback'],
        ]),
    ];
}
