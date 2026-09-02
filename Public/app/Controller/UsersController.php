<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController {

	public $components = array('Mail');

 	public $uses = array('User');

	public function beforeFilter(){

		//ログインなしでアクセス可能なページを列挙
		//
		$this->Auth->allow(array("login2","add", "edit", "viewadd", "result", "result2", "passwordlost", "passwordlostend",'password', 'login_taboo'));

	}

	/**
	 * ログイン処理
	 */
	public function login($model = NULL, $act = NULL, $id = NULL) {
//	var_dump($this->Session->Read('model'));exit;
	    if ($this->request->is('post')) {
	        if ($this->Auth->login()) {
				$this->Session->write('user', $this->Auth->user());
			//	if(! is_null($model))
			//		return $this->redirect(array('controller' =>$model, 'action' =>  $act, $id));

			 	//if($this->Session->Check('model'))
				 //	return $this->redirect(array('controller' => $this->Session->Read('model'), 'action' =>  $this->Session->Read('act'), $this->Session->Read('id')));
		//		 if($this->Session->Check('Yoyaku'))
		//		 	return $this->redirect(array('controller' => 'Msubscriptions','action' => 'view3'));

			 	return $this->redirect(array('controller' => 'Msubscriptions','action' => 'view'));
	        } else {
	            $this->Session->setFlash(__('メールアドレスまたはパスワードが違います'));
	        }
	    } else $this->Auth->logout();
		$this->Session->write('PRE_URLA',  'login');

		$this->set('title_for_layout', $this->Session->read('Kname') . "ログインフォーム");
		if($this->Session->read('PRE_URL'))
				$this->layout = 'tsuhan';
		else	$this->layout = 'webout';

	}

	/**
	 * ログイン処理
	 */
	public function login_taboo() {
//	var_dump($this->Session->Read('model'));exit;
	    if ($this->request->is('post')) {
	        if ($this->Auth->login()) {
				$this->Session->write('user', $this->Auth->user());
			//	if(! is_null($model))
			//		return $this->redirect(array('controller' =>$model, 'action' =>  $act, $id));

			 	//if($this->Session->Check('model'))
				 //	return $this->redirect(array('controller' => $this->Session->Read('model'), 'action' =>  $this->Session->Read('act'), $this->Session->Read('id')));
				 if(!$this->Auth->user('taboo_flg')) {
		           return $this->Session->setFlash(__('禁断の書を読む権限がありません。'));
			 	}
				return $this->redirect(array('controller' => 'Massociations','action' => 'tindex_taboo'));
	        } else {
	            $this->Session->setFlash(__('メールアドレスまたはパスワードが違います。'));
	        }
	    } else $this->Auth->logout();
		$this->set('title_for_layout', "禁断の書入口");
		$this->layout = 'study';

	}

	/**
	 * ログイン処理
	 */
	public function login2($id = NULL) {
//	var_dump($this->Session->Read('model'));exit;
	    if ($this->request->is('post')) {
	        if ($this->Auth->login()) {
				$this->Session->write('user', $this->Auth->user());
				//if(! is_null($model))
				//	return $this->redirect(array('controller' =>'Mfiles', 'action' =>  'oview3', $this->Session->Read('id')));
	           		 $this->Session->setFlash(__('ログインに成功しました。動画の場合はダウンロードを開始いたしております。'));
					return $this->redirect(array('controller' =>'Mfiles', 'action' =>  'oview3', $id));
				//	return $this->redirect(array('controller' =>'Mfiles', 'action' =>  'oview_bk', $id));

			 	//if($this->Session->Check('model'))
				 //	return $this->redirect(array('controller' => $this->Session->Read('model'), 'action' =>  $this->Session->Read('act'), $this->Session->Read('id')));
			 	//return $this->redirect(array('controller' => 'Msubscriptions','action' => 'view'));
	        } else {
	            $this->Session->setFlash(__('メールアドレスまたはパスワードが違います'));
	        }
	    } else $this->Auth->logout();
		$this->set('title_for_layout', $this->Session->read('Kname') . "ログインフォーム");
		if($this->Session->read('PRE_URL'))
				$this->layout = 'tsuhan';
		else	$this->layout = 'webout';

	}

	/**
	 * ログアウト処理
	 */
	public function logout(){
		$this->Auth->logout();
	}

	public function result() {
		//決済金額を書き込む

//		if ($this->User->save($this->request->data)) {
		if($this->Session->check('user.id')) {
			$this->User->id = $this->Session->read('user.id');
			$user = NULL;
			$user['User']['cardflg'] = True;
			$user['User']['yoyakuflg'] = True;
			$this->User->save($user);
		}


	}
		public function result2() {
		//決済金額を書き込む

//		if ($this->User->save($this->request->data)) {
		if($this->Session->check('user.id')) {
			$this->User->id = $this->Session->read('user.id');
			$user = NULL;
			$user['User']['cardflg'] = True;
			$user['User']['yoyakuflg'] = True;
			$this->User->save($user);
		}


	}

/**
 * index method
 *
 * @return void
 */


//index


/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			//if(! preg_match('/^[a-zA-Z0-9]{6,10}$/', $this->request->data["User"]['password']) )
			//	$this->Session->setFlash(__('パスワードは英数字混在でなければいけません。'));
			if (strcmp($this->request->data["User"]['password'], $this->request->data["User"]['re_password']))
					$this->Session->setFlash(__('確認用パスワードと一致していません。'));
			else if (strcmp($this->request->data["User"]['usrmail'], $this->request->data["User"]['re_usrmail']))
					$this->Session->setFlash(__('確認用メールアドレスと一致していません。'));
				else
					$this->User->create();
					if ($this->User->save($this->request->data)) {
						$this->Session->setFlash(__('正常に登録できました。'));
						$this->request->data['User']['id'] = $this->User->getInsertID();
//var_dump($a);
					//	$this->Session->write('oldact', 'add' );
						$this->Session->write('user', $this->request->data['User']);
							if($this->Session->Check('Yoyaku'))
						 		return $this->redirect(array('controller' => 'Msubscriptions','action' => 'view3'));
						 	return $this->redirect(array('controller' => 'Msubscriptions','action' => 'view'));
					} else
						$this->Session->setFlash(__('メッセージに従いやり直してください。'));
		}


		$this->set('title_for_layout', "ユーザ情報登録画面");
		$this->layout = 'yoyakuform';
