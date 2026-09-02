<?php
App::uses('AppController', 'Controller');
/**
 * Mkbn1s Controller
 *
 * @property Mkbn1 $Mkbn1
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class Mkbn1sController extends AppController {

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
		$this->Mkbn1->recursive = 0;
		$this->set('mkbn1s', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Mkbn1->exists($id)) {
			throw new NotFoundException(__('Invalid mkbn1'));
		}
		$options = array('conditions' => array('Mkbn1.' . $this->Mkbn1->primaryKey => $id));
		$this->set('mkbn1', $this->Mkbn1->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Mkbn1->create();
			if ($this->Mkbn1->save($this->request->data)) {
				$this->Flash->success(__('The mkbn1 has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The mkbn1 could not be saved. Please, try again.'));
			}
		}
		$this->index();

	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Mkbn1->save($this->request->data)) {
				$this->Flash->success(__('The mkbn1 has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The mkbn1 could not be saved. Please, try again.'));
			}
		} else if($id !== null) {
				$this->request->data = $this->Mkbn1->findById($id);
				if(empty($this->request->data)) {
					$this->Session->setFlash('見つかりませんでした');
					$this->redirect(array('action'=> 'add'));
			}
		
		
			$options = array('conditions' => array('Mkbn1.' . $this->Mkbn1->primaryKey => $id));
			$this->request->data = $this->Mkbn1->find('first', $options);
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Mkbn1->id = $id;
		if (!$this->Mkbn1->exists()) {
			throw new NotFoundException(__('Invalid mkbn1'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Mkbn1->delete()) {
			$this->Flash->success(__('The mkbn1 has been deleted.'));
		} else {
			$this->Flash->error(__('The mkbn1 could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
