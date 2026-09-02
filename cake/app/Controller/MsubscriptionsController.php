<?php
App::uses('AppController', 'Controller');
/**
 * Msubscriptions Controller
 *
 * @property Msubscription $Msubscription
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class MsubscriptionsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Flash', 'Session');

	public function add_doku() {
		if ($this->request->is('post')) {
			$this->Msubscription->create();
			$this->request->data['Msubscription']['mryoukin_id'] = 700;
			$this->request->data['Msubscription']['mpaymentmethod_id'] = 6;
			if(empty($this->request->data['Msubscription']['eschedule_id']))
				$this->request->data['Msubscription']['mworkst_id'] = 50;
			else $this->request->data['Msubscription']['mworkst_id'] = 40;
			$this->request->data['Msubscription']['mstudentst1_id'] = 10;
			$this->request->data['Msubscription']['admissiondate'] = '2019-05-28';

			if ($this->Msubscription->save($this->request->data)) {
				$this->Flash->success(__('The msubscription has been saved.'));
			}
		}
		$day = new DateTime();

		$this->loadModel('Vdokuritsum');
		$vdokuritsums = $this->Vdokuritsum->find('list', array('conditions' => array('Vdokuritsum.m_id' => NULL), 'order' => 'Vdokuritsum.id'));
		$this->set(compact('vdokuritsums'));
		$this->loadModel('Eschedule');
		$Eschedules = $this->Eschedule->find('list', array('fields' => array('Eschedule.id','Eschedule.date1'),'conditions' => array('Eschedule.mryoukin_id' => 700, 'Eschedule.deadline > ' => $day->format('Y-m-d')), 'order' => 'Eschedule.date1'));
		$this->set(compact('Eschedules'));

	}

/**
 * index method
 *
 * @return void
 */
	public function index($sts = NULL) {


		switch ($sts){
			case 30:

				$day = new DateTime();
			    $this->Paginator->settings = array (
	 					'conditions' => array('Msubscription.mworkst_id <' => 50, 'Msubscription.admissiondate <= '=> $day->format('Y-m-d') ),
						'sort' => 'Msubscription.admissiondate',
						'direction' => 'ASC' ,  'limit' => 40, 'recursive' => 0   );
				break;
			case 40:
			    $this->Paginator->settings = array (
	 					'conditions' => array('Msubscription.mworkst_id >=' => 20, 'Msubscription.mworkst_id <=' => 40,'Mryoukin.mcoursename_id =' => array(6, 11, 12, 13)),
						'sort' => 'Msubscription.admissiondate',
						'direction' => 'ASC',  'limit' => 40, 'recursive' => 0  );
				break;

			case 55:
			    $this->Paginator->settings = array (
	 					'conditions' => array('Msubscription.mworkst_id ' => 55),
						'sort' => 'Msubscription.admissiondate',
						'direction' => 'ASC',   'limit' => 40, 'recursive' => 0 );
				break;
 			case 50: //受講済み、一部支払い
			    $this->Paginator->settings = array (
	 					'conditions' => array('Msubscription.mworkst_id ' => array(50, 55)),
						'sort' => 'Msubscription.admissiondate',
						'direction' => 'DESC' , 'limit' => 40, 'recursive' => 0);
				break;
			case 100: //お仕事講座 受講済み
				    $this->Paginator->settings = array (
		 					'conditions' => array('Msubscription.mworkst_id ' => array(50, 55),'Mryoukin.mcoursename_id =' => array(11,12)),
							'sort' => 'Msubscription.admissiondate',
							'direction' => 'DESC' , 'limit' => 40, 'recursive' => 0);
					break;
			case 110: //お仕事講座 受講済み
					    $this->Paginator->settings = array (
			 					'conditions' => array('Msubscription.mworkst_id ' => array(50, 55),'Mryoukin.mcoursename_id =' => 13),
								'sort' => 'Msubscription.admissiondate',
								'direction' => 'DESC' , 'limit' => 40, 'recursive' => 0);
						break;
			case 888: //通信講座　受講済み
			    	$this->Paginator->settings = array (
	 					'conditions' => array('Msubscription.mworkst_id ' => array(40, 42, 50), 'Msubscription.mryoukin_id' => 9),
						'sort' => 'Msubscription.mworkst_id',
						'direction' => 'DESC' , 'limit' => 40, 'recursive' => 0);
				break;

			case 999: //紹介インセンティブ対象
			    $this->Paginator->settings = array (
	 					'conditions' => array('Msubscription.mworkst_id ' => array(50, 52,55), 'Msubscription.mintroduction_id' => 1),
						'sort' => 'Msubscription.admissiondate',
						'direction' => 'DESC' , 'limit' => 40, 'recursive' => 0);
				break;
			default:
			    $this->Paginator->settings = array (
						'sort' => 'Msubscription.mworkst_id',
						'direction' => 'ASC' ,  'limit' => 40, 'recursive' => 0 );
		}
		$this->set('msubscriptions', $this->Paginator->paginate());
		$this->set('sts', $sts);
	}


