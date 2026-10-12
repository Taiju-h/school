<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\EventInterface;
use Cake\I18n\FrozenTime;
use Cake\Log\Log;

class WebhookController extends AppController
{
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        // CSRFトークンチェックを無効化（テレコムからの外部POSTを受け付けるため）
        $this->Security->setConfig('unlockedActions', ['telecomCreditRecieve']);

        // Viewのレンダリングを無効化し、厳密にテキストのみを出力する設定
        $this->autoRender = false;
    }

    /**
     * テレコムクレジットからの継続決済データ受信エンドポイント (CGI通知受け取り)
     */
    public function telecomCreditRecieve()
    {
        // 1. IPアドレスの検証（セキュリティ制約）
        $allowedIps = [
            '54.65.177.67',
            '52.196.8.0',
            '54.238.8.174',
            '172.104.99.119',
            '54.95.89.20'
        ];
        $clientIp = $this->request->clientIp();

        if (!in_array($clientIp, $allowedIps)) {
            Log::error("不正なIPからのアクセス: {$clientIp}");
            // エラーを返すとテレコムが再送ループに入るため、SuccessOKで強制終了させる
            return $this->response->withStringBody('SuccessOK');
        }

        // 2. POSTデータの取得
        $postData = $this->request->getData();
        $rebillId = $postData['relcode'] ?? null;      // 継続決済ID
        $transactionId = $postData['transcode'] ?? null; // トランザクションID

        // ★ 新規追加: 入口(index.php)から引き継いだユーザー特定のカギを受け取る
        $userIdParam = $postData['user_id_param'] ?? null;
        $userMail    = $postData['usrmail'] ?? null; // 保険：user_id_paramが消された時用

        // ログ出力（デバッグ用・本番稼働後は消してもOKです）
        Log::info("【Webhook受信】", $postData);

        if (!$rebillId) {
            Log::error("継続決済ID(relcode)が不足しています", $postData);
            return $this->response->withStringBody('SuccessOK');
        }

        // 3. 冪等性（Idempotency）の担保：二重処理防止
        $this->loadModel('Transactions');
        $exists = $this->Transactions->exists(['transaction_id' => $transactionId]);
        if ($exists) {
            Log::info("重複リクエストをスキップしました: {$transactionId}");
            return $this->response->withStringBody('SuccessOK');
        }

        // 4. ユーザー特定 ＆ DBの更新処理
        $this->loadModel('Users'); // ※もしモデル名が NaviUsers 等であれば書き換えてください
        $user = null;

        // 【検索ルート1】入口で仕込んだ user_id_param で探す（初回決済時の大本命）
        if ($userIdParam) {
            $user = $this->Users->find()->where(['user_id' => $userIdParam])->first();
        }

        // 【検索ルート2】user_id_paramが弾かれた場合、メアドで探す（初回決済時の保険）
        if (!$user && $userMail) {
            // ※ DBのメアドカラム名が 'email' でない場合は適宜変更してください
            // $user = $this->Users->find()->where(['email' => $userMail])->first();
        }

        // 【検索ルート3】IDもメアドもない場合、リビルIDで探す（2回目以降の自動継続時）
        if (!$user) {
            $user = $this->Users->find()->where(['telecom_rebill_id' => $rebillId])->first();
        }

        // ユーザーが見つかった場合の処理
        if ($user) {

            // ★超重要！【初回登録】まだリビルIDが金庫(DB)にない場合は、ここで保存！
            if (empty($user->telecom_rebill_id)) {
                $user->telecom_rebill_id = $rebillId;
                Log::info("【初回登録成功】ユーザーID: {$user->id} に新しいリビルID ({$rebillId}) を紐付けました。");
            }

            // クレジットの完全リセットロジック（加算不可、上書き）
            $user->credit_balance = 50;

            // ステータスをActiveに更新
            $user->subscription_status = 'active';

            // DBへ保存実行
            if ($this->Users->save($user)) {
                Log::info("ユーザーID: {$user->id} のクレジットをリセット・更新しました。");

                // トランザクション履歴を保存（冪等性用）
                $newTx = $this->Transactions->newEmptyEntity();
                $newTx->transaction_id = $transactionId;
                $newTx->user_id = $user->id; // DBのカラム名に合わせてください
                $this->Transactions->save($newTx);
            } else {
                Log::error("DB保存エラー", $user->getErrors());
            }
        } else {
            Log::error("該当するユーザーが見つかりません: UserParam: {$userIdParam}, RebillID: {$rebillId}");
        }

        // 5. 必須レスポンスの出力（厳格な要件）
        // プログラムは必ず 'SuccessOK' という文字列のみを出力する
        return $this->response->withStringBody('SuccessOK');
    }
}
