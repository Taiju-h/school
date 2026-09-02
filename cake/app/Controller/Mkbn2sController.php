<?php
App::uses('AppController', 'Controller');
/**
 * Mkbn2s Controller
 *
 * @property Mkbn2 $Mkbn2
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class Mkbn2sController extends AppController {

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
		$this->Mkbn2->recursive = 0;
		$this->set('mkbn2s', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Mkbn2->exists($id)) {
			throw new NotFoundException(__('Invalid mkbn2'));
		}
		$options = array('conditions' => array('Mkbn2.' . $this->Mkbn2->primaryKey => $id));
		$this->set('mkbn2', $this->Mkbn2->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Mkbn2->create();
			if ($this->Mkbn2->save($this->request->data)) {
				$this->Flash->success(__('The mkbn2 has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The mkbn2 could not be saved. Please, try again.'));
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
		if (!$this->Mkbn2->exists($id)) {
			throw new NotFoundException(__('Invalid mkbn2'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Mkbn2->save($this->request->data)) {
				$this->Flash->success(__('The mkbn2 has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The mkbn2 could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Mkbn2.' . $this->Mkbn2->primaryKey => $id));
			$this->request->data = $this->Mkbn2->find('first', $options);
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
		$this->Mkbn2->id = $id;
		if (!$this->Mkbn2->exists()) {
			throw new NotFoundException(__('Invalid mkbn2'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Mkbn2->delete()) {
			$this->Flash->success(__('The mkbn2 has been deleted.'));
		} else {
			$this->Flash->error(__('The mkbn2 could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
