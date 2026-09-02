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
 	public function beforeFilter(){

		$this->Auth->allow('view');
	}
/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($mcoursenames_id = null, $btn = NULL) {

		$this->loadModel('Vryoukin');
 		$Vryoukin = $this->Vryoukin->find('first', array('fields' => "id", 'conditions' => array('mcoursename_id =' =>$mcoursenames_id), 'recursive'  => -1));
//var_dump($Vryoukin); exit;
		$id =  $Vryoukin["Vryoukin"]["id"];

		if (!$this->Mryoukin->exists($id)) {
			throw new NotFoundException(__('Invalid mryoukin'));
		}

		$options = array('conditions' => array('Mryoukin.' . $this->Mryoukin->primaryKey => $id));
		$mryoukin = $this->Mryoukin->find('first', $options);
		$this->set('mryoukin', $mryoukin);

		$data = NULL;
		$sql = "SELECT  Msubscription.mday_id , COUNT( Msubscription.mday_id ) as cnt ";
		$sql .= ' FROM msubscriptions AS Msubscription';
		$sql .= ' WHERE mworkst_id in (15, 20, 35)  AND mryoukin_id ';
		$sql2 = ' GROUP BY Msubscription.mday_id';
 		$sql2 .= ' ORDER BY Msubscription.mday_id';

//id 4の場合は独立講座なので1の独立講座標準コースを読み込む
//var_dump($mcoursenames_id);
	/*
		if($mcoursenames_id == 11 OR $mcoursenames_id == 12) {

 			$Vryoukin1 = $this->Vryoukin->find('first', array('fields' => "id", 'conditions' => array('mcoursename_id =' => 11), 'recursive'  => -1));
			$id2 =  $Vryoukin1["Vryoukin"]["id"];
//var_dump($id2. "<bR>");
			$options = array('conditions' => array('Mryoukin.' . $this->Mryoukin->primaryKey => $id2),'recursive'  => -1);
			$mryoukin = $this->Mryoukin->find('first', $options);
//var_dump($mryoukin);

			$this->set('mryoukin1', $mryoukin);
		//	$wk = "in("
		//	$wk .=  $id2 . "," . $id2 + 1 . "," .  $id2 + 2 .") ";
			$wk =" in(1301,1302) ";
			$sql3= $sql . $wk . $sql2;

			$data = $this->Mryoukin->query($sql3);
//var_dump($sql3, $data);

} else */
		switch ($mcoursenames_id) {
			case '1':
			case '11':
			case '12':
			case '13':
			$this->set('mryoukin1', $mryoukin);

 				$Vryoukin1 = $this->Vryoukin->find('first', array('fields' => "id", 'conditions' => array('mcoursename_id =' => $mcoursenames_id), 'recursive'  => -1));
				$id2 =  $Vryoukin["Vryoukin"]["id"];


				$sql3= $sql . '= ' . $id2 . $sql2;
				$data = $this->Mryoukin->query($sql3);
		}
		
		$kuuseki = NULL;
		if(!is_null($data)) {
			foreach($data as $row) {
				$kuuseki[$row['Msubscription']['mday_id']] = 0;
				$kuuseki[$row['Msubscription']['mday_id']] = $row['0']['cnt'];
			}
			$this->set('kuuseki', $kuuseki);
		}
		if(($mryoukin['Mryoukin']['anytime_flg']  == FALSE) AND ($mryoukin['Mryoukin']['pending_flg'] == FALSE)) {

 			$Mryoukin2 = $this->Mryoukin->find('all', array('fields' => "id", 'conditions' => array('mcoursename_id =' =>$mcoursenames_id), 'recursive'  => -1));


		//	$id2 =  $Vryoukin["Vryoukin"]["id"];
			$wk = 'in(';
			foreach($Mryoukin2 as $row) {
			  $wk .= $row["Mryoukin"]['id'] . ",";

			}
			$wk .= "0)";
//

//var_dump($wk); exit;


			$sql = 'SELECT COUNT( Msubscription.id ) as cnt , Eschedule.*';
			$sql .= ' FROM eschedules AS Eschedule';
			$sql .= ' LEFT JOIN vsubscriptions AS Msubscription ON Eschedule.id = Msubscription.eschedule_id';
			$sql .= ' WHERE Eschedule.enddate + interval 1 day > NOW( ) ';
			$sql .= ' AND Eschedule.mryoukin_id ' . $wk;
			$sql .= ' GROUP BY Eschedule.id';
			$sql .= ' ORDER BY Eschedule.date1';

			$data = $this->Mryoukin->query($sql);
//var_dump($data);exit;
		}
		$this->set('data', $data);
		$this->set('btn', $btn);
		$this->layout = 'webout_in';
	}
}
