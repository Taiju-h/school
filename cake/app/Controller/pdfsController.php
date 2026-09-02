<?php
App::uses('AppController', 'Controller');
App::import('Vendor', 'tcpdf/tcpdf');
App::import('Vendor', 'fpdi/fpdi');
/**
 * Mtickets Controller
 *
 * @property Mticket $Mticket
 */
class PdfsController extends AppController {
public $components=array("RequestHandler");

function sankalist($id = NULL){

	$db =& ConnectionManager::getDataSource('default');
		$sql3 = 'SELECT kaishaname, daihyouname, address, telno FROM mtenpos as Mtenpo';
		$sql3 = $sql3 .  ' WHERE id = ' . $id;
	$mtenpo = $db->query($sql3);
	$this->set('mtenpo', $mtenpo['0']);
	//それぞれに応じて読むよぉ～
	
	$sql3 = 'SELECT mtenpos.mdivision_id as mdivision_id, mtenpos.tenponame as tenponame , sum(ekanteihoukokues.mpeople_id) as mpeople_id, sum(ekanteihoukokues.kanteiryoukin) as kanteiryoukin, sum(ekanteihoukokues.omisebun) as omisebun';
	$sql3 .= ' From ekanteihoukokues as ekanteihoukokues , mtenpos AS mtenpos Where ekanteihoukokues.mtenpo_id  = mtenpos.id AND mtenpos.tgroup = '. $id; 
	$sql3 .= " AND DATE_FORMAT(ekanteihoukokues.kantei_date, '%Y%m') = " . $year . $mon;
	$sql3 .= " GROUP BY ekanteihoukokues.mtenpo_id";
	$shukei = $db->query($sql3);

//var_dump($sql3);exit;

	$this->set('shukei', $shukei);
//var_dump($shukei, $sql3);exit;
	
	
	$this->autoLayout = false;
	$this->RequestHandler->respondAs('application/pdf');
	$this->set('mon', $mon);
	$this->set('year', $year);
	$this->set('id', $id);
	$date = sprintf("%04d年%2d月%2d日",date('Y'), date('m'), date('d'));
	$this->set('date', $date);
	}
}

?>