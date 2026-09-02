<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class UsersController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Flash', 'Session');
	public $uses = array('User');
	
	public function beforeFilter(){
		
		//ログインなしでアクセス可能なページを列挙
		//
		$this->Auth->allow('add', 'login');
	}
	
	 public function login(){
		if($this->request->isPost()){
			$this->Auth->logout();
			if($this->Auth->login()){
				$this->Session->setFlash('ログインしました');
				$this->redirect(array('controller' => 'Massociations', 'action' => 'index'));

			}
		}
	}

	public function Release() {
		$this->Session->write('mkanteishi_id', 0);
$this->redirect(array('controller' =>  $this->Session->read('model'), 'action' =>  $this->Session->read('act')));
	}

	public function logout(){
		$this->Auth->logout();
	}
	
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}

	
	/**
	 * ユーザリスト
	 */
	public function userlist() {
		
		$userData = $this->paginate();
		
		$this->set(compact('userData'));
	}
	
	/**
	 * ユーザ詳細
	 */
	public function view($id) {
		
		//ユーザを捜して見つかったら表示
		//
		$userData = $this->User->findById($id);
		if(empty($userData)) {
			$this->Session->setFlash('ユーザが見つかりませんでした');
			$this->redirect(array('action'=> 'userlist'));
		}
		
		$this->set(compact('userData'));
	}
	
	/**
	 * ユーザ追加
	 */
	public function add() {
		$this->index();
		return $this->edit(null);
	}
	
	/**
	 * ユーザ編集
	 */
	public function edit($id) {
		
		//フォーム入力があった場合には保存処理。そうでない場合は初期値の準備
		//
		if($this->request->is('post') || $this->request->is('put')) {
			
			//edit時にもしパスワードが空白だったら対象外にする
			//
			if($id !== null) {
				if($this->request->data[$this->User->alias]['password'] == '') {
					unset($this->request->data[$this->User->alias]['password']);
				}
			}
			if($this->User->save($this->request->data)) {
				$this->Session->setFlash('ユーザ情報を保存しました');
				$this->redirect(array('action' => 'add'));
			} else {
				$this->Session->setFlash('入力に間違いがあります');
			}
		} else {
			if($id !== null) {
				$this->request->data = $this->User->findById($id);
				unset($this->request->data[$this->User->alias]['password']);
				if(empty($this->request->data)) {
					$this->Session->setFlash('ユーザが見つかりませんでした');
					$this->redirect(array('action'=> 'add'));
				}
			}
		}

		
		//addもeditもedit.ctpを表示する
		//
		$this->render('edit');
	}
	
	/**
	 * ユーザ削除
	 */
	public function delete($id) {
		
		//フォーム入力があった場合に削除処理。そうでない場合は初期値の準備
		//
		if($this->request->is('post') || $this->request->is('put')) {
			
			//削除実行。削除できない場合はエラー
			//
			if ($this->User->delete($id)) {
				$this->Session->setFlash('ユーザを削除しました');
				$this->redirect(array('action'=>'userlist'));
			}
			$this->Session->setFlash('ユーザの削除に失敗しました');
			$this->redirect(array('action' => 'userlist'));
		}
		
		//ユーザを捜し、いなかったらエラー
		//
		$this->request->data = $this->User->findById($id);
		if (empty($this->request->data)) {
			$this->Session->setFlash('ユーザが見つかりませんでした');
			$this->redirect(array('action' => 'userlist'));
		}
	}
	
}
