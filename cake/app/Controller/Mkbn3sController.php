<?php
App::uses('AppController', 'Controller');
/**
 * Mkbn3s Controller
 *
 * @property Mkbn3 $Mkbn3
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class Mkbn3sController extends AppController {

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
		$this->Mkbn3->recursive = 0;
		$this->set('mkbn3s', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Mkbn3->exists($id)) {
			throw new NotFoundException(__('Invalid mkbn3'));
		}
		$options = array('conditions' => array('Mkbn3.' . $this->Mkbn3->primaryKey => $id));
		$this->set('mkbn3', $this->Mkbn3->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Mkbn3->create();
			if ($this->Mkbn3->save($this->request->data)) {
				$this->Flash->success(__('The mkbn3 has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The mkbn3 could not be saved. Please, try again.'));
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
		if (!$this->Mkbn3->exists($id)) {
			throw new NotFoundException(__('Invalid mkbn3'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Mkbn3->save($this->request->data)) {
				$this->Flash->success(__('The mkbn3 has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The mkbn3 could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Mkbn3.' . $this->Mkbn3->primaryKey => $id));
			$this->request->data = $this->Mkbn3->find('first', $options);
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
		$this->Mkbn3->id = $id;
		if (!$this->Mkbn3->exists()) {
			throw new NotFoundException(__('Invalid mkbn3'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Mkbn3->delete()) {
			$this->Flash->success(__('The mkbn3 has been deleted.'));
		} else {
			$this->Flash->error(__('The mkbn3 could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
