<?php

// DB接続なしで金額とコース対応を検証する。
$testConfig = [
    'plans' => [
        980 => ['rebill_param_id' => '1month980yen', 'credits' => 16, 'label' => '毎月16クレジット'],
        1980 => ['rebill_param_id' => '1month1980yen', 'credits' => 34, 'label' => '毎月34クレジット'],
        2980 => ['rebill_param_id' => '1month2980yen', 'credits' => 54, 'label' => '毎月54クレジット'],
        3980 => ['rebill_param_id' => '1month3980yen', 'credits' => 72, 'label' => '毎月72クレジット'],
    ],
];

function mappedPlan(array $config, $value): ?array
{
    $value = str_replace([',', '円', ' '], '', (string)$value);
    if ($value === '' || !ctype_digit($value)) {
        return null;
    }
    return $config['plans'][(int)$value] ?? null;
}

$cases = [
    ['980', '1month980yen', 16],
    ['1,980円', '1month1980yen', 34],
    [2980, '1month2980yen', 54],
    ['3980', '1month3980yen', 72],
];

foreach ($cases as [$input, $rebillId, $credits]) {
    $plan = mappedPlan($testConfig, $input);
    assert($plan !== null);
    assert($plan['rebill_param_id'] === $rebillId);
    assert($plan['credits'] === $credits);
}

assert(mappedPlan($testConfig, '1') === null);
assert(mappedPlan($testConfig, '980.0') === null);
assert(mappedPlan($testConfig, 'abc') === null);
echo "logic_test: OK\n";
