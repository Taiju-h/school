<?php
App::uses('AppController', 'Controller');
CakeLog::write('debug', 'WebhookController loaded.');

class WebhookController extends AppController
{

    // 使用するモデルを宣言
    public $uses = array('NaviUser', 'Transaction');

    public function beforeFilter()
    {
        parent::beforeFilter();

        // 画面のレンダリングを無効化（テキストのみ返すため）
        $this->autoRender = false;

        // Securityコンポーネントを使っている場合のPOST許可
        if (isset($this->Security)) {
            $this->Security->unlockedActions = array('index');
        }

        // Authコンポーネントでログイン必須になっている場合、外部APIからのアクセスを許可
        if (isset($this->Auth)) {
            $this->Auth->allow('index');
        }
    }

    public function index()
    {
        // 1. IP制限
        $allowedIps = array('54.65.177.67', '52.196.8.0', '54.238.8.174', '54.95.89.20');
        $clientIp = $this->request->clientIp();

        if (!in_array($clientIp, $allowedIps)) {
            CakeLog::write('error', "【決済Webhookエラー】未許可IP: {$clientIp}");
            echo 'SuccessOK'; // Cake2の出力はechoを使用
            return;
        }

        // POSTチェック
        if (!$this->request->is('post')) {
            echo 'SuccessOK';
            return;
        }

        // 3. パラメータ取得 (Cake2は配列アクセス)
        $postData = $this->request->data;
        $rebillId = isset($postData['relcode']) ? $postData['relcode'] : null;
        $transactionId = isset($postData['transcode']) ? $postData['transcode'] : null;
        $userEmail = isset($postData['usrmail']) ? $postData['usrmail'] : null;

        if (empty($rebillId) || empty($transactionId)) {
            CakeLog::write('error', "必須パラメータ不足");
            echo 'SuccessOK';
            return;
        }

        // 4. 冪等性チェック
        $isProcessed = $this->Transaction->hasAny(array('transaction_id' => $transactionId));
        if ($isProcessed) {
            CakeLog::write('debug', "重複スキップ: {$transactionId}");
            echo 'SuccessOK';
            return;
        }

        // 5. ユーザー特定
        $user = $this->NaviUser->find('first', array(
            'conditions' => array('NaviUser.telecom_rebill_id' => $rebillId)
        ));

        // 初回紐付け
        if (empty($user) && !empty($userEmail)) {
            $user = $this->NaviUser->find('first', array(
                'conditions' => array('NaviUser.account' => $userEmail)
            ));

            if (!empty($user)) {
                $user['NaviUser']['telecom_rebill_id'] = $rebillId;
                $user['NaviUser']['billing_anchor_day'] = (int)date('d');
            }
        }

        // 6. DB更新
        if (!empty($user)) {
            $user['NaviUser']['credit_balance'] = 50; // 50で上書き
            $user['NaviUser']['subscription_status'] = 'active';

            // Cake2での保存処理
            if ($this->NaviUser->save($user)) {

                // トランザクション履歴の保存
                $txData = array(
                    'Transaction' => array(
                        'transaction_id' => $transactionId,
                        'user_id' => $user['NaviUser']['id'],
                        'amount' => isset($postData['money']) ? $postData['money'] : 0
                    )
                );
                $this->Transaction->create();
                $this->Transaction->save($txData);

                CakeLog::write('debug', "【Webhook成功】UserID: {$user['NaviUser']['id']}");
            }
        }

        // 7. 必須レスポンス
        echo 'SuccessOK';
        return;
    }
}
