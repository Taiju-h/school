<?php
App::uses('AppModel', 'Model');

class NaviUser extends AppModel
{
    public $useTable = 'navi_user';
    public $primaryKey = 'id';

    /**
     * クレジットの上書きリセット（管理用）
     */
    public function resetCreditBalance($userId, $amount = 50)
    {
        $this->id = $userId;
        if (!$this->exists()) {
            return false;
        }
        return $this->saveField('credit_balance', $amount);
    }

    /**
     * 課金ステータスの変更（管理用）
     */
    public function updateSubscriptionStatus($userId, $status)
    {
        $validStatuses = array('active', 'suspended', 'inactive');
        if (!in_array($status, $validStatuses)) {
            return false;
        }

        $this->id = $userId;
        if (!$this->exists()) {
            return false;
        }
        return $this->saveField('subscription_status', $status);
    }

    /**
     * テレコムIDの手動紐付け・修正（管理用）
     */
    public function linkTelecomId($userId, $rebillId = null)
    {
        $this->id = $userId;
        if (!$this->exists()) {
            return false;
        }

        $data = array(
            'NaviUser' => array(
                'id' => $userId,
                'telecom_rebill_id' => $rebillId
            )
        );
        // ID紐付け時、もし本日を基準日にセットする場合は以下を有効化
        if (!empty($rebillId)) {
            $data['NaviUser']['billing_anchor_day'] = (int)date('d');
        }

        return $this->save($data, array('validate' => false));
    }
}
