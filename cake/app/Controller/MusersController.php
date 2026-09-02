<?php
App::uses('AppController', 'Controller');
/**
 * Musers Controller
 *
 * @property Muser $Muser
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class MusersController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Flash', 'Session');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Muser->recursive = 0;
		$this->set('musers', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Muser->exists($id)) {
			throw new NotFoundException(__('Invalid muser'));
		}
		$options = array('conditions' => array('Muser.' . $this->Muser->primaryKey => $id));
		$this->set('muser', $this->Muser->find('first', $options));

		$this->loadModel('Msubscription');

		$options = array('conditions' => array('Msubscription.muser_id' => $id));

		$this->set('msubscriptions', $this->Msubscription->find('all', $options));
/*
		$this->paginate=array(
			'page'=>1,
			'conditions'=>array('Msubscription.muser_id'=>$id),
			'sort'=>'id',
			'limit'=>20,
			'recursive'=>0
			);		
		$this->Msubscription->recursive = 0;
		$this->set('msubscriptions', $this->Paginator->paginate());
*/
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Muser->create();
			if ($this->Muser->save($this->request->data)) {
				$this->Flash->success(__('The muser has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The muser could not be saved. Please, try again.'));
			}
		}
		$mdivisions = $this->Muser->Mdivision->find('list');
		$mryoukins = $this->Muser->Mryoukin->find('list');
		$this->set(compact('mdivisions', 'mryoukins'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Muser->exists($id)) {
			throw new NotFoundException(__('Invalid muser'));
		}
		
		if ($this->request->is(array('post', 'put'))) {
			if(empty($this->request->data["User"]['usrmail']))
				unset($this->request->data["User"]['usrmail']);
			else if (strcmp($this->request->data["User"]['usrmail'], $this->request->data["User"]['re_usrmail'])) {
					$this->Session->setFlash(__('確認用メールアドレスと一致していません。'));
					return $this->redirect(array('action' => 'index'));
			}

	
			if ($this->Muser->save($this->request->data)) {
				$this->Flash->success(__('The muser has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The muser could not be saved. Please, try again.'));
			}
	
		}
		$this->layout = 'user';

		$options = array('conditions' => array('Muser.' . $this->Muser->primaryKey => $id));
		$this->request->data = $this->Muser->find('first', $options);
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Muser->id = $id;
		if (!$this->Muser->exists()) {
			throw new NotFoundException(__('Invalid muser'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Muser->delete()) {
			$this->Flash->success(__('The muser has been deleted.'));
		} else {
			$this->Flash->error(__('The muser could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
