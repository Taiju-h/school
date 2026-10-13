<?php
// 占術コースは買い切りです。定額クレジットの処理は呼び出しません。
require_once __DIR__ . '/subscription_lib.php';
subscription_start_session();
header('Cache-Control: no-store');
header('Referrer-Policy: no-referrer');

$courses = [
    'course_tarot' => 'タロット',
    'course_palmistry' => '手相',
    'course_western_astrology' => '西洋占星術',
    'course_numerology' => '数秘術',
    'course_ziwei' => '紫微斗数',
    'course_four_pillars' => '四柱推命',
    'course_sanmeigaku' => '算命学',
    'course_daneki' => '断易',
    'course_shueki' => '周易',
    'course_kyusei' => '九星気学',
    'course_name_divination' => '姓名判断',
    'course_mayan_calendar' => 'マヤ暦',
    'course_lenormand' => 'ルノルマンカード',
    'course_sukuyo' => '宿曜占星術',
];
$price = 2980;
$request = ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' ? $_POST : $_GET;
$scalar = static function (array $values, string $key, string $default = ''): string {
    return isset($values[$key]) && is_string($values[$key]) ? $values[$key] : $default;
};
$escape = static function (string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
};
$token = $scalar($request, 'token');
$callback = subscription_callback_path($scalar($request, 'callback', 'my'));
$mode = subscription_mode($scalar($request, 'navi_mode', 'prod'));
$selected = $scalar($request, 'item_name', $scalar($request, 'course'));
if (!isset($courses[$selected])) {
    $selected = '';
}
if (!isset($_SESSION['course_csrf'])) {
    $_SESSION['course_csrf'] = bin2hex(random_bytes(32));
}
$error = '';
$checkoutHandler = __DIR__ . '/course_checkout.php';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    try {
        if (!hash_equals($_SESSION['course_csrf'], $scalar($_POST, 'csrf'))) {
            throw new RuntimeException('画面を開き直して、もう一度お試しください。');
        }
        if ($selected === '') {
            throw new RuntimeException('購入するコースを選んでください。');
        }
        if ($token === '') {
            throw new RuntimeException('NAVIへログインしてから、この画面を開いてください。');
        }
        require_once __DIR__ . '/../vendor/autoload.php';
        // 都度クレジット購入のindex.phpと同じJWTキーを使用する。
        $legacyConfig = require __DIR__ . '/legacy_private.php';
        $jwtKey = (string)$legacyConfig['index_php_key'];
        \Firebase\JWT\JWT::$leeway = 200;
        if (class_exists('\\Firebase\\JWT\\Key')) {
            $decoded = \Firebase\JWT\JWT::decode(
                $token, new \Firebase\JWT\Key($jwtKey, 'HS256')
            );
        } else {
            $decoded = \Firebase\JWT\JWT::decode($token, $jwtKey, ['HS256']);
        }
        if (!isset($decoded->user->id, $decoded->user->account)) {
            throw new RuntimeException('NAVIへログインし直して、もう一度お試しください。');
        }
        // 金額・商品名・購入者をPOST値から決定させない。
        // 都度クレジット購入と同じテレコム1回払いへ渡す。
        $coursePurchase = [
            'user_id' => (string)$decoded->user->id,
            'email' => (string)$decoded->user->account,
            'item_name' => $selected,
            'course_name' => $courses[$selected],
            'price' => $price,
            'payment_type' => 'one_time',
            'callback' => $callback,
            'navi_mode' => $mode,
        ];
        define('COURSE_CHECKOUT_AUTHORIZED', true);
        require $checkoutHandler;
        exit;
    } catch (RuntimeException $e) {
        http_response_code(400);
        $error = $e->getMessage();
    } catch (Throwable $e) {
        http_response_code(400);
        $error = '購入手続きを開始できませんでした。NAVIから画面を開き直してください。';
    }
}
?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>占術コース購入</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; color: #24333e; background: #f6f8fa; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", "Noto Sans JP", sans-serif; line-height: 1.7; }
        header { min-height: 175px; display: grid; place-items: center; padding: 24px 16px; background: #087f83 url('header.jpg') center / cover; }
        header h1 { margin: 0; padding: 8px 22px; color: #fff; background: rgba(0,0,0,.56); font-size: clamp(1.6rem, 5vw, 2.4rem); }
        main { max-width: 960px; margin: 0 auto; padding: 30px 24px 42px; }
        .intro { margin: 0 0 22px; }
        .intro p { margin: 6px 0; }
        .tag { display: inline-block; padding: 3px 12px; color: #075d60; background: #e2f3f2; font-size: .9rem; font-weight: 700; }
        .error { padding: 12px 16px; border: 1px solid #ce6060; color: #8c2828; background: #fff0f0; }
        fieldset { margin: 0; padding: 0; border: 0; min-width: 0; }
        legend { margin-bottom: 16px; font-size: 1.1rem; font-weight: 700; }
        .options { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
        .option { position: relative; display: block; min-height: 118px; padding: 18px 54px 18px 20px; border: 2px solid #dce3e8; background: #fff; cursor: pointer; transition: border-color .15s, background .15s; }
        .option:hover { border-color: #6ca1c8; }
        .option.selected { border-color: #1779bd; background: #edf7ff; }
        .option:focus-within { outline: 3px solid #72b9e7; outline-offset: 2px; }
        .option input { position: absolute; top: 22px; right: 20px; width: 21px; height: 21px; accent-color: #1779bd; }
        .course-name { display: block; font-size: 1.14rem; font-weight: 700; overflow-wrap: anywhere; }
        .price { display: block; margin-top: 10px; color: #075d60; font-size: 1.12rem; font-weight: 700; }
        .tax { color: #61717c; font-size: .8rem; font-weight: 400; }
        .purchase { margin-top: 24px; padding: 22px; background: #fff; border: 1px solid #dce3e8; }
        .summary { display: flex; align-items: center; justify-content: space-between; gap: 16px; }
        .summary p { margin: 0; }
        .summary strong { font-size: 1.45rem; white-space: nowrap; }
        .note { color: #536570; margin: 12px 0 18px; font-size: .9rem; }
        button { display: block; width: 100%; padding: 13px 20px; border: 0; color: #fff; background: #147bb9; font: inherit; font-weight: 700; cursor: pointer; }
        button:hover { background: #106596; }
        button:disabled { color: #68767e; background: #e4eaee; cursor: default; }
        .status { margin: 14px 0 0; color: #536570; font-size: .9rem; }
        footer { padding: 16px; color: #fff; text-align: center; background: #019795; font-size: .85rem; }
        @media (max-width: 560px) { main { padding: 22px 14px 32px; } .options { grid-template-columns: 1fr; gap: 10px; } .option { min-height: 108px; } .purchase { padding: 18px; } .summary { align-items: flex-start; } }
    </style>
</head>
<body>
<header><h1>占術コース購入</h1></header>
<main>
    <div class="intro">
        <span class="tag">買い切り・1回払い</span>
        <p>購入するコースを選んでください。各コース <strong>2,980円（税込）</strong> です。</p>
    </div>
    <?php if ($error !== ''): ?><p class="error" role="alert"><?= $escape($error) ?></p><?php endif; ?>
    <form action="course.php" method="post" target="_top">
        <input type="hidden" name="token" value="<?= $escape($token) ?>">
        <input type="hidden" name="callback" value="<?= $escape($callback) ?>">
        <input type="hidden" name="navi_mode" value="<?= $escape($mode) ?>">
        <input type="hidden" name="csrf" value="<?= $escape($_SESSION['course_csrf']) ?>">
        <fieldset>
            <legend>コース一覧</legend>
            <div class="options">
                <?php foreach ($courses as $code => $name): ?>
                <label class="option<?= $selected === $code ? ' selected' : '' ?>">
                    <input type="radio" name="item_name" value="<?= $escape($code) ?>" data-name="<?= $escape($name) ?>" required<?= $selected === $code ? ' checked' : '' ?>>
                    <span class="course-name"><?= $escape($name) ?></span>
                    <span class="price">2,980円 <span class="tax">（税込）</span></span>
                </label>
                <?php endforeach; ?>
            </div>
        </fieldset>
        <div class="purchase">
            <div class="summary" aria-live="polite">
                <p id="selected-course"><?= $selected !== '' ? $escape($courses[$selected]) : 'コースを選択してください' ?></p>
                <strong id="selected-price"><?= $selected !== '' ? '2,980円' : '—' ?></strong>
            </div>
            <p class="note">お支払いは今回の1回のみです。毎月の課金・自動更新はありません。</p>
            <button type="submit"<?= $token === '' ? ' disabled' : '' ?>>選択したコースを購入する</button>
            <?php if ($token === ''): ?>
                <p class="status">購入するには、NAVIへログインしてからこの画面を開いてください。</p>
            <?php endif; ?>
        </div>
    </form>
</main>
<footer>&copy; <?= date('Y') ?> NAVI</footer>
<script>
(function () {
    var radios = document.querySelectorAll('input[name="item_name"]');
    function update() {
        for (var i = 0; i < radios.length; i++) {
            radios[i].parentNode.classList.toggle('selected', radios[i].checked);
            if (radios[i].checked) {
                document.getElementById('selected-course').textContent = radios[i].getAttribute('data-name');
                document.getElementById('selected-price').textContent = '2,980円';
            }
        }
    }
    for (var i = 0; i < radios.length; i++) { radios[i].addEventListener('change', update); }
    update();
}());
</script>
</body>
</html>
