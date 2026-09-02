<?php
App::uses('AppController', 'Controller');
/**
 * Msums Controller
 *
 * @property Msum $Msum
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class MsumsController extends AppController {

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
		$this->Msum->recursive = 0;
		$this->set('msums', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Msum->exists($id)) {
			throw new NotFoundException(__('Invalid msum'));
		}
		$options = array('conditions' => array('Msum.' . $this->Msum->primaryKey => $id));
		$this->set('msum', $this->Msum->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Msum->create();
			if ($this->Msum->save($this->request->data)) {
				$this->Flash->success(__('The msum has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The msum could not be saved. Please, try again.'));
			}
		}
		$mryoukins = $this->Msum->Mryoukin->find('list');
		$mryoukin2s = $this->Msum->Mryoukin2->find('list');
		$this->set(compact('mryoukins', 'mryoukin2s'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Msum->exists($id)) {
			throw new NotFoundException(__('Invalid msum'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Msum->save($this->request->data)) {
				$this->Flash->success(__('The msum has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The msum could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Msum.' . $this->Msum->primaryKey => $id));
			$this->request->data = $this->Msum->find('first', $options);
		}
		$mryoukins = $this->Msum->Mryoukin->find('list');
		$mryoukin2s = $this->Msum->Mryoukin2->find('list');
		$this->set(compact('mryoukins', 'mryoukin2s'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Msum->id = $id;
		if (!$this->Msum->exists()) {
			throw new NotFoundException(__('Invalid msum'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Msum->delete()) {
			$this->Flash->success(__('The msum has been deleted.'));
		} else {
			$this->Flash->error(__('The msum could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
