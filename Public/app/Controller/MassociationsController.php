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


 	public function beforeFilter(){

		$this->Auth->allow('tindex', 'findex', 'oview','text');
	}
    
    
 	public function tindex(){
  
	}
    
     
	public function text($id = NULL, $kbn = NULL, $id2 = NULL) {
		//$this->layout = 'study';
		$conditions = array(
		    'fields' => array(
		        'id',
		        'dname'
		    ),
			 'conditions' => array('taboo_flg =' => 0)
		);
		$this->set('mkbn1s', $this->Massociation->Mkbn1->find('list', $conditions));
		$this->set('id', $id);
		$this->set('id2', $id2);
		if(! is_null($id)) {
			$options = array(
				//フィールド名の配列
			  'fields' => array('Massociation.id', 'Massociation.title'), 
			  'conditions' => array('Massociation.disp_flg =' => 1, 'Massociation.mkbn1_id =' => $id, 'Mkbn1.taboo_flg =' => 0)
	 		);
			
			//$this->set('mkbn3s', $this->Massociation->Mkbn3s->find('all'));
			
			$sql = "SELECT DISTINCT Mkbn3.id, Mkbn3.name FROM mkbn3s as  Mkbn3 , massociations  as Massociation  WHERE Mkbn3.id = Massociation.mkbn3_id AND Massociation.mkbn1_id = " . $id;
			$sql .= " ORDER BY Mkbn3.oder";
			//$data = $this->Massociation->query($sql);
			$this->set('mkbn3s', $this->Massociation->query($sql));

			$sql = "SELECT DISTINCT Mkbn2.id, Mkbn2.name FROM mkbn2s as  Mkbn2 , massociations  as Massociation  WHERE Mkbn2.id = Massociation.mkbn2_id AND Massociation.mkbn1_id = " . $id;
			$sql .= " ORDER BY Mkbn2.oder";
			///$data = $this->Massociation->query($sql);
			$this->set('mkbn2s', $this->Massociation->query($sql));
			$this->set('kbn1', $id);

			
			//$this->set('Massociations', $this->Massociation->find('list', $options));

		}
		if(! is_null($id2)) {
			$conditions = array(
			    'fields' => array(
			        'id',
			        'dname'
			    ),
			 'conditions' => array('taboo_flg =' => 0)
			);
			$this->set('mkbn1s', $this->Massociation->Mkbn1->find('list', $conditions));
			if($kbn == 2) {
				$options = array(
					//フィールド名の配列
				  'fields' => array('Mfile.id', 'Mfile.thumbnail', 'Mfile.title', 'Mfile.filesize', 'Mfile.filetype', 'Mfile.description', 'Mfile.limit_flg', 'Mkbn1.name' , 'Mkbn3.name', 'Mkbn2.name'), 
				  'conditions' => array('Mfile.disp_flg =' => 1 , 'Massociation.mkbn1_id =' => $id, 'Massociation.mkbn2_id' => $id2)
		 		);
			} else {
				$options = array(
					//フィールド名の配列
				  'fields' => array('Mfile.id', 'Mfile.thumbnail', 'Mfile.title', 'Mfile.filesize', 'Mfile.filetype', 'Mfile.description', 'Mfile.limit_flg', 'Mkbn1.name' , 'Mkbn3.name', 'Mkbn2.name'), 
				  'conditions' => array('Mfile.disp_flg =' => 1 , 'Massociation.mkbn1_id =' => $id, 'Massociation.mkbn3_id' => $id2)
		 		);
			}
			
			$this->set('Massociations', $this->Massociation->find('all', $options));

		}
	}
	public function tindex_taboo($id = NULL, $kbn = NULL, $id2 = NULL) {
		$auth =$this->Session->read('Auth');

//var_dump($auth);exit;
		if(!($auth['User']['taboo_flg'])) {	
				$this->Flash->error(__('禁断の書を閲覧する権限がありません。権限を持っている場合には管理者にご連絡ください。'));
			return $this->redirect(array('controller' => 'Users','action' => 'login_taboo'));
		}
		$this->layout = 'study';
		$conditions = array(
		    'fields' => array(
		        'id',
		        'dname'
		    ),
			 'conditions' => array('taboo_flg =' => 1)
		);
		$this->set('mkbn1s', $this->Massociation->Mkbn1->find('list', $conditions));
		$this->set('id', $id);
		$this->set('id2', $id2);
		if(! is_null($id)) {
			$options = array(
				//フィールド名の配列
			  'fields' => array('Massociation.id', 'Massociation.title'), 
			  'conditions' => array('Massociation.disp_flg =' => 1, 'Massociation.mkbn1_id =' => $id, 'Mkbn1.taboo_flg =' => 1)
	 		);
			
			//$this->set('mkbn3s', $this->Massociation->Mkbn3s->find('all'));
			
			$sql = "SELECT DISTINCT Mkbn3.id, Mkbn3.name FROM mkbn3s as  Mkbn3 , massociations  as Massociation  WHERE Mkbn3.id = Massociation.mkbn3_id AND Massociation.mkbn1_id = " . $id;
			$sql .= " ORDER BY Mkbn3.oder";
			//$data = $this->Massociation->query($sql);
			$this->set('mkbn3s', $this->Massociation->query($sql));

			$sql = "SELECT DISTINCT Mkbn2.id, Mkbn2.name FROM mkbn2s as  Mkbn2 , massociations  as Massociation  WHERE Mkbn2.id = Massociation.mkbn2_id AND Massociation.mkbn1_id = " . $id;
			$sql .= " ORDER BY Mkbn2.oder";
			///$data = $this->Massociation->query($sql);
			$this->set('mkbn2s', $this->Massociation->query($sql));
			$this->set('kbn1', $id);

			
			//$this->set('Massociations', $this->Massociation->find('list', $options));

		}
		if(! is_null($id2)) {
/*
			$conditions = array(
			    'fields' => array(
			        'id',
			        'dname'
			    ),
			);
			$this->set('mkbn1s', $this->Massociation->Mkbn1->find('list', $conditions));
*/
			if($kbn == 2) {
				$options = array(
					//フィールド名の配列
				  'fields' => array('Mfile.id', 'Mfile.thumbnail', 'Mfile.title', 'Mfile.filesize', 'Mfile.filetype', 'Mfile.description', 'Mfile.limit_flg', 'Mkbn1.name' , 'Mkbn3.name', 'Mkbn2.name'), 
				  'conditions' => array('Mfile.disp_flg =' => 1 , 'Massociation.mkbn1_id =' => $id, 'Massociation.mkbn2_id' => $id2)
		 		);
			} else {
				$options = array(
					//フィールド名の配列
				  'fields' => array('Mfile.id', 'Mfile.thumbnail', 'Mfile.title', 'Mfile.filesize', 'Mfile.filetype', 'Mfile.description', 'Mfile.limit_flg', 'Mkbn1.name' , 'Mkbn3.name', 'Mkbn2.name'), 
				  'conditions' => array('Mfile.disp_flg =' => 1 , 'Massociation.mkbn1_id =' => $id, 'Massociation.mkbn3_id' => $id2)
		 		);
			}
			
			$this->set('Massociations', $this->Massociation->find('all', $options));

		}
	}	public function findex($id, $kbn1, $kbn) {
	
	
	/*
		$this->Massociation->id = $id;
		if (!$this->Massociation->exists()) {
			throw new NotFoundException(__('ファイルが存在しません'));
		}
		
	*/
		$this->layout = 'study';
	}


}