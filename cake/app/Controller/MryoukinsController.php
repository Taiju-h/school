<?php
App::uses('AppController', 'Controller');
/**
 * Mryoukins Controller
 *
 * @property Mryoukin $Mryoukin
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class MryoukinsController extends AppController {

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
 //public $paginate = 'limit' => 50;
	public function index() {
		    $this->Paginator->settings = array ( 
 					'conditions' => array('Mryoukin.delflg ' => 0),
					'sort' => 'Mryoukin.oder', 
					'direction' => 'ASC',
					'limit' => 50    ); 
		$this->Mryoukin->recursive = 0;
		$this->set('mryoukins', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Mryoukin->exists($id)) {
			throw new NotFoundException(__('Invalid mryoukin'));
		}
		$options = array('conditions' => array('Mryoukin.' . $this->Mryoukin->primaryKey => $id));
		$this->set('mryoukin', $this->Mryoukin->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Mryoukin->create();
			if ($this->Mryoukin->save($this->request->data)) {
				$this->Flash->success(__('The mryoukin has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The mryoukin could not be saved. Please, try again.'));
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
		if (!$this->Mryoukin->exists($id)) {
			throw new NotFoundException(__('Invalid mryoukin'));
		}
		$this->Mryoukin->id = $id;

		if ($this->request->is(array('post', 'put'))) {
		
			if($this->request->data['Mryoukin']['opday'] == '')
				$this->request->data['Mryoukin']['opday'] = NULL;
			if ($this->Mryoukin->save($this->request->data)) {
				$this->Flash->success(__('The mryoukin has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The mryoukin could not be saved. Please, try again.'));
			}
		} 
		$options = array('conditions' => array('Mryoukin.' . $this->Mryoukin->primaryKey => $id));
		$this->request->data = $this->Mryoukin->find('first', $options);

//		$this->loadModel('Mlecturer');

		$mlecturers = $this->Mryoukin->Mlecturer->find('list');
		$places = $this->Mryoukin->Place->find('list');
		$this->set(compact('mlecturers', 'places'));
	
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Mryoukin->id = $id;
		if (!$this->Mryoukin->exists()) {
			throw new NotFoundException(__('Invalid mryoukin'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Mryoukin->delete()) {
			$this->Flash->success(__('The mryoukin has been deleted.'));
		} else {
			$this->Flash->error(__('The mryoukin could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
