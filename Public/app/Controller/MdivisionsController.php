<?php
App::uses('AppController', 'Controller');
/**
 * Mdivisions Controller
 *
 * @property Mdivision $Mdivision
 */
class MdivisionsController extends AppController {

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Mdivision->recursive = 0;
		if(MDVI_ID == 1) {
			$this->set('mdivisions', $this->paginate());
		} else {
			$this->set('mdivisions', $this->paginate(array('Mdivision.id' => MDVI_ID)));
		}
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
				$this->Session->setFlash(__('The mdivision has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The mdivision could not be saved. Please, try again.'));
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
		$this->Mdivision->id = $id;
		if (!$this->Mdivision->exists($id)) {
			throw new NotFoundException(__('Invalid mdivision'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Mdivision->save($this->request->data)) {
				$this->Session->setFlash(__('The mdivision has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The mdivision could not be saved. Please, try again.'));
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
		$this->request->onlyAllow('post', 'delete');
		if ($this->Mdivision->delete()) {
			$this->Session->setFlash(__('Mdivision deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Mdivision was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
