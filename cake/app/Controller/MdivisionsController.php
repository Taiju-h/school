<?php
App::uses('AppController', 'Controller');
/**
 * Mdivisions Controller
 *
 * @property Mdivision $Mdivision
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class MdivisionsController extends AppController {

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
		$this->Mdivision->recursive = 0;
		$this->set('mdivisions', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Mdivision->exists($id)) {
			throw new NotFoundException(__('Invalid mdivision'));
		}
		$options = array('conditions' => array('Mdivision.' . $this->Mdivision->primaryKey => $id));
		$this->set('mdivision', $this->Mdivision->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Mdivision->create();
			if ($this->Mdivision->save($this->request->data)) {
				$this->Flash->success(__('The mdivision has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The mdivision could not be saved. Please, try again.'));
			}
		}
		$mbanks = $this->Mdivision->Mbank->find('list');
		$this->set(compact('mbanks'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Mdivision->exists($id)) {
			throw new NotFoundException(__('Invalid mdivision'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Mdivision->save($this->request->data)) {
				$this->Flash->success(__('The mdivision has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The mdivision could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Mdivision.' . $this->Mdivision->primaryKey => $id));
			$this->request->data = $this->Mdivision->find('first', $options);
		}
		$mbanks = $this->Mdivision->Mbank->find('list');
		$this->set(compact('mbanks'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Mdivision->id = $id;
		if (!$this->Mdivision->exists()) {
			throw new NotFoundException(__('Invalid mdivision'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Mdivision->delete()) {
			$this->Flash->success(__('The mdivision has been deleted.'));
		} else {
			$this->Flash->error(__('The mdivision could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
