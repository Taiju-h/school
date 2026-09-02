<?php
App::uses('AppController', 'Controller');
/**
 * Massociations Controller
 *
 * @property Massociation $Massociation
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class MassociationsController extends AppController {

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
		$this->Massociation->recursive = 0;
		$this->set('massociations', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Massociation->exists($id)) {
			throw new NotFoundException(__('Invalid massociation'));
		}
		$options = array('conditions' => array('Massociation.' . $this->Massociation->primaryKey => $id));
		$this->set('massociation', $this->Massociation->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$cnt = $this->Massociation->find('count',array('conditions'=> 
			array(
					'Massociation.mfile_id' => $this->request->data['Massociation']['mfile_id'],
					'Massociation.mkbn1_id' => $this->request->data['Massociation']['mkbn1_id'],
					'Massociation.mkbn2_id' => $this->request->data['Massociation']['mkbn2_id'],
					'Massociation.mkbn3_id' => $this->request->data['Massociation']['mkbn3_id'],
				)));
			if($cnt> 0) {
				$this->Flash->error(__('既に存在する組み合わせです'));
			} else {
			$this->Massociation->create();
				if ($this->Massociation->save($this->request->data)) {
					$this->Flash->success(__('保存しました'));
					return $this->redirect(array('action' => 'index'));
				} else {
					$this->Flash->error(__('保存できませんでした'));
				}
			}
		}
		$mfiles = $this->Massociation->Mfile->find('list', array('Mfile.list_flg' => True));
		$mkbn1s = $this->Massociation->Mkbn1->find('list');
		$mkbn2s = $this->Massociation->Mkbn2->find('list');
		$mkbn3s = $this->Massociation->Mkbn3->find('list');
		$this->set(compact('mfiles', 'mkbn1s', 'mkbn2s', 'mkbn3s'));
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
		if (!$this->Massociation->exists($id)) {
			throw new NotFoundException(__('Invalid massociation'));
		}
		$this->Massociation->id = $id;

		if ($this->request->is(array('post', 'put'))) {
			if ($this->Massociation->save($this->request->data)) {
				$this->Flash->success(__('保存しました'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('保存できませんでした'));
			}
		} else {
			$options = array('conditions' => array('Massociation.' . $this->Massociation->primaryKey => $id));
			$this->request->data = $this->Massociation->find('first', $options);
		}
		$mfiles = $this->Massociation->Mfile->find('list', array('Mfile.list_flg' => True));
		$mkbn1s = $this->Massociation->Mkbn1->find('list');
		$mkbn2s = $this->Massociation->Mkbn2->find('list');
		$mkbn3s = $this->Massociation->Mkbn3->find('list');
		$this->set(compact('mfiles', 'mkbn1s', 'mkbn2s', 'mkbn3s'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Massociation->id = $id;
		if (!$this->Massociation->exists()) {
			throw new NotFoundException(__('Invalid massociation'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Massociation->delete()) {
			$this->Flash->success(__('The massociation has been deleted.'));
		} else {
			$this->Flash->error(__('The massociation could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
