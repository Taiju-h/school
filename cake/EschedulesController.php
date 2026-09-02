<?php
App::uses('AppController', 'Controller');

App::import('Vendor', 'tcpdf/tcpdf');
App::import('Vendor', 'fpdi/fpdi');

/**
 * Eschedules Controller
 *
 * @property Eschedule $Eschedule
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class EschedulesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Flash', 'Session', 'Pdf');

/**
 * index method
 *
 * @return void
 */
	public function index() {
	    $this->Paginator->settings = array ( 
				'sort' => 'Eschedule.deadline', 
				'direction' => 'DESC'    ); 

		$this->Eschedule->recursive = 0;
		$this->set('eschedules', $this->Paginator->paginate());
	}
	public function sankalist() {
		if ($this->request->is('post')) {
			$this->sankalist_pdf($this->request->data['Mlecturer']['id']);
			

		}

		$this->loadModel('Mlecturer');
		$mlecturers = $this->Mlecturer->find('list',  array('conditions' => array('Mlecturer.dispflg' => 1)));
		$this->set(compact('mlecturers'));
	
	

	}
	public function todaylist() {
		if ($this->request->is('post')) {
			$this->todaylist_pdf($this->request->data['Mlecturer']['id']);
			exit;
			

		}

		$this->loadModel('Vtodaylist');
		$vtodaylists = $this->Vtodaylist->find('list', array('fields' => array('kaisaidate', 'kaisaidate'), 'order' => 'Vtodaylist.kaisaidate'));
		$this->set(compact('vtodaylists'));
	

	}

	public function sankalist_pdf($id = NULL) {
//var_dump($id);exit;
	$week = array("日", "月", "火", "水", "木", "金", "土");

		$modified = new DateTime();
		$date = $modified->format('Y年m月d日');


			$pdf = $this->Pdf->pdfstart();
			// ページ追加
			$pdf->AddPage();
			$pdf->setSourceFile('./files/SchoolList.pdf');
			$page = $pdf->importPage(1);
			$pdf->useTemplate($page);
			$pdf->SetFont('kozminproregular','B',11);//日本語のフォントも使えるよーこれは明朝体
			$font_path = './ipag00303/ipag.ttf';

			if (file_exists($font_path)) {
				$font_name = $pdf->addTTFfont($font_path, 'TrueTypeUnicode');
				$pdf->SetFont($font_name, '', 10);
			}	

		$pdf->Text(170,3.5, $date);
		 
		$pdf->SetFontSize(16);
		$pdf->Text(55, 8, "スケジュールの確定している講座の申し込み状況"); 
		

		$pdf->SetFontSize(12);
		$x = 24;
		$pdf->Text($x, 18, "講 座"); 
		$x += 24;
		$pdf->Text($x, 18, "開催日");
		$x += 20;		 
		$pdf->Text($x, 15.5, "曜"); 
		$pdf->Text($x, 20.5, "日"); 
		$x += 7.5;		 
		$pdf->Text($x, 15.5, "回"); 
		$pdf->Text($x, 20.5, "数"); 
		$x += 7.5;		 
		$pdf->Text($x, 15.5, "人"); 
		$pdf->Text($x, 20.5, "数"); 
		$x += 7.5;		 
		$pdf->Text($x, 15.5, "定"); 
		$pdf->Text($x, 20.5, "員"); 
		$x += 47;		 
		$pdf->Text($x, 18, "参    加     者"); 
		
		$pdf->SetFont('kozminproregular','',11);//日本語のフォントも使えるよーこれは明朝体

		//$pdf->SetFontSize(11);

		$y = 27.5;
		$add =7.2;
		$eschedules = $this->Eschedule->find('all', array(
				'conditions' => array('Eschedule.enddate >' => $modified->format('Y-m-d'), 'Mryoukin.mlecturer_id' => $id), 
				'order' => 'Eschedule.date1'));

		foreach ($eschedules as $eschedule) {
//$wk = $eschedule['Eschedule']['id'];
			$msubscriptions = $this->Eschedule->Msubscription->find('all', array(
				'conditions' => array('Msubscription.eschedule_id =' => $eschedule['Eschedule']['id'], 'Msubscription.mworkst_id >=' => 35), 
				'order' => 'Msubscription.muser_id'));

			for($ix = 1; $eschedule['Mryoukin']['daytimes'] >= $ix; $ix++) { 
				$sankacnt= 0;

				if($ix ==  1) 
					$pdf->Text(18, $y, $eschedule['Mryoukin']['rname']); 
				$x = 43;
				$pdf->Text($x,$y, $eschedule['Eschedule']['date'. $ix]); 				
				$x += 25;
				$w = date('w', strtotime($eschedule['Eschedule']['date'. $ix]));

				$pdf->Text($x, $y,  $week[$w]);
				$x += 9.5;

				$pdf->Text($x, $y, $ix);
				//定員
				if($eschedule['Eschedule']['capacity'] > 0) 
					$capacity = $eschedule['Eschedule']['capacity'];
				else $capacity = $eschedule['Mryoukin']['capacity'];
				$x += 15.5;
				$pdf->Text($x, $y, $capacity); 				

				$wkname = ',';
				foreach ($msubscriptions as $msubscription) {
					if($msubscription['Msubscription']['mstudentst'.$ix . '_id'] == 10) {
						$sankacnt++;
						$wkname .= ', ' . $msubscription['Muser']['name'] . '様';
					}					
				
				}
				$x += 5;
				$pdf->Text($x, $y,substr($wkname,2));
				//参加人数
				$x -= 13;
				$pdf->Text($x, $y, $sankacnt);
				$y += $add;

			}


		
		}



		$pdf->Output($date . "講座の申し込み状況.pdf", 'FD');



						exit;

}
	public function todaylist_pdf($id = NULL) {
		$week = array("日", "月", "火", "水", "木", "金", "土");

		$modified = new DateTime();
		$date = $modified->format('Y年m月d日');


		$pdf = $this->Pdf->pdfstart('B5');
		// ページ追加
		$pdf->AddPage();
		$pdf->setSourceFile('./files/SchoolToday.pdf');
		$page = $pdf->importPage(1);
		$pdf->useTemplate($page);
		$pdf->SetFont('kozminproregular','B',11);//日本語のフォントも使えるよーこれは明朝体
		$font_path = './ipag00303/ipag.ttf';

		if (file_exists($font_path)) {
			$font_name = $pdf->addTTFfont($font_path, 'TrueTypeUnicode');
			$pdf->SetFont($font_name, '', 10);
		}	

		$pdf->Text(140,3.5, $date);
		 
		$pdf->SetFontSize(16);
		$pdf->Text(55, 7.5, "参加一覧（締め切り後送付）"); 
		

		$pdf->SetFontSize(12);
		$x = 24;
		$pdf->Text($x, 18, "開 催 日");
		$x += 22.5;		 
		$pdf->Text($x, 15.5, "曜"); 
		$pdf->Text($x, 20.5, "日"); 
		$x += 12.5;		 
		$pdf->Text($x, 18, "講    座"); 
		$x += 60;		 
		$pdf->Text($x, 18, "参    加     者"); 
		
		$pdf->SetFontSize(11);

		$y = 28.5;
		$add = 8.9;
		$eschedules = $this->Eschedule->find('all', array(
				'conditions' => array('Eschedule.enddate >' => $modified->format('Y-m-d'), 'Mryoukin.mlecturer_id' => $id), 
				'order' => 'Eschedule.date1'));

		foreach ($eschedules as $eschedule) {
//$wk = $eschedule['Eschedule']['id'];
			$msubscriptions = $this->Eschedule->Msubscription->find('all', array(
				'conditions' => array('Msubscription.eschedule_id =' => $eschedule['Eschedule']['id'], 'Msubscription.mworkst_id >=' => 35), 
				'order' => 'Msubscription.muser_id'));

			for($ix = 1; $eschedule['Mryoukin']['daytimes'] >= $ix; $ix++) { 
				$x = 22;
				$pdf->Text($x,$y, $eschedule['Eschedule']['date'. $ix]); 				
				$x += 24.5;
				$w = date('w', strtotime($eschedule['Eschedule']['date'. $ix]));

				$pdf->Text($x, $y,  $week[$w]);
				$x += 7.5;
				$sankacnt= 0;

				if($ix ==  1) 
					$pdf->Text($x, $y, $eschedule['Mryoukin']['rname']); 

				$wkname = ',';
				foreach ($msubscriptions as $msubscription) {
					if($msubscription['Msubscription']['mstudentst'.$ix . '_id'] == 10) {
						$sankacnt++;
						$wkname .= ', ' . $msubscription['Muser']['name'] . '様';
					}					
				
				}
				$y += $add;

			}
		}









		$pdf->SetFont('kozminproregular','',11);//日本語のフォントも使えるよーこれは明朝体
		$pdf->Output($date . "講座の申し込み状況.pdf", 'FD');



						exit;

	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Eschedule->exists($id)) {
			throw new NotFoundException(__('Invalid eschedule'));
		}
		$options = array('conditions' => array('Eschedule.' . $this->Eschedule->primaryKey => $id));
		$this->set('eschedule', $this->Eschedule->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Eschedule->create();
			if ($this->Eschedule->save($this->request->data)) {
 
			/* 最終日を設定する */
				if(! empty($this->request->data['Eschedule']['date3']['year'])) {
					$enddate = $this->request->data['Eschedule']['date3'];
				} else if(! empty($this->request->data['Eschedule']['date2']['year'])) {
					$enddate = $this->request->data['Eschedule']['date2'];
				} else $enddate = $this->request->data['Eschedule']['date1'];
				$enddate1 = $enddate['year'] . '-' . $enddate['month'] . '-' . $enddate['day'];
			/* 締切を設定する */
				$date = date_create($this->request->data['Eschedule']['date1']['year'] . '-' . $this->request->data['Eschedule']['date1']['month'] . '-' . $this->request->data['Eschedule']['date1']['day']);
				date_sub($date, date_interval_create_from_date_string('3 days'));
				$deadline = date_format($date, 'Y-m-d');	
				
				$sql = "UPDATE eschedules SET enddate = '" . $enddate1 . "' ,deadline = '" . $deadline . "' WHERE id = " . $this->Eschedule->getInsertID();;
				$data1 = $this->Eschedule->query($sql);

				$this->Flash->success(__('The eschedule has been saved.'));
				return $this->redirect(array('action' => 'add'));

			} else {
				$this->Flash->error(__('The eschedule could not be saved. Please, try again.'));
			}
		}
		$mryoukins = $this->Eschedule->Mryoukin->find('list',  array('conditions' => array('Mryoukin.pending_flg' => 0, 'Mryoukin.anytime_flg' => 0, 'Mryoukin.delflg' => 0)));
		$this->set(compact('mryoukins'));
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
		if (!$this->Eschedule->exists($id)) {
			throw new NotFoundException(__('Invalid eschedule'));
		}
		if ($this->request->is(array('post', 'put'))) {
			$this->Eschedule->id = $id;

			if ($this->Eschedule->save($this->request->data)) {
			/* 最終日を設定する */
				if(! empty($this->request->data['Eschedule']['date3']['year'])) {
					$enddate = $this->request->data['Eschedule']['date3'];
				} else if(! empty($this->request->data['Eschedule']['date2']['year'])) {
					$enddate = $this->request->data['Eschedule']['date2'];
				} else $enddate = $this->request->data['Eschedule']['date1'];
				$enddate1 = $enddate['year'] . '-' . $enddate['month'] . '-' . $enddate['day'];
			/* 締切を設定する 
				$date = date_create($this->request->data['Eschedule']['date1']['year'] . '-' . $this->request->data['Eschedule']['date1']['month'] . '-' . $this->request->data['Eschedule']['date1']['day']);
				date_sub($date, date_interval_create_from_date_string('3 days'));
				$deadline = date_format($date, 'Y-m-d');	
				$sql = "UPDATE eschedules SET enddate = '" . $enddate1 . "' ,deadline = '" . $deadline . "' WHERE id = " .$id;
				$data1 = $this->Eschedule->query($sql);
			*/	

				$this->Flash->success(__('The eschedule has been saved.'));
				return $this->redirect(array('action' => 'add'));
			} else {
				$this->Flash->error(__('The eschedule could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Eschedule.' . $this->Eschedule->primaryKey => $id));
			$this->request->data = $this->Eschedule->find('first', $options);
		}
		$mryoukins = $this->Eschedule->Mryoukin->find('list');
		$this->set(compact('mryoukins'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Eschedule->id = $id;
		if (!$this->Eschedule->exists()) {
			throw new NotFoundException(__('Invalid eschedule'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Eschedule->delete()) {
			$this->Flash->success(__('The eschedule has been deleted.'));
		} else {
			$this->Flash->error(__('The eschedule could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'add'));
	}
}
