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
class TodaysController extends AppController {
   public $uses = array('Vattendance', 'Msubscription');
/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Flash', 'Session');
	public function beforeFilter(){
		
		//ログインなしでアクセス可能なページを列挙
		//
		$this->Auth->allow('index', 'today_index', 'edit2');
	}
	
	
	public function index() {
//var_dump($this->request->is);
	
		if ($this->request->is(array('post', 'put'))) {
			//$this->seikyusho_pdf($this->request->data['Vattendance']['id']);
			return $this->redirect(array('action' => 'today_index', $this->request->data['Vattendance']['id']));
			
		}

		$vattendances = $this->Vattendance->find('list');
		$this->set(compact('vattendances'));

	
	}
	public function today_index($id) {
	
		if ($this->request->is('post')) {
		//	$this->seikyusho_pdf($this->request->data['Mlecturer']['id'], $this->request->data['Mlecturer']['nen']['year'], $this->request->data['Mlecturer']['tuki']['month']);
		//	exit;

		}
//		$vattendances = $this->Eschedule->Vattendance->find('list');
//		$this->set(compact('vattendances'));
//		$this->loadModel('Vattendance');
		$options = array('conditions' => array('Vattendance.id' => $id));
		$vattendance = $this->Vattendance->find('first', $options);
		$this->set('days', $vattendance['Vattendance']['days']);
		$options = array('conditions' => array('Msubscription.eschedule_id' => $id, 'Msubscription.mworkst_id ' => array(40,46,50), 'Msubscription.mstudentst' .$vattendance['Vattendance']['days'] . '_id' => 10));
		$msubscriptions = $this->Msubscription->find('all', $options);
		$this->set('msubscriptions', $msubscriptions);
	//	$this->set('msubscriptions');

	
	}
	public function edit2($id = null,  $days = NULL, $eschedule_id = NULL, $mworkst_id = NULL) {
		$sql = 'UPDATE msubscriptions SET ';
		

		if($mworkst_id != 46) 
			$sql .=  'mworkst_id = 50 , ';
			
		$sql .= 'mstudentst' . $days . '_id = 20 ,modified = now() WHERE id = ' . $id;

//		$sql = 'UPDATE msubscriptions SET mworkst_id = ' .  50 . ' ,mstudentst' . $days . '_id = 20 ,modified = now() WHERE id = ' . $id;

		$data1 = $this->Msubscription->query($sql);
		return $this->redirect(array('action' => 'today_index', $eschedule_id));

	
	}


	
 }