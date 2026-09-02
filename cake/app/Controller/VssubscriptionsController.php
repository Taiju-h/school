<?php
App::uses('AppController', 'Controller');
/**
 * Msubscriptions Controller
 *
 * @property Msubscription $Msubscription
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class VssubscriptionsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Flash', 'Session', 'Mail');

/**
 * index method
 *
 * @return void
 */
	public function index() {

		$this->Vssubscription->recursive = 0;
	     $this->Paginator->settings = array (
			'sort' => 'Vssubscription.mworkst_id',
			'direction' => 'ASC'    );
		$this->set('vssubscriptions', $this->Paginator->paginate());
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
 	public function edit1($id = null, $st = NULL) {
		$sql = "Update msubscriptions SET mworkst_id = " ;
		if($st == 40)	 $sql .=  $st .",  date1 = now()";
		if($st == 41)	 $sql .= " 40, mpaymentmethod_id = 7";
		$sql .= " ,  modified = now() WHERE firstid = " . $id;
		if($st == 40) $this->send($id);
		$data = $this->Vssubscription->query($sql);

		$this->Flash->success(__('更新しました。'));
		return $this->redirect(array('action' => 'index'));


	}
/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$sql = "DELETE FROM msubscriptions WHERE firstid = " . $id;
		$data = $this->Vssubscription->query($sql);
		return $this->redirect(array('action' => 'index'));
	}

	public function send($id) {
		$sql = "SELECT Vssubscription.mryoukin_id,Vssubscription.kng, Muser.name, Muser.usrmail FROM vssubscriptions AS Vssubscription INNER JOIN musers AS Muser ON Vssubscription.muser_id = Muser.id WHERE Vssubscription.id = " . $id ;
		$data1 = $this->Vssubscription->query($sql);
//		$data1 = $this->Vssubscription->find('first', array('conditions'=> array('Vssubscription.id' => $id)));
//var_dump($data1, $sql);exit;
		//メールを作成する
		$mail_temp = $data1[0]['Muser']['name']  . "様\n\n";

		$mail_temp.= "講座料金(" . h(number_format($data1[0]['Vssubscription']['kng'] * 1.1)) . "円)のお支払い、ありがとうございました。\n";
		$mail_temp.= "指定口座への入金確認ができましたので、お申込みを確定させていただきます。\n\n";
		$mail_temp.= "尚、キャンセルに関しては以下をご参照ください。\n";
		$mail_temp.= "https://school.heartf.com/index.php?go=DQR9Sh\n\n";
//		$mail_temp.= "後程、詳細をご連絡いたします。\n";
//		$mail_temp.= "取り急ぎ入金確認のご連絡まで。\n";
//通信講座以外の場合
		if($data1[0]['Vssubscription']['mryoukin_id'] != 9)
			$mail_temp.= "開催日にお会いできることを楽しみにしております。\n";

		$wk = NULL;
		$wk['m_subject'] = $data1[0]['Muser']['name'] . '様 ハートフルスクールです。';

		$wk['mail_temp'] =  $mail_temp;
		$this->Session->write('wk', $wk);
		$this->loadModel('Mdivision');
		$data = $this->Mdivision->find('first', array('Mdivision.id' => MDVI_ID));

		$this->Mail->send_mail($data1[0]['Muser']['usrmail'], $data['Mdivision']);
		}



}