/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Msubscription->exists($id)) {
			throw new NotFoundException(__('Invalid msubscription'));
		}
		$options = array('conditions' => array('Msubscription.' . $this->Msubscription->primaryKey => $id));
		$this->set('msubscription', $this->Msubscription->find('first', $options));

	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Msubscription->create();
			if ($this->Msubscription->save($this->request->data)) {
				$this->Flash->success(__('The msubscription has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The msubscription could not be saved. Please, try again.'));
			}
		}
		$musers = $this->Msubscription->Muser->find('list');
		$mryoukins = $this->Msubscription->Mryoukin->find('list');
		$mpaymentmethods = $this->Msubscription->Mpaymentmethod->find('list');
		$mdivisions = $this->Msubscription->Mdivision->find('list');
		$mintroductions = $this->Msubscription->Mintroduction->find('list');
		$sales = $this->Msubscription->Sale->find('list');
		$this->set(compact('musers', 'mryoukins', 'mpaymentmethods', 'mdivisions', 'mintroductions', 'sales'));
	}

/**
 * edit method20
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Msubscription->exists($id)) {
			throw new NotFoundException(__('Invalid msubscription'));
		}
		$this->Msubscription->id = $id;
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Msubscription->save($this->request->data)) {
				$this->Flash->success(__('The msubscription has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The msubscription could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Msubscription.' . $this->Msubscription->primaryKey => $id));
			$this->request->data = $this->Msubscription->find('first', $options);
		}
		$mdays = $this->Msubscription->Mday->find('list');
		$musers = $this->Msubscription->Muser->find('list');
		$mryoukins = $this->Msubscription->Mryoukin->find('list');
		$mpaymentmethods = $this->Msubscription->Mpaymentmethod->find('list');
		$mdivisions = $this->Msubscription->Mdivision->find('list');
		$mworksts = $this->Msubscription->Mworkst->find('list');
		$mstudentst1s = $mstudentst2s = $mstudentst3s = $mstudentst4s = $mstudentst5s = $mstudentst6s = $this->Msubscription->Mstudentst1->find('list');
		$mintroductions = $this->Msubscription->Mintroduction->find('list');
		$sales= $this->Msubscription->Sale->find('list');
		$this->set(compact('musers', 'mryoukins', 'mpaymentmethods', 'mdivisions', 'mworksts', 'mstudentst1s', 'mstudentst2s',  'mstudentst3s', 'mstudentst4s', 'mstudentst5s','mstudentst6s','mintroductions','mdays', 'sales'));

	}

	public function edit1($id = null, $mworkst_id = null, $eschedule_id = null) {
		if(is_null($eschedule_id))
			$sql = 'UPDATE msubscriptions SET mworkst_id = ' . $mworkst_id . ' ,admissiondate = now() ,modified = now() WHERE id = ' . $id;
		else $sql = 'UPDATE msubscriptions SET mworkst_id = ' . $mworkst_id . ' ,modified = now() WHERE id = ' . $id;

		$data1 = $this->Msubscription->query($sql);
		return $this->redirect(array('action' => 'index'));


	}


/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Msubscription->id = $id;
		if (!$this->Msubscription->exists()) {
			throw new NotFoundException(__('Invalid msubscription'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Msubscription->delete()) {
			$this->Flash->success(__('The msubscription has been deleted.'));
		} else {
			$this->Flash->error(__('The msubscription could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
