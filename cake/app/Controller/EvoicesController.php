<?php
App::uses('AppController', 'Controller');
/**
 * Msum2s Controller
 *
 * @property Msum2 $Msum2
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class EvoicesController extends AppController {
  // public $uses = array('Vattendance', 'Msubscription');

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
		    $this->Paginator->settings = array (
 					'conditions' => array('Evoice.delflg ' => 0),
					'sort' => 'Evoice.modified',
					'direction' => 'desc',
					'limit' => 50    );


		$this->Evoice->recursive = 0;
		$this->set('evoices', $this->Paginator->paginate());
	}
/**
 * add method
 *
 * @return void
 */
	public function add($id = NULL) {


		if ($this->request->is('post') || $this->request->is('put')) {
			$Evoice = $this->request->data;
			if(is_null($id)) {
				$this->Evoice->create();
				$Evoice['Evoice']['approval_kbn'] = 1;

			} else $this->Evoice->id = $id;
//var_dump($Evoice);
			if ($this->Evoice->save($Evoice)) {
//var_dump($Evoice); 	exit;			//if(is_null($id))  $id = $this->Evoice->getInsertID();
				return $this->redirect(array('action' => 'add'));
			} else {
				$this->Session->setFlash(__('The ekantei could not be saved. Please, try again.'));
			}
		}

		if(!is_null($id))
			$this->request->data = $this->Evoice->read(null, $id);

	$this->index();
		$mcoursenames = $this->Evoice->Mcoursename->find('list', array('conditions' => array('NOT' =>array('Mcoursename.vname ' => NULL, ))));
 //, array('conditions' => array('Mvioce.id =' => $id,'Mvioce.delflg =' => 0)));
		$msexes = $this->Evoice->Msex->find('list');
		$mnendais = $this->Evoice->Mnendai->find('list');
		$this->set(compact('mcoursenames', 'msexes', 'mnendais'));
		$this->set('id', $id);

	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Evoice->id = $id;
		if (!$this->Evoice->exists()) {
			throw new NotFoundException(__('Invalid Evoice'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Evoice->delete()) {
			$this->Flash->success(__('The Evoice has been deleted.'));
		} else {
			$this->Flash->error(__('The Evoice could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