//		$this->layout = 'webout';

	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit() {
		$this->User->id = $this->Session->read('user.id');
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid User'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if (strcmp($this->request->data["User"]['usrmail'], $this->request->data["User"]['re_usrmail'])) {
				$this->Session->setFlash(__('確認用メールアドレスと一致していません。'));
			} else {
				if ($this->User->save($this->request->data)) {
					$this->Session->setFlash(__('正常に更新されました。'));
					$this->Session->write('user', $this->request->data['User']);
					return $this->redirect(array('controller' => 'Msubscriptions','action' => 'view'));
				} else {
					$this->Session->setFlash(__('更新できませんでした。受付までお問合せください。'));
				}
			}
		}
		$this->request->data = $this->User->read(null, $id);
		$this->set('title_for_layout', "ユーザ情報変更画面");
		$this->layout = 'yoyakuform';
	}

/**
 * delete method
 *
 * @throws MethodNotAllowedException
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {


		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('ユーザが存在しません'));
		}
		if ($this->User->delete()) {
			$this->Session->setFlash(__('削除しました。'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('削除できませんでした。'));
		$this->redirect(array('action' => 'index'));
	}

	public function passwordlost() {

		if($this->request->isPost()){
			$data1 = $this->User->find('first', array('conditions'=> array('User.usrmail' => $this->request->data['User']['usrmail'])));
			if(empty($data1)) {
				$this->Session->setFlash(__('存在しないユーザです', $this->request->data['User']['usrmail']));
			} else {
				//メールを作成する
				$mail_temp = $data1['User']['name']  . "様\n\n";

				$mail_temp.= "ハートフルです。\n";
				$mail_temp.= "パスワード再設定は以下のＵＲＬからアクセスしてください。　\n";
				$mail_temp.= "https://school.heartf.com/Public/Users/password/" . $data1['User']['usrmail'] . "/" . $data1['User']['password'] . "/\n\n";

				$wk = NULL;
				$wk['m_subject'] = $data1['User']['name'] . '様 パスワード再登録のご連絡です。';
				$wk['mail_temp'] =  $mail_temp;
				$this->Session->write('wk', $wk);
				$this->loadModel('Mdivision');
				$data2 = $this->Mdivision->find('first', array('Mdivision.id' => MDVI_ID));

				$send_mail = $this->Mail->send_mail( $data1['User']['usrmail'], $data2['Mdivision']);
				if (!$send_mail)
	   				throw new NotFoundException('メールは送信できませんでした');
		

				$this->redirect(array('controller' => 'Users', 'action' => 'passwordlostend'));
			}
		}


	}
	public function passwordlostend() {


	}

	public function password($email = NULL, $pass = NULL) {
		if(!is_null($email)) {
			$data1 = $this->User->find('first', array('conditions'=> array('User.usrmail' => $email)));

			if($data1['User']['password'] != $pass) {

				$this->Session->setFlash(__('不正なアクセスです。'));

			} else {
				$this->Session->write('user_id',$data1['User']['id']);
			}
		}
		$this->User->id = $this->Session->read('user_id');
		if (!$this->User->exists()) {
			throw new NotFoundException(__('存在しないユーザです'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {

			if(! preg_match('/^[a-zA-Z0-9]{6,10}$/', $this->request->data["User"]['password']) ){
				$this->Session->setFlash(__('パスワードは英数字混在でなければいけません。'));
			}

			if($this->request->data['User']['password'] == '') {
				$this->Session->setFlash(__('未入力ですやり直してください。'));
			} else {
				if($this->request->data['User']['password'] == $this->request->data['User']['password1']) {
					if ($this->User->save($this->request->data)) {
						$this->Session->setFlash(__('パスワードを設定しました'));

						$this->redirect(array('action' => $this->Session->read('PRE_URLA')));
					} else {
						$this->Session->setFlash(__('設定できませんでした。やり直してください。'));
					}
				} else {
					$this->Session->setFlash(__('一致しませんでした。やり直してください。'));
				}
			}
			//$this->request->data = $this->User->read(null, $this->Session->read('user_id'));
		}
	}


}
