<?php

/**
 * NAVI側の戻り先で使用する検証例。
 * JWT鍵そのものをURLで受け取る旧方式は使用しません。
 */
function verifySubscriptionCallback(array $query, string $callbackSecret, string $expectedKey): bool
{
    foreach (['transaction_id', 'result', 'price', 'timestamp', 'hash', 'key'] as $required) {
        if (!isset($query[$required])) {
            return false;
        }
    }

    if (!hash_equals($expectedKey, (string)$query['key'])) {
        return false;
    }

    $timestamp = (int)$query['timestamp'];
    if (abs(time() - $timestamp) > 300) {
        return false;
    }

    $message = implode(':', [
        (string)$query['transaction_id'],
        (string)$query['result'],
        (int)$query['price'],
        $timestamp,
        $callbackSecret,
    ]);
    $expected = hash('sha256', $message);
    return hash_equals($expected, (string)$query['hash']);
}
