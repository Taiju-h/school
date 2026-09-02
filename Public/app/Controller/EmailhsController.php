<?php
App::uses('AppController', 'Controller');

/**
 * Emailhs Controller
 *
 * @property Emailh $Emailh
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class EmailhsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Mail');
	public function beforeFilter(){
			
		//ログインなしでアクセス可能なページを列挙
		//
		$this->Auth->allow(); 
	}
		public function afterFilter(){
			
//var_dump($this->action);
		if($this->action == 'send_end') 
			$this->Session->destroy();
	}


/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view() {
		if (!$this->Emailh->exists( $this->Session->read('Eid'))) {
			throw new NotFoundException(__('Invalid emailh'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if(isset($this->request->data['edit'])) 
				return $this->redirect(array('action' => 'edit'));
			$this->Emailh->id = $this->Session->read('Eid');
			if(isset($this->request->data['send'])) 
				$this->request->data['Emailh']['mpaymentmethod_id'] = 3;
			if(isset($this->request->data['card'])) 
				$this->request->data['Emailh']['mpaymentmethod_id'] = 2;
			if(isset($this->request->data['bank'])) 
				$this->request->data['Emailh']['mpaymentmethod_id'] = 1;
			if ($this->Emailh->save($this->request->data)) {
				if(isset($this->request->data['send'])) 
					return $this->redirect(array('action' => 'send'));
				if(isset($this->request->data['card'])) {
					$this->Session->write('mpaymentmethod_id',2);
					return $this->redirect(array('controller' => 'users','action' => 'card'));
				}
				if(isset($this->request->data['bank'])) {
					$this->Session->write('mpaymentmethod_id',1);
					return $this->redirect(array('action' => 'send'));
				}
		 	}
		}
		$this->set('title_for_layout', $this->Session->read('Kname') . "予約確認画面");
		$this->layout = 'yoyakuform';
		$options = array('conditions' => array('Emailh.' . $this->Emailh->primaryKey =>  $this->Session->read('Eid')));
		$this->set('emailh', $this->Emailh->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function yoyakuform($mclassification_id = NULL) {
		if ($this->request->is(array('post', 'put'))) {
			$wkmsg = NULL;
			if(!$this->Session->check('user')) {
				if($this->request->data['Emailh']['name'] == "") $wkmsg.="お名前、";
				if($this->request->data['Emailh']['furigana'] == "") $wkmsg.="ふりがな、";
				if($this->request->data['Emailh']['tel'] == "") $wkmsg.="電話番号、";
				if($this->request->data['Emailh']['email'] == "") $wkmsg.="メールアドレス、";
				
			} else { 
				$this->request->data['Emailh']['muser_id'] = $this->Session->read('user.id');
				$this->request->data['Emailh']['name'] = $this->Session->read('user.name');
				$this->request->data['Emailh']['furigana'] = $this->Session->read('user.furigana');
				$this->request->data['Emailh']['tel'] = $this->Session->read('user.usrtel');
				$this->request->data['Emailh']['email'] = $this->Session->read('user.usrmail');
			}
			if(is_null($wkmsg)) {
				$this->Emailh->create();
				$this->request->data['Emailh']['mkanteishi_id'] = $this->Session->read('Kid');
				$this->request->data['Emailh']['mclassification_id'] = $this->Session->read('mclassification_id');
				$this->request->data['Emailh']['mdivision_id'] = MDVI_ID;
				$this->request->data['Emailh']['mtanka_id'] = $this->Session->Read('mtankatel_id');
				if($mclassification_id == 2)
					$this->request->data['Emailh']['maebaraikng'] = $this->Session->Read('mtankatel_id') * $this->request->data['Emailh']['mticket_id'];
				else if($mclassification_id == 3)
					$this->request->data['Emailh']['maebaraikng'] =  $this->Session->Read('mtankamail_id');
				else $this->request->data['Emailh']['maebaraikng'] = 0;
				
				if ($this->Emailh->save($this->request->data)) {
					$this->Session->write('Eid', $this->Emailh->getInsertID());
					if(isset($this->request->data['Emailh']['mticket_id']))
						$this->Session->write('mticket_id', $this->request->data['Emailh']['mticket_id']);

					return $this->redirect(array('action' => 'view'));
				} else 
					$this->Session->setFlash(__('画面の指示に従い修正してください。'));
				
			} else $this->Session->setFlash(__($wkmsg .'は必須入力項目です。'));
		}
		$this->Session->write('mclassification_id', $mclassification_id);
		$sql = 'SELECT Mclassification.name FROM mclassifications as Mclassification WHERE Mclassification.id = ' . $this->Session->read('mclassification_id');
		$mclassifications = $this->Emailh->query($sql);
		$this->Session->write('mclassification_name', $mclassifications[0]['Mclassification']['name']);
		$this->set('title_for_layout', $this->Session->read('Kname') . "先生の予約画面");
		$this->layout = 'yoyakuform';
		
		$this->set('user', $this->Session->read('user'));
		$mtenpos = $this->Emailh->Mtenpo->find('list', array('conditions' => array('Mtenpo.chokuei =' => 1)));
		$mmenus = $this->Emailh->Mmenu->find('list', array('conditions' => array('Mmenu.mkanteishi_id =' => $this->Session->read('Kid')), 'order' => 'Mmenu.seq'));
		$mquestionnaires = $this->Emailh->Mquestionnaire->find('list');
		if($mclassification_id != 3) {
			if(($mclassification_id == 2) and ( $this->Session->read('isisflg'))) {
				$mtimes = $this->Emailh->Mtime->find('list', array('order' => 'Mtime.id'));
				$mtime2s = $this->Emailh->Mtime2->find('list', array('order' => 'Mtime2.id'));
			} else {
				$mtimes = $this->Emailh->Mtime->find('list', array('conditions' => array('Mtime.chokueiflg =' => 1), 'order' => 'Mtime.id'));
				$mtime2s = $this->Emailh->Mtime2->find('list', array('conditions' => array('Mtime2.chokueiflg =' => 1), 'order' => 'Mtime2.id'));
			}
		
			$mtickets = $this->Emailh->Mticket->find('list',array('conditions' => array('Mticket.taimen =' => 1)));
			$this->set(compact('mtenpos', 'mtimes', 'mtime2s', 'mtickets', 'mmenus', 'mquestionnaires'));
		} else $this->set(compact('mtenpos', 'mmenus', 'mquestionnaires'));
		
	}

	
/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit() {

		$this->Emailh->id = $this->Session->read('Eid');
		if (!$this->Emailh->exists($this->Session->read('Eid'))) {
			throw new NotFoundException(__('Invalid emailh'));
		}
		if ($this->request->is(array('post', 'put'))) {
			$wkmsg = NULL;
			if(!$this->Session->check('user')) {
				if($this->request->data['Emailh']['name'] == "") $wkmsg.="お名前、";
				if($this->request->data['Emailh']['furigana'] == "") $wkmsg.="ふりがな、";
				if($this->request->data['Emailh']['tel'] == "") $wkmsg.="電話番号、";
				if($this->request->data['Emailh']['email'] == "") $wkmsg.="メールアドレス、";
				
			}  
			if(is_null($wkmsg)) {
				if($mclassification_id == 2)
					$this->request->data['Emailh']['maebaraikng'] = $this->Session->Read('mtankatel_id') * $this->request->data['Emailh']['mticket_id'];
				else if($mclassification_id == 3)
					$this->request->data['Emailh']['maebaraikng'] =  $this->Session->Read('mtankamail_id');
				else $this->request->data['Emailh']['maebaraikng'] = 0;
				if ($this->Emailh->save($this->request->data)) {
					if(isset($this->request->data['Emailh']['mticket_id']))
						$this->Session->write('mticket_id', $this->request->data['Emailh']['mticket_id']);
					return $this->redirect(array('action' => 'view'));
				} else {
					$this->Session->setFlash(__('画面の指示に従い修正してください。'));
				}
			} else $this->Session->setFlash(__($wkmsg .'は必須入力項目です。'));
		}
		
		$options = array('conditions' => array('Emailh.' . $this->Emailh->primaryKey => $this->Session->read('Eid')));
		$this->request->data = $this->Emailh->find('first', $options);

		$wk = "占い師 " . $this->Session->read('Kname') . "先生の予約画面";
		$this->set('title_for_layout', $wk);
		$this->layout = 'kanteishiview';
		$this->set('user', $this->Session->read('user'));
		$mtenpos = $this->Emailh->Mtenpo->find('list', array('conditions' => array('Mtenpo.chokuei =' => 1)));
		$mmenu = $this->Emailh->Mmenu->find('list', array('conditions' => array('Mmenu.mkanteishi_id =' => $this->Session->read('Kid')), 'order' => 'Mmenu.seq'));
		$mquestionnaires = $this->Emailh->Mquestionnaire->find('list');
		if($this->Session->read('mclassification_id') != 3) {
			if(($this->Session->read('mclassification_id') == 2) and ( $this->Session->read('isisflg'))) {
				$mtimes = $this->Emailh->Mtime->find('list', array('order' => 'Mtime.id'));
				$mtime2s = $this->Emailh->Mtime2->find('list', array('order' => 'Mtime2.id'));
			} else {
				$mtimes = $this->Emailh->Mtime->find('list', array('conditions' => array('Mtime.chokueiflg =' => 1), 'order' => 'Mtime.id'));
				$mtime2s = $this->Emailh->Mtime2->find('list', array('conditions' => array('Mtime2.chokueiflg =' => 1), 'order' => 'Mtime2.id'));
			}
		
			$mtickets = $this->Emailh->Mticket->find('list',array('conditions' => array('Mticket.taimen =' => 1)));
			$this->set(compact('mtenpos', 'mtimes', 'mtime2s', 'mtickets', 'mmenus', 'mquestionnaires'));
		} else $this->set(compact('mtenpos', 'mmenus', 'mquestionnaires'));
		
	}


	public function send() {
		$mail_temp = NULL;
		if($this->Session->check('Eid')) {
			if($this->Session->read('mpaymentmethod_id') == 2) $mworkst_id = 30;
			else $mworkst_id = 20;
			//登録する値
			$data = array('Emailh' => array('id' =>  $this->Session->read('Eid'), 'mworkst_id' => $mworkst_id));
			// 登録するフィールド
			$fields = array('mworkst_id');
			$this->Emailh->save($data, false, $fields);

			$data = $this->Emailh->find('first', 
				array('conditions'=> array('Emailh.id' => $this->Session->read('Eid'))));
			$this->Session->write('Ename',  $data['Emailh']['name']);
			
			$this->loadModel('Mdivision');
			$data1 = $this->Mdivision->find('first', array('Mdivision.id' => MDVI_ID));  
			$mail_temp1 =  $this->Mail->Reception($data1['Mdivision']);
	
			if($this->Session->read('mpaymentmethod_id') == 1)
				$mail_temp1 .=  $this->Mail->Reception_bank($data, $data1['Mdivision']);
			if($this->Session->read('mpaymentmethod_id') == 2) {
				$date = new DateTime();
				//登録する値
				$data1 = array('Emailh' => array('id ' =>  $this->Session->read('Eid'), 'renyukindate' => $date->format('Y-m-d H:i:s')));
				// 登録するフィールド
				$fields = array('renyukindate');
				$this->Emailh->save($data1, false, $fields);
			}
	
			$mail_temp .=  $this->Mail->Contact_content($data, $this->Session->read('mclassification_name'), $this->Session->read('mclassification_id'));
			$wk = NULL;
			$wk['m_subject'] = $data['Emailh']['name']. '様 ご予約ありがとうございます。';
;
			$wk['mail_temp'] =  $mail_temp;
			$this->Session->write('wk', $wk);
			
			
			$sql = 'SELECT email  FROM mprivacy WHERE mkanteishi_id = ' . $this->Session->read('Kid');
			$dataM = $this->Emailh->query($sql);
		
			//先生
			$wk = NULL;
			$wk['m_subject'] = $data['Emailh']['name']. '様 ご連絡ありがとうございます。';

			$wk['mail_temp'] =  "Webサイトから予約のご連絡がありました。\n管理側から１２時間以内に次のメールが来ない場合はご連絡ください。\nまた下記をご確認の上先生のほうでご要望の有る場合には１時間内にご返信をください。ない場合は第1か第2でお取りいたします。\n\n【注意】決済を終了いたしましたら受付からご連絡をいたしますのでそれまでお待ちください。【注意】\n" . $mail_temp;
			$this->Session->write('wk', $wk);
			$this->Mail->send_mail($dataM['0']['mprivacy']['email'], $data1['Mdivision']);

			//お客様
			$wk = NULL;
			$wk['m_subject'] = $data['Emailh']['name']. '様 ご連絡ありがとうございます。';
;
			$wk['mail_temp'] = $mail_temp1 .  $mail_temp;
			$this->Session->write('wk', $wk);
			$this->Mail->send_mail($data['Emailh']['email'], $data1['Mdivision']);
		//	$this->Mail->send_mail($data1['0']['mprivacy']['email']);

			//会社用
			$this->Mail->send_mail('uranai@heartf.com');
			$this->Session->delete('Eid');

			$this->redirect(array('action' => 'send_end'));

		} else $this->Session->setFlash(__('正しい手順ではありませんでした。鑑定士のご紹介からやり直してください。'));
	}
	
	public function send_end() {
		$this->layout = 'webout';

	
	}


}
