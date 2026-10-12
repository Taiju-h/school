<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/subscription_lib.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

subscription_start_session();
JWT::$leeway = 200;

function teigaku_decode_user(string $token, bool $test): array
{


    // if ($test) {
    //     return ['id' => '99999', 'email' => 'taiju.h@gmail.com'];
    // }
    if ($token === '') {
        throw new RuntimeException('tokenがありません。');
    }
    if (class_exists('\\Firebase\\JWT\\Key')) {
        $decoded = JWT::decode($token, new Key(subscription_config()['jwt_key'], 'HS256'));
    } else {
        $decoded = JWT::decode($token, subscription_config()['jwt_key'], ['HS256']);
    }
    if (!isset($decoded->user->id, $decoded->user->account)) {
        throw new RuntimeException('token内のユーザー情報が不足しています。');
    }
    return ['id' => (string)$decoded->user->id, 'email' => (string)$decoded->user->account];
}

try {
    $test = 0; //(($_REQUEST['pass'] ?? '') === 'heart');
    $token = (string)($_REQUEST['token'] ?? '');
    $user = teigaku_decode_user($token, $test);
    $callback = subscription_callback_path((string)($_REQUEST['callback'] ?? 'my'));
    $mode = subscription_mode((string)($_REQUEST['navi_mode'] ?? 'stg'));

    // テレコムクレジットに登録済みの継続決済設定です。
    // 画面やPOSTから受け取った値をそのまま使わず、金額からサーバー側で確定します。
    $rebillParamIds = [
        980  => '1month980yen',
        1980 => '1month1980yen',
        2980 => '1month2980yen',
        3980 => '1month3980yen',
    ];

    // テレコム接続テスト時だけ使用する1日周期の設定です。
    if ($test) {
        $rebillParamIds[6000] = '1day6000yen';
    }

    $pdo = subscription_pdo();

    // テストURLで開いた場合だけテストプランをDBへ用意します。
    // 通常アクセスでは、DBに残っていても下のSELECTで必ず除外します。
    if ($test) {
        $stmt = $pdo->prepare(
            'INSERT INTO subscription_plans
                (plan_code, plan_name, monthly_amount, credits_per_cycle, is_active)
             VALUES
                (\'1DAY_6000_TEST\', \'1日6000円コース（テスト）\', 6000, 1, 1)
             ON DUPLICATE KEY UPDATE
                plan_name = VALUES(plan_name),
                credits_per_cycle = VALUES(credits_per_cycle),
                is_active = 1,
                updated_at = NOW()'
        );
        $stmt->execute();
    }

    $plansSql =
        'SELECT id, plan_code, plan_name, monthly_amount, credits_per_cycle
           FROM subscription_plans
          WHERE is_active = 1';
    if (!$test) {
        $plansSql .= " AND plan_code <> '1DAY_6000_TEST'";
    }
    $plansSql .= ' ORDER BY monthly_amount';
    $plans = $pdo->query($plansSql)->fetchAll();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (($_POST['agreement'] ?? '') !== '1') {
            throw new RuntimeException('継続課金への同意が必要です。');
        }
        $planId = (int)($_POST['plan_id'] ?? 0);
        $planSql = 'SELECT * FROM subscription_plans WHERE id = :id AND is_active = 1';
        if (!$test) {
            $planSql .= " AND plan_code <> '1DAY_6000_TEST'";
        }
        $planSql .= ' LIMIT 1';
        $stmt = $pdo->prepare($planSql);
        $stmt->execute([':id' => $planId]);
        $plan = $stmt->fetch();
        if (!$plan) {
            throw new RuntimeException('選択された月額クレジットがありません。');
        }

        $monthlyAmount = (int)$plan['monthly_amount'];
        if (!isset($rebillParamIds[$monthlyAmount])) {
            throw new RuntimeException('選択された金額に対応する継続決済設定がありません。');
        }
        $rebillParamId = $rebillParamIds[$monthlyAmount];

        // sendidはテレコム側で新規申込みごとにユニークである必要があります。
        // NAVIのuser_idはoptionで別途送るため、sendidは申込み単位で新規発行します。
        $memberId = subscription_new_member_id($pdo, (string)$user['id']);

        // ユーザーごとのテレコムIDをDB保存値と送信値の両方に使用します。
        $effectiveCredits = ($test && $mode === 'stg' && $user['id'] === '99999')
            ? max(1, (int)$plan['credits_per_cycle'])
            : (int)$plan['credits_per_cycle'];
        $stmt = $pdo->prepare('SELECT telecom_password FROM navi_user WHERE user_id = :user_id LIMIT 1');
        $stmt->execute([':user_id' => $user['id']]);
        $existing = $stmt->fetch();
        $telecomPassword = (string)($existing['telecom_password'] ?? '');
        if ($telecomPassword === '') {
            $telecomPassword = subscription_password();
        }

        $stmt = $pdo->prepare(
            'INSERT INTO navi_user
                (user_id, plan_id, telecom_member_id, telecom_password, subscription_status,
                 monthly_amount, credits_per_cycle, navi_mode, callback_path, updated_at)
             VALUES
                (:user_id, :plan_id, :member_id, :password, \'pending\',
                 :amount, :credits, :navi_mode, :callback_path, NOW())
             ON DUPLICATE KEY UPDATE
                plan_id = VALUES(plan_id),
                telecom_member_id = VALUES(telecom_member_id),
                telecom_password = VALUES(telecom_password),
                subscription_status = \'pending\',
                monthly_amount = VALUES(monthly_amount),
                credits_per_cycle = VALUES(credits_per_cycle),
                navi_mode = VALUES(navi_mode),
                callback_path = VALUES(callback_path),
                updated_at = NOW()'
        );
        $stmt->execute([
            ':user_id' => $user['id'],
            ':plan_id' => $planId,
            ':member_id' => $memberId,
            ':password' => $telecomPassword,
            ':amount' => (int)$plan['monthly_amount'],
            ':credits' => $effectiveCredits,
            ':navi_mode' => $mode,
            ':callback_path' => $callback,
        ]);

        $_SESSION['teigaku_payment'] = [
            'user_id' => $user['id'],
            'plan_id' => $planId,
            'price' => $monthlyAmount,
            'credits' => $effectiveCredits,
            'rebill_param_id' => $rebillParamId,
            'callback' => $callback,
            'navi_mode' => $mode,
            'created_at' => time(),
        ];
        session_write_close();

        // STGの99999だけ、カード決済なしで成功通知からNAVI戻りまで再現します。
        if (
            $test
            && $mode === 'stg'
            && $user['id'] === '99999'
            && (string)($_POST['test_skip_payment'] ?? '') === '1'
        ) {
            $settleUuid = bin2hex(random_bytes(16));
            $testParams = http_build_query([
                'clientip' => (string)subscription_config()['clientip'],
                'id' => $memberId,
                'sendid' => $memberId,
                'option' => $user['id'],
                'email' => $user['email'],
                'user_name' => 'admin',
                'telno' => (string)subscription_config()['usrtel'],
                'money' => $monthlyAmount,
                'cont' => 'yes',
                'rel' => 'yes',
                'settle_count' => 99,
                'settle_uuid' => $settleUuid,
                'rebill_param_id' => $rebillParamId,
                'test_plan_id' => $planId,
                'test_credits' => $effectiveCredits,
                'test_signature' => subscription_receive_plan_test_signature(
                    $settleUuid,
                    $user['id'],
                    $planId,
                    $monthlyAmount,
                    $effectiveCredits
                ),
                'test_browser_continue' => 1,
            ]);
            header('Location: receive.php?' . $testParams, true, 302);
            exit;
        }

        $telecom = subscription_config();
        $telecomOrderUrl = (string)(
            $telecom['telecom_cont_url']
            ?? $telecom['telecom_order_url']
            ?? 'https://secure.telecomcredit.co.jp/inetcredit/secure/order.pl'
        );

        // テレコムへブラウザPOSTする直前の実URLと識別情報を追跡ログへ残します。
        // sendpassは意図的に記録しません。
        subscription_trace_log('telecom_' . $memberId, 'telecom_browser_post_ready', [
            'url' => $telecomOrderUrl,
            'return_url' => (string)$telecom['receive_url'],
            'clientip' => (string)$telecom['clientip'],
            'sendid' => $memberId,
            'option' => (string)$user['id'],
            'money' => $monthlyAmount,
            'rebill_param_id' => $rebillParamId,
            'navi_mode' => $mode,
        ]);
?>
        <!doctype html>
        <html lang="ja">
        <meta charset="UTF-8">
        <title>テレコム決済へ移動</title>

        <body onload="document.getElementById('telecom-form').submit()">
            <p>クレジット決済画面へ移動しています。</p>
            <form id="telecom-form" action="<?= htmlspecialchars($telecomOrderUrl, ENT_QUOTES) ?>" method="post" target="_top">
                <input type="hidden" name="clientip" value="<?= htmlspecialchars($telecom['clientip'], ENT_QUOTES) ?>">
                <input type="hidden" name="money" value="<?= $monthlyAmount ?>">
                <input type="hidden" name="rebill_param_id" value="<?= htmlspecialchars($rebillParamId, ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="sendid" value="<?= htmlspecialchars($memberId, ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="sendpass" value="<?= htmlspecialchars($telecomPassword, ENT_QUOTES) ?>">
                <input type="hidden" name="send_pass_bool" value="yes">
                <input type="hidden" name="usrtel" value="<?= htmlspecialchars($telecom['usrtel'], ENT_QUOTES) ?>">
                <input type="hidden" name="usrmail" value="<?= htmlspecialchars($user['email'], ENT_QUOTES) ?>">
                <input type="hidden" name="option" value="<?= htmlspecialchars($user['id'], ENT_QUOTES) ?>">
                <input type="hidden" name="user_id_param" value="<?= htmlspecialchars($user['id'], ENT_QUOTES) ?>">
                <input type="hidden" name="redirect_url" value="<?= htmlspecialchars($telecom['receive_url'], ENT_QUOTES) ?>">
                <noscript><button type="submit">クレジット決済画面へ</button></noscript>
            </form>
        </body>

        </html>
<?php
        exit;
    }
} catch (Throwable $e) {
    http_response_code(400);
    echo '<meta charset="UTF-8"><h2>定額申込みを開始できませんでした。</h2><p>'
        . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>月額クレジットのお申込み</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-image: url('header.jpg');
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

        .credit_options {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
            margin: 20px 0 30px;
        }

        .credit_option {
            position: relative;
            display: block;
            border: 2px solid #d9d9d9;
            border-radius: 12px;
            padding: 22px;
            cursor: pointer;
            background: #fff;
            transition: border-color .15s, box-shadow .15s, background-color .15s;
        }

        .credit_option:hover {
            border-color: #7aabdf;
            box-shadow: 0 3px 12px rgba(0, 123, 255, 0.12);
        }

        .credit_option.selected {
            border-color: #007bff;
            background-color: #f4f9ff;
            box-shadow: 0 3px 14px rgba(0, 123, 255, 0.18);
        }

        .credit_option input {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 20px;
            height: 20px;
        }

        .credit_count {
            display: block;
            padding-right: 30px;
            font-size: 1.45rem;
            font-weight: bold;
        }

        .monthly_price {
            display: block;
            margin-top: 12px;
            font-size: 1.15rem;
            color: #444;
        }

        .notice {
            background-color: #fff8dc;
            border: 1px solid #e6ce75;
            padding: 15px;
            margin: 20px 0;
            line-height: 1.7;
        }

        .agreement {
            margin: 20px 0;
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

        @media (max-width: 700px) {
            header {
                height: 140px;
            }

            header h1 {
                font-size: 2rem;
            }

            .content {
                margin: 10px auto;
                padding: 12px;
            }

            .credit_options {
                grid-template-columns: 1fr;
            }

            #footer {
                min-width: 0;
            }
        }
    </style>
</head>

<body>
    <header>
        <h1>月額クレジットのお申込み</h1>
    </header>

    <div class="content">
        <h2>月々のクレジット数</h2>

        <form method="post" target="_top">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token, ENT_QUOTES) ?>">
            <input type="hidden" name="callback" value="<?= htmlspecialchars($callback, ENT_QUOTES) ?>">
            <input type="hidden" name="navi_mode" value="<?= htmlspecialchars($mode, ENT_QUOTES) ?>">
            <?php if ($test): ?><input type="hidden" name="pass" value="heart"><?php endif; ?>

            <div class="credit_options">
                <?php foreach ($plans as $index => $plan): ?>
                    <?php $isTelecomTest = ((string)$plan['plan_code'] === '1DAY_6000_TEST'); ?>
                    <label class="credit_option">
                        <input
                            type="radio"
                            name="plan_id"
                            value="<?= (int)$plan['id'] ?>"
                            data-price="<?= (int)$plan['monthly_amount'] ?>"
                            data-credits="<?= (int)$plan['credits_per_cycle'] ?>"
                            data-cycle="<?= $isTelecomTest ? 'day' : 'month' ?>"
                            <?= $index === 0 ? 'required' : '' ?>>
                        <?php if ($isTelecomTest): ?>
                            <span class="credit_count">テレコムテスト（1日）</span>
                            <span class="monthly_price"><?= number_format((int)$plan['monthly_amount']) ?>円／日</span>
                        <?php else: ?>
                            <span class="credit_count"><?= number_format((int)$plan['credits_per_cycle']) ?>クレジット／月</span>
                            <span class="monthly_price"><?= number_format((int)$plan['monthly_amount']) ?>円／月</span>
                        <?php endif; ?>
                    </label>
                <?php endforeach; ?>
            </div>

            <div class="notice">
                <span id="selected_payment">選択した月額料金が本日決済され、クレジットが付与されます。</span><br>
                <span id="next_payment">次回は申込日の1か月後に同額が決済され、以後毎月同日に自動更新されます。</span><br>
                解約する場合は、次回課金日の前日までにNAVIから解約手続きを行ってください。
            </div>

            <div class="agreement">
                <label>
                    <input type="checkbox" name="agreement" value="1" required>
                    月額料金と毎月の自動決済について確認しました
                </label>
            </div>

            <div class="submit">
                <button type="submit">この内容で申し込む</button>
                <?php if ($test && $mode === 'stg' && $user['id'] === '99999'): ?>
                    <button type="submit" name="test_skip_payment" value="1"
                        style="margin-left:12px;background:#6c757d">
                        カード決済なしでNAVI疎通テスト
                    </button>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div id="footer">
        &copy; 2024 navi. All Rights Reserved.
    </div>
    <script>
        (function() {
            var radios = document.querySelectorAll('input[name="plan_id"]');
            var selectedPayment = document.getElementById('selected_payment');
            var nextPayment = document.getElementById('next_payment');

            function updateSelection(radio) {
                var options = document.querySelectorAll('.credit_option');
                var i;
                for (i = 0; i < options.length; i++) {
                    options[i].classList.remove('selected');
                }
                radio.parentNode.classList.add('selected');

                var price = Number(radio.getAttribute('data-price')).toLocaleString('ja-JP');
                var credits = Number(radio.getAttribute('data-credits')).toLocaleString('ja-JP');
                var cycle = radio.getAttribute('data-cycle');
                if (cycle === 'day') {
                    selectedPayment.textContent = 'テレコム接続テストとして本日' + price + '円が決済されます。';
                    nextPayment.textContent = '次回は申込日の1日後に' + price + '円が決済され、以後毎日自動更新されます。';
                    return;
                }
                selectedPayment.textContent = '本日' + price + '円が決済され、' + credits + 'クレジットが付与されます。';
                nextPayment.textContent = '次回は申込日の1か月後に' + price + '円が決済され、以後毎月同日に自動更新されます。';
            }

            for (var i = 0; i < radios.length; i++) {
                radios[i].addEventListener('change', function() {
                    updateSelection(this);
                });
            }
        }());
    </script>
</body>

</html>