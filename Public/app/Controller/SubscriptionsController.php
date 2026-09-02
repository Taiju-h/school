<?php
App::uses('AppController', 'Controller');
App::uses('ConnectionManager', 'Model');
App::uses('CakeLog', 'Log');

class SubscriptionsController extends AppController
{

    // モデルを自動で読み込まない（直接SQLを書くため）
    public $uses = array();

    /**
     * 外部API（NAVI）からのPOST通信を受け付けるためのセキュリティ解除
     */
    public function beforeFilter()
    {
        parent::beforeFilter();

        // 画面のレンダリングを無効化（API用のJSONを返すため）
        $this->autoRender = false;

        // APIエンドポイントのため、ログイン認証をスキップ
        if (isset($this->Auth)) {
            $this->Auth->allow('index', 'update');
        }

        // 外部からのPOSTを許可するため、CSRF・セキュリティチェックを除外
        if (isset($this->Security)) {
            $this->Security->unlockedActions = array('index', 'update');
            $this->Security->csrfCheck = false;
        }
    }

    // =======================================
    // テレコムクレジットからのWebhook受取口
    // =======================================
    public function index()
    {
        // ※もしこのファイルでテレコム用も受けるならここに記述します。
        // WebhookControllerに分けている場合は、このメソッドは空のままでもOKです。
        echo 'SuccessOK';
    }

    // =======================================
    // NAVIからの定期課金API受取口
    // =======================================
    public function update()
    {
        // JSON形式でレスポンスを返す宣言
        $this->response->type('json');

        // 1. JSONまたはPOSTデータの受け取り
        $raw_input = file_get_contents('php://input');
        $post_data = json_decode($raw_input, true);
        if (empty($post_data)) {
            $post_data = $this->request->data;
        }

        // 2. パラメータの抽出
        $user_id        = isset($post_data['user_id']) ? $post_data['user_id'] : null;
        $action         = isset($post_data['action']) ? $post_data['action'] : null;
        $transaction_id = isset($post_data['transaction_id']) ? $post_data['transaction_id'] : null;
        $timestamp      = isset($post_data['timestamp']) ? $post_data['timestamp'] : null;
        $received_hash  = isset($post_data['hash']) ? $post_data['hash'] : null;

        // 必須チェック
        if (!$user_id || !$action || !$transaction_id || !$timestamp || !$received_hash) {
            $this->response->statusCode(400);
            return json_encode(array('status' => 'error', 'message' => 'Missing required parameters'));
        }

        // 3. 仕様書通りの署名（hash）検証ロジック
        $key = ".#'Q\\6aVHt'O"; // 共通秘密鍵
        $formula = implode(':', array($user_id, $action, $transaction_id, $timestamp, $key));
        $calculated_hash = hash('sha256', $formula);

        if ($received_hash !== $calculated_hash) {
            CakeLog::write('error', "【API認証エラー】ハッシュ不一致。計算: {$calculated_hash}, 受信: {$received_hash}");
            $this->response->statusCode(401);
            return json_encode(array('status' => 'error', 'message' => 'Invalid signature'));
        }

        // ==========================================
        // 4. navi_user テーブルへの登録・更新 ＆ 重複チェック
        // ==========================================
        try {
            // CakePHP標準のDB接続を使用
            $db = ConnectionManager::getDataSource('default');

            // ① まず、要求されたユーザーが事前登録されているか確認
            $user_sql = "SELECT telecom_rebill_id, subscription_status FROM navi_user WHERE user_id = ?";
            $user = $db->fetchAll($user_sql, array($user_id));

            if (empty($user)) {
                CakeLog::write('error', "【APIエラー】事前登録のないユーザー(ID: {$user_id})のリクエストです。");
                return json_encode(array('status' => 'error', 'message' => 'User is not registered'));
            }

            // ② 重複処理（冪等性）の防止チェック
            $dup_sql = "SELECT COUNT(*) as cnt FROM navi_user WHERE last_transaction_id = ?";
            $duplicate = $db->fetchAll($dup_sql, array($transaction_id));

            if ($duplicate[0][0]['cnt'] > 0) {
                CakeLog::write('info', "【API重複検知】transaction_id: {$transaction_id} は処理済みです。");
                // NAVIの再試行を止めるために success を返す
                return json_encode(array('status' => 'success', 'message' => 'Duplicate transaction ignored'));
            }

            // ③ 決済更新処理
            $update_sql = "UPDATE navi_user SET last_transaction_id = ?, updated_at = NOW() WHERE user_id = ?";
            $db->execute($update_sql, array($transaction_id, $user_id));

            CakeLog::write('info', "【API同期成功】user_id: {$user_id} のトランザクションを更新しました。");

            // 成功レスポンスを返す
            return json_encode(array('status' => 'success'));
        } catch (Exception $e) {
            CakeLog::write('error', "【API DBエラー】" . $e->getMessage());
            $this->response->statusCode(500);
            return json_encode(array('status' => 'error', 'message' => 'Database error occurred'));
        }
    }
}
