<?php
App::uses('AppController', 'Controller');
/**
 * Mpaymentmethods Controller
 *
 * @property Mpaymentmethod $Mpaymentmethod
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class MpaymentmethodsController extends AppController {

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
		$this->Mpaymentmethod->recursive = 0;
		$this->set('mpaymentmethods', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Mpaymentmethod->exists($id)) {
			throw new NotFoundException(__('Invalid mpaymentmethod'));
		}
		$options = array('conditions' => array('Mpaymentmethod.' . $this->Mpaymentmethod->primaryKey => $id));
		$this->set('mpaymentmethod', $this->Mpaymentmethod->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Mpaymentmethod->create();
			if ($this->Mpaymentmethod->save($this->request->data)) {
				$this->Flash->success(__('The mpaymentmethod has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The mpaymentmethod could not be saved. Please, try again.'));
			}
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Mpaymentmethod->exists($id)) {
			throw new NotFoundException(__('Invalid mpaymentmethod'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Mpaymentmethod->save($this->request->data)) {
				$this->Flash->success(__('The mpaymentmethod has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The mpaymentmethod could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Mpaymentmethod.' . $this->Mpaymentmethod->primaryKey => $id));
			$this->request->data = $this->Mpaymentmethod->find('first', $options);
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
		$this->Mpaymentmethod->id = $id;
		if (!$this->Mpaymentmethod->exists()) {
			throw new NotFoundException(__('Invalid mpaymentmethod'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Mpaymentmethod->delete()) {
			$this->Flash->success(__('The mpaymentmethod has been deleted.'));
		} else {
			$this->Flash->error(__('The mpaymentmethod could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
