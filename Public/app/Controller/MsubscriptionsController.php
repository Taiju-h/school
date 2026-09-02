<?php
App::uses('AppController', 'Controller');

/**
 *  Msubscriptions Controller
 *
 * @property  Msubscriptions $ Msubscription
 */
class MsubscriptionsController extends AppController {

/**
 * index method
 *
 * @return void
 */
	public function beforeFilter(){

		//ログインなしでアクセス可能なページを列挙
		//
		$this->Auth->allow();  
	}
	public $components = array('Paginator', 'Mail');

	public function index() {
		$this->Msubscription->recursive = 0;
		if(MDVI_ID == 1)
			$this->set('msubscriptions', $this->paginate());
		else
			$this->set('msubscriptions', $this->paginate(array('Msubscription.id' => MDVI_ID)));

	}

	public function select() {
		$this->set('title_for_layout', "講座種類選択画面");
		$this->layout = 'yoyakuform';
	}

	public function selectkoza($koza = NULL, $eid = NULL, $day = 99, $kbn =  NULL) {

		$week = array("日", "月", "火", "水", "木", "金", "土");

		$url = $this->referer();  // 'http://*****/users/view'
		//strstr($url, 'starot');
		if($day != 99) $eid = NULL;
		$this->Session->write('PRE_URL', strstr($url, 'starot'));
//		$this->Session->write('PRE_URL', TRUE);
// var_dump($url);exit;
//心理タロット講座を含む場合単独で申し込みとなる
		$this->Session->write('TANDOKU_KBN', $kbn);

		if(($koza == 600) OR ($koza == 2) OR ($koza == 3)OR ($koza == 4)) {
			$Mryoukin = NULL;
			$this->Session->write('TANDOKU', 1);

		}else {
			 $Mryoukin = $this->Session->read('Mryoukin');
			 $this->Session->write('TANDOKU', 0);
		}



		$Mryoukin[] = $koza;
		$this->Session->Delete('Mryoukin');
		$this->Session->write('Mryoukin', $Mryoukin);
		$Esche = $this->Session->read('Esche');
		if(!is_null($eid)) {
			$this->loadModel('Eschedule');
			$options = array('conditions' => array('Eschedule.' . $this->Eschedule->primaryKey => $eid));
			$Eschedule = $this->Eschedule->find('first', $options);

			if(empty($Eschedule['Eschedule']['jikan']))
				$jikan = $Eschedule['Mryoukin']['optime'];
			else $jikan = $Eschedule['Eschedule']['jikan'];
			if($Eschedule['Eschedule']['capacity'] == 0)
					$capacity = $mryoukin['Mryoukin']['capacity'];
				else $capacity = $Eschedule['Eschedule']['capacity'];
			$jikan = "(" . $jikan . ")";

			$date = NULL;
			for($ix = 0; $ix < 9; $ix++) {
				if(! empty($Eschedule['Eschedule']['date'.$ix])) {
					$w = date('w', strtotime($Eschedule['Eschedule']['date'.$ix]));
					$date .= $ix ."日目： " . $Eschedule['Eschedule']['date'.$ix]  . '('. $week[$w]. ') ' . $jikan;
					$date .= "</br>";
				}
			}
		}

		$Esche[$koza] = array($eid, $date);
		$this->Session->write('Esche', $Esche);

		if(!is_null($day)) {
//			$options = array('conditions' => array('Mday.id' . $this->Mday->id => $day));
			$options = array('conditions' => array('Mday.id' => $day));
			$Mday = $this->Msubscription->Mday->find('first', $options);
		} else $Mday['Mday']['name'] = NULL;
		$Day[$koza] = array($day, $Mday['Mday']['name']);
		$this->Session->write('Day', $Day);

	//	if($kbn == 2) {
	//		$this->Session->write('Yoyaku', 2);

	//		return $this->redirect(array('action' => 'user2'));
	//	}
		return $this->redirect(array('action' => 'user'));
	}
	public function delete($id = null) {
		$wk = $this->Session->read('Mryoukin');
		$Mryoukin = NULL;
		$Esche = $this->Session->read('Esche');
		$Day = $this->Session->read('Day');
		foreach ($wk as  $key => $val) {
			if($val == $id) {
				unset($Esche[$val]);
				unset($Day[$val]);
				continue;
			}
			$Mryoukin[] = $val;

		}
		$wk = $this->Session->delete('Mryoukin');
		$wk = $this->Session->delete('Esche');
		$wk = $this->Session->delete('Day');

		$this->Session->Write('Mryoukin',$Mryoukin );
		$this->Session->write('Esche', $Esche);
		$this->Session->write('Day', $Day);
		return $this->redirect(array('action' => 'user'));
	}

	public function delete2($id = null) {
		$wk = $this->Session->read('Mryoukin');
		$Mryoukin = NULL;
		$Esche = $this->Session->read('Esche');
		$Day = $this->Session->read('Day');
		foreach ($wk as  $key => $val) {
			if($val == $id) {
				unset($Esche[$val]);
				unset($Day[$val]);
				continue;
			}
			$Mryoukin[] = $val;

		}
		$wk = $this->Session->delete('Mryoukin');
		$wk = $this->Session->delete('Esche');
		$wk = $this->Session->delete('Day');

		$this->Session->Write('Mryoukin',$Mryoukin );
		$this->Session->write('Esche', $Esche);
		$this->Session->write('Day', $Day);
		return $this->redirect(array('action' => 'view'));
	}

	public function selectsub($kbn = NULL) {
		if ($this->request->is('post') || $this->request->is('put')) {
			if(isset($this->request->data['back'])) {
				$this->redirect(array('action' => 'select'));
			}
			if(empty($this->request->data['Msubscription']['Mryoukin'])) {
				$this->Session->setFlash(__('講座が選択されていません。'));
			} else {
				$this->Session->write('Mryoukin', $this->request->data['Msubscription']['Mryoukin']);
				$this->Session->write('Remarks', $this->request->data['Msubscription']['Remarks']);
				$this->Session->write('Kbn', $kbn);

				$this->redirect(array('action' => 'user'));
			}
		}
		$this->request->data['Msubscription']['Remarks'] = $this->Session->read('Remarks');
		$this->set('title_for_layout', "講座選択画面");
		if($this->Session->read('PRE_URL'))
				$this->layout = 'tsuhan';
		else	$this->layout = 'webout';
		$this->Msubscription->Mryoukin->virtualFields = array('full_name' => "CONCAT(name , '：',  format(kng, 0), '円')");
		if(is_null($kbn)) {
			$mryoukins = $this->Msubscription->Mryoukin->find('list',array( 'fields' => array('id', 'full_name')));
		 } else $mryoukins = $this->Msubscription->Mryoukin->find('list',array('fields' => array('id', 'full_name'), 'conditions'=> array('Mryoukin.mkkbn_id =' => $kbn)));
		$this->set(compact('mryoukins'));

	//var_dump($this->Session->read('oldact'));exit

	}


	public function cancel($kbn = NULL) {
		if ($this->request->is('post') || $this->request->is('put')) {
			if(isset($this->request->data['edit_submit'])) {
				$this->redirect(array('action' => 'edit'));
			}
			if(empty($this->request->data['Msubscription']['Mryoukin'])) {
				$this->Session->setFlash(__('講座が選択されていません。'));
			} else {
				$this->Session->write('Mryoukin', $this->request->data['Msubscription']['Mryoukin']);
				$this->Session->write('Remarks', $this->request->data['Msubscription']['Remarks']);

				$this->redirect(array('action' => 'view'));
			}
		}
		$this->request->data['Msubscription']['Remarks'] = $this->Session->read('Remarks');
		$this->set('title_for_layout', "講座選択画面");
		$this->layout = 'webout';
		$this->Msubscription->Mryoukin->virtualFields = array('full_name' => "CONCAT(name , '：',  format(kng, 0), '円')");
		if(is_null($kbn)) {
			$mryoukins = $this->Msubscription->Mryoukin->find('list',array( 'fields' => array('id', 'full_name')));
		 } else $mryoukins = $this->Msubscription->Mryoukin->find('list',array('fields' => array('id', 'full_name'), 'conditions'=> array('Mryoukin.mkkbn_id =' => $kbn)));
		$this->set(compact('mryoukins'));

	//var_dump($this->Session->read('oldact'));exit

	}

	public function user() {
		$this->set('title_for_layout', 'お客様情報画面');
		if($this->Session->read('PRE_URL'))
				$this->layout = 'tsuhan';
		else	$this->layout = 'webout';
		$Mryoukins = $this->Session->read('Mryoukin');

		if(empty($Mryoukins))
			return	$this->redirect(FULL_BASE_URL . '/index.php?go=bdFnXs');
		$wk = NULL;
	 	foreach ($Mryoukins as $mryoukin_id) {
			if(is_null($mryoukin_id)) continue;
			$wk .= ',' . $mryoukin_id;
		}

		$sql = 'SELECT Mryoukin.id, Mryoukin.name, Mryoukin.kng, Mryoukin.anytime_flg, Mryoukin.pending_flg, Mryoukin.opday, Mryoukin.optime, Mryoukin.capacity, Mryoukin.daytimes';
		$sql .= ' FROM mryoukins AS Mryoukin WHERE Mryoukin.id IN(' . substr($wk, 1) . ')';

		$Mryoukins = $this->Msubscription->query($sql);
		$this->Session->Write('Mryoukins' , $Mryoukins);

		//$user = $this->Auth->user();
		//既にログイン済みなので次へ
		//if(!is_null($user)) $this->redirect(array('action' => 'view'));

		$this->set('Mryoukins', $Mryoukins);
//		var_dump($mryoukin);exit;
		$this->set('user', $this->Session->read('user'));
		$this->set('cardflg', $this->Session->read('user.cardflg'));
		$this->set('Esche', $this->Session->read('Esche'));
		$this->set('Day', $this->Session->read('Day'));
//		$this->set('Remarks', $this->Session->read('Remarks'));
		/*
		if(! $this->Session->read('cardflg')) {
			$sql = 'UPDATE musers SET cardflg = 1 WHERE id = ' .  $this->Session->read('user_id');
			$data1 = $this->User->query($sql);
		}
		*/
	}
	public function user2() {
		$this->set('title_for_layout', 'お客様情報画面');
		if($this->Session->read('PRE_URL'))
				$this->layout = 'tsuhan';
		else	$this->layout = 'webout';
		$Mryoukins = $this->Session->read('Mryoukin');
		$wk = NULL;
		if(empty($Mryoukins))
			return	$this->redirect(FULL_BASE_URL . '/index.php?go=bdFnXs');
	 	foreach ($Mryoukins as $mryoukin_id)
			$wk .= ',' . $mryoukin_id;

		$sql = 'SELECT Mryoukin.id, Mryoukin.name, Mryoukin.kng, Mryoukin.anytime_flg, Mryoukin.pending_flg, Mryoukin.opday, Mryoukin.optime, Mryoukin.capacity ';
		$sql .= ' FROM mryoukins AS Mryoukin WHERE Mryoukin.id IN(' . substr($wk, 1) . ')';
		$Mryoukins = $this->Msubscription->query($sql);
		$this->Session->Write('Mryoukins' , $Mryoukins);

		//$user = $this->Auth->user();
		//既にログイン済みなので次へ
		//if(!is_null($user)) $this->redirect(array('action' => 'view'));

		$this->set('Mryoukins', $Mryoukins);
//		var_dump($mryoukin);exit;
		$this->set('user', $this->Session->read('user'));
		$this->set('cardflg', $this->Session->read('user.cardflg'));
		$this->set('Esche', $this->Session->read('Esche'));
		$this->set('Day', $this->Session->read('Day'));
//		$this->set('Remarks', $this->Session->read('Remarks'));
		/*
		if(! $this->Session->read('cardflg')) {
			$sql = 'UPDATE musers SET cardflg = 1 WHERE id = ' .  $this->Session->read('user_id');
			$data1 = $this->User->query($sql);
		}
		*/
	}
	public function card() {
		$this->set('title_for_layout', 'クレジット金額確認画面');
		if($this->Session->read('PRE_URL'))
				$this->layout = 'tsuhan';
		else	$this->layout = 'webout';

		$Msubscription = $this->Msubscription->find('all',
				array('conditions'=> array('Msubscription.Firstid' => $this->Session->read('Firstid'))));
		$this->set('Msubscriptions', $Msubscription);

		$this->set('user', $this->Session->read('user'));
		$this->set('cardflg', $this->Session->read('user.cardflg'));
		/*
		if(! $this->Session->read('cardflg')) {
			$sql = 'UPDATE musers SET cardflg = 1 WHERE id = ' .  $this->Session->read('user_id');
			$data1 = $this->User->query($sql);
		}
		*/

	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view() {

		if(! $this->Session->check('Mryoukins')) {
			$this->Flash->error(__('予期せぬエラーが発生いたしました。講座一覧に移動します。'));
			$this->redirect("../../index.php?Application#top"); // 講座一覧
		}


		if ($this->request->is(array('post', 'put'))) {
//$this->request->data['Msubscription']['mpaymentmethod_id'] = 3;

			$firstid = 0;
			$this->Session->write('Remarks', $this->request->data['Msubscription']['remarks']);
			if(is_null($this->request->data['Msubscription']['mpaymentmethod_id']))
				$this->request->data['Msubscription']['mpaymentmethod_id'] = -1;
			$this->Session->write('mpaymentmethod_id', $this->request->data['Msubscription']['mpaymentmethod_id'] );
			$this->Session->write('mdeliverytime_id', $this->request->data['Msubscription']['mdeliverytime_id']);
			$this->redirect(array('action' => 'view2'));
		}


		$Mryoukins = $this->Session->read('Mryoukin');
/*
		//独立講座の生徒で含まれる講座なら支払方法はなし（既存生徒とする）
		$wk_cnt = NULL;
		$auth =$this->Session->read('Auth');
		$wk_cnt = $this->Msubscription->find('all',
				array('conditions'=> array('Msubscription.muser_id' => $auth['User']['id'], 'Msubscription.mryoukin_id' =>array(610, 620, 630), 'Msubscription.mworkst_id' => array(50, 55))));
//var_dump($wk_cnt);
		//独立講座の生徒なら
		if(isset($wk_cnt)) {
			$Msums = NULL;
			$this->loadModel('Msum2');
			$Msums = $this->Msum2->find('all', array('conditions'=> array('Msum2.mryoukin_id' => $wk_cnt[0]['Msubscription']['mryoukin_id'])));
//var_dump($wk_cnt[0]['Msubscription']['mryoukin_id']);exit;

		 	foreach ($Msums as $Msum)
				$sums[$Msum['Msum2']['mryoukin2_id']] = True;

//var_dump($sums);

			$Msubscriptions = $this->Msubscription->find('all',
					array('conditions'=> array('Msubscription.muser_id' => $auth['User']['id'],  'Msubscription.mworkst_id <' => 100)));

			//既に受けている講座は抜かす。
		 	foreach ($Msubscriptions as $Msubscription){
//var_dump($Msubscription['Msubscription']['mryoukin_id']);
				if(isset($sums[$Msubscription['Msubscription']['mryoukin_id']]))
					unset($sums[$Msubscription['Msubscription']['mryoukin_id']]);
				//$out[$Msubscription['Mryoukin_id']] = True;
			}
//var_dump($sums);


			$kizon_flg = True; //含まれない講座があるときFalseとする
		 	foreach ($Mryoukins as $mryoukin_id) {
				if(!isset($sums[$mryoukin_id])) {
					$kizon_flg = False;
					break;
				}
			}
//var_dump($mryoukin_id);

			if($kizon_flg) {
				return $this->redirect(array('action' => 'view3', $kizon_flg));

			}
		}
	//独立講座用終わり
*/
		$this->set('title_for_layout', '支払方法・連絡事項');
		if($this->Session->read('PRE_URL'))
				$this->layout = 'tsuhan';
		else	$this->layout = 'webout';
		$wk = NULL;

	 	foreach ($Mryoukins as $mryoukin_id) {
		//var_dump($Mryoukins); exit;
			$wk .= ',' . $mryoukin_id;
		}

		$sql = 'SELECT Mryoukin.id, Mryoukin.name, Mryoukin.kng, Mryoukin.anytime_flg, Mryoukin.opday, Mryoukin.optime FROM mryoukins AS Mryoukin WHERE Mryoukin.id IN(' . substr($wk, 1) . ')';
		$wk_kin = $this->Msubscription->query($sql);
		$kng = 0;
		foreach ($wk_kin as $Mryoukin)
			$kng += $Mryoukin['Mryoukin']['kng'] * TAX;

		$sql = 'SELECT count(Mryoukin.id) as  cnt, sum(Mryoukin.cash_flg) as sum FROM mryoukins AS Mryoukin WHERE Mryoukin.id IN(' . substr($wk, 1) . ')';
		$data = $this->Msubscription->query($sql);
		if($data[0][0]['cnt'] == $data[0][0]['sum'])
			$mpaymentmethods = $this->Msubscription->Mpaymentmethod->find('list', array('conditions' => array('Mpaymentmethod.id !=' => 6)));
		else $mpaymentmethods = $this->Msubscription->Mpaymentmethod->find('list', array('conditions' => array('Mpaymentmethod.id ' => array(1, 2))));
//var_dump($mpaymentmethods);exit;
		$mdeliverytimes = $this->Msubscription->Mdeliverytime->find('list', array('conditions' => array('Mdeliverytime.delflg !=' => 1)));
		$this->set(compact('mpaymentmethods', 'mdeliverytimes'));

		$this->request->data['Msubscription']['remarks'] = $this->Session->read('Remarks');
		$this->request->data['Msubscription']['mpaymentmethod_id'] = $this->Session->read('mpaymentmethod_id');
		$this->request->data['Msubscription']['mdeliverytime_id'] = $this->Session->read('mdeliverytime_id');
		$this->request->data['Msubscription']['kng1'] = $kng;


		//$this->set('Remarks', $this->Session->read('Remarks'));


	}

	public function view3($kizon_flg = NULL) {

		if(! $this->Session->check('Mryoukins')) {
			$this->Flash->error(__('予期せぬエラーが発生いたしました。講座一覧に移動します。'));
			$this->redirect("../../index.php?Application#top"); // 講座一覧
		}


		if ($this->request->is(array('post', 'put'))) {
//$this->request->data['Msubscription']['mpaymentmethod_id'] = 0;

			$firstid = 0;
			$this->Session->write('Remarks', $this->request->data['Msubscription']['remarks']);
			$this->redirect(array('action' => 'view4'));
		}
		if($kizon_flg)
			$this->set('title_for_layout', '独立講座付帯・連絡事項');
		else $this->set('title_for_layout', '空席待ち予約・連絡事項');
		if($this->Session->read('PRE_URL'))
				$this->layout = 'tsuhan';
		else	$this->layout = 'webout';
/*
		$wk = NULL;

		$Mryoukins = $this->Session->read('Mryoukin');

	 	foreach ($Mryoukins as $mryoukin_id)
			$wk .= ',' . $mryoukin_id;

*/
		$this->Session->write('Kizon_flg', $kizon_flg);


		$this->request->data['Msubscription']['remarks'] = $this->Session->read('Remarks');


		//$this->set('Remarks', $this->Session->read('Remarks'));


	}

	public function view2() {

		if(! $this->Session->check('Mryoukins')) {
			$this->Flash->error(__('予期せぬエラーが発生いたしました。講座一覧に移動します。'));
			$this->redirect("../../index.php?Application#top"); // 講座一覧
		}

		if ($this->request->is(array('post', 'put'))) {
			if(isset($this->request->data['edit_submit']))
				return $this->redirect(array('action' => 'view'));
			if(isset($this->request->data['user_submit']))
				return $this->redirect(array('controller' => 'users', 'action' => 'edit'));

			$firstid = 0;

			$Mryoukins = $this->Session->read('Mryoukins');
			$Esche = $this->Session->read('Esche');
//var_dump($Esche);exit;

			$Day = $this->Session->read('Day');
			$msg = NULL;
			$kng = 0;
			// 登録するフィールド]

			foreach ($Mryoukins as $Mryoukin) {
		//var_dump($Mryoukin['Mryoukin']['anytime_flg']);
		//	var_dump($Mryoukin['Mryoukin']['pending_flg']);exit;
 			if((!$Mryoukin['Mryoukin']['anytime_flg']) AND (!$Mryoukin['Mryoukin']['pending_flg'])) {

				//if(($Mryoukin['Mryoukin']['anytime_flg']  == FALSE)) {
					$sql = 'SELECT COUNT( Msubscription.id ) as cnt , Eschedule.*';
					$sql .= ' FROM eschedules AS Eschedule';
					$sql .= ' LEFT JOIN vsubscriptions AS Msubscription ON Eschedule.id = Msubscription.eschedule_id';
					$sql .= ' WHERE Eschedule.mryoukin_id = ' . $Mryoukin['Mryoukin']['id'] . ' AND Eschedule.id = ' .  $Esche[$Mryoukin['Mryoukin']['id']]['0'];
					$sql .= ' GROUP BY Eschedule.id';

					$data = $this->Msubscription->query($sql);

//var_dump($data);exit;
					if($data[0]['Eschedule']['deadline'] < date("Y-m-d")) {
						$msg .=  $Mryoukin['id'] . 'は締切日を過ぎました。申込みできません。';
						continue;
					}

					if($data[0]['Eschedule']['capacity'] == 0)
						$capacity = $Mryoukin['Mryoukin']['capacity'];
					else $capacity =$data[0]['Eschedule']['capacity'] ;
					if($capacity <= $data[0][0]['cnt']){
						$msg .=  $Mryoukin['id'] . 'は定員に達しました。申込みできません。';
						continue;
					}
				}
				$kng += $Mryoukin['Mryoukin']['kng'];


	//			if(
				//	締め切りと定員がOKなら受ける
				$fields = array('firstid', 'muser_id', 'mryoukin_id', 'mpaymentmethod_id', 'eschedule_id', 'remarks', 'fee', 'admissiondate', 'mday_id', 'mdeliverytime_id');
				//登録する値
				$data1 = array();
				if(is_null($this->Auth->User))
					$muser_id = $this->Session->read('user.id');
				else $muser_id = $this->Auth->User('id');

				if(!isset($data[0]['Eschedule']['date1'])) $data[0]['Eschedule']['date1'] = NULL;

				$data1 = array('Msubscription' => array('firstid' => $firstid, 'muser_id' => $muser_id, 'mryoukin_id' => $Mryoukin['Mryoukin']['id'],
				'mpaymentmethod_id' => $this->Session->read('mpaymentmethod_id'), 'eschedule_id' => $Esche[$Mryoukin['Mryoukin']['id']][0],
				 'remarks' => $this->Session->read('Remarks'), 'fee' =>$Mryoukin['Mryoukin']['kng'], 'admissiondate' => $data[0]['Eschedule']['date1'],
				  'mday_id' => $Day[$Mryoukin['Mryoukin']['id']][0]));

				 if(!empty($this->Session->read('mdeliverytime_id')))
				 $data1['Msubscription']['mdeliverytime_id'] = $this->Session->read('mdeliverytime_id');

				for($ix = 1; $Mryoukin['Mryoukin']['daytimes'] >= $ix; $ix++) {
					$fields[] = 'mstudentst' . $ix . '_id';
					$data1['Msubscription']['mstudentst' . $ix . '_id'] = 10;
				}
//var_dump($Day); exit;
				$this->Msubscription->create();

				if($this->Msubscription->save($data1, false, $fields));
				if($firstid == 0) {
					$firstid = $this->Msubscription->getInsertID();
					$fields = array('firstid');
					$data1 = array('Msubscription' =>array('id' => $firstid, 'firstid' => $firstid));
					$this->Msubscription->save($data1, false, $fields);
				}

			}


			$kng1 =  (int)$kng * TAX;
			$this->Session->Write('Skng', $kng1);
			$this->Session->Write('Firstid', $firstid);

			if(!is_null($msg))
				$this->Flash->error(__($msg));


			if($this->Session->read('mpaymentmethod_id') == 2) {
				return $this->redirect(array('action' => 'card', $kng1));
			}
			return $this->redirect(array('action' => 'send'));
		}

		$this->set('title_for_layout', '申込内容確認');
		if($this->Session->read('PRE_URL'))
				$this->layout = 'tsuhan';
		else	$this->layout = 'webout';
		$wk = NULL;
		$Mryoukins = $this->Session->read('Mryoukin');

	 	foreach ($Mryoukins as $mryoukin_id)
			$wk .= ',' . $mryoukin_id;

		if(is_null($wk)) {
			$this->Flash->error(__('予期せぬエラーが発生いたしました。講座一覧に移動します。'));
			$this->redirect("../../index.php?Application#top"); // 講座一覧
		}
		$sql = 'SELECT Mryoukin.id, Mryoukin.name, Mryoukin.kng, Mryoukin.anytime_flg, Mryoukin.opday, Mryoukin.optime FROM mryoukins AS Mryoukin WHERE Mryoukin.id IN(' . substr($wk, 1) . ')';
		$this->set('Mryoukins', $this->Msubscription->query($sql));
		$sql = 'SELECT name FROM mpaymentmethods WHERE id = ' . $this->Session->read('mpaymentmethod_id');
		$data = $this->Msubscription->query($sql);


		if(!empty($this->Session->read('mdeliverytime_id'))) {
			$sql = 'SELECT name FROM mdeliverytimes WHERE id = ' . $this->Session->read('mdeliverytime_id');
			$data2 = $this->Msubscription->query($sql);
		} else $data2[0]['mdeliverytimes']['name'] = NULL;

		$this->Session->write('Mdeliverytime', $data2[0]['mdeliverytimes']['name']);

		$this->set('user', $this->Session->read('user'));
		$this->set('cardflg', $this->Session->read('user.cardflg'));
		$this->set('remarks', $this->Session->read('Remarks'));
		$this->set('mpaymentmethod', $data[0]['mpaymentmethods']['name']);
		$this->set('mdeliverytime', $data2[0]['mdeliverytimes']['name']);
		$this->set('Esche', $this->Session->read('Esche'));
		$this->set('Day', $this->Session->read('Day'));



	}

	public function view4() {

		if(! $this->Session->check('Mryoukins')) {
			$this->Flash->error(__('予期せぬエラーが発生いたしました。講座一覧に移動します。'));
			$this->redirect("../../index.php?Application#top"); // 講座一覧
		}

		if ($this->request->is(array('post', 'put'))) {
			if(isset($this->request->data['edit_submit']))
				return $this->redirect(array('action' => 'view'));
			if(isset($this->request->data['user_submit']))
				return $this->redirect(array('controller' => 'users', 'action' => 'edit'));

			$firstid = 0;

			$Mryoukins = $this->Session->read('Mryoukins');
			$Esche = $this->Session->read('Esche');
			$Day = $this->Session->read('Day');
			$msg = NULL;

			$kizon_flg = $this->Session->Read('Kizon_flg');

			if($this->Session->Read('Kizon_flg'))
				$mpaymentmethod_id = 6; //既存生徒
			else	$mpaymentmethod_id = 7; //空席待ち
			// 登録するフィールド

			foreach ($Mryoukins as $Mryoukin) {
	 			if(($Mryoukin['Mryoukin']['anytime_flg']  == FALSE) AND ($Mryoukin['Mryoukin']['pending_flg'] == FALSE)) {
					$sql = 'SELECT COUNT( Msubscription.id ) as cnt , Eschedule.*';
					$sql .= ' FROM eschedules AS Eschedule';
					$sql .= ' LEFT JOIN vsubscriptions AS Msubscription ON Eschedule.id = Msubscription.eschedule_id';
					$sql .= ' WHERE Eschedule.mryoukin_id = ' . $Mryoukin['Mryoukin']['id'] . ' AND Eschedule.id = ' .  $Esche[$Mryoukin['Mryoukin']['id']]['0'];
					$sql .= ' GROUP BY Eschedule.id';

					$data = $this->Msubscription->query($sql);
//var_dump($data);exit;

					if($data[0]['Eschedule']['deadline'] < date("Y-m-d")) {
						$msg .=  $Mryoukin['id'] . 'は締切日を過ぎました。申込みできません。';
						continue;
					}
//					if($Mryoukin['Mryoukin']['capacity'] <= $data[0][0]['cnt']){
					if($data[0]['Eschedule']['capacity'] == 0)
						$capacity = $Mryoukin['Mryoukin']['capacity'];
					else $capacity =$data[0]['Eschedule']['capacity'] ;
					if($capacity <= $data[0][0]['cnt']){

						$msg .=  $Mryoukin['id'] . 'は定員に達しました。申込みできません。';
						continue;
					}
				}

				//	締め切りと定員がOKなら受ける
				$fields = array('firstid', 'muser_id', 'mryoukin_id', 'mpaymentmethod_id', 'eschedule_id', 'remarks', 'fee', 'admissiondate', 'mday_id', 'mdeliverytime_id');
				//登録する値
				$data1 = array();
				if(is_null($this->Auth->User))
					$muser_id = $this->Session->read('user.id');
				else $muser_id = $this->Auth->User('id');
				if(!isset($data[0]['Eschedule']['date1'])) $data[0]['Eschedule']['date1'] = NULL;

				$data1 = array('Msubscription' => array('firstid' => $firstid, 'muser_id' => $muser_id, 'mryoukin_id' => $Mryoukin['Mryoukin']['id'],
				'mpaymentmethod_id' => $mpaymentmethod_id, 'eschedule_id' => $Esche[$Mryoukin['Mryoukin']['id']][0],
				 'remarks' => $this->Session->read('Remarks'), 'fee' =>$Mryoukin['Mryoukin']['kng'], 'admissiondate' => $data[0]['Eschedule']['date1'], 'mday_id' =>$Day[$Mryoukin['Mryoukin']['id']][0], 'mdeliverytime_id' =>$mdeliverytime_id));

				for($ix = 1; $Mryoukin['Mryoukin']['daytimes'] >= $ix; $ix++) {
					$fields[] = 'mstudentst' . $ix . '_id';
					$data1['Msubscription']['mstudentst' . $ix . '_id'] = 10;
				}
//var_dump($fields, $data1); exit;

				$this->Msubscription->create();

				$this->Msubscription->save($data1, false, $fields);
				if($firstid == 0) {
					$firstid = $this->Msubscription->getInsertID();
					$fields = array('firstid');
					$data1 = array('Msubscription' =>array('id' => $firstid, 'firstid' => $firstid));
					$this->Msubscription->save($data1, false, $fields);
				}

			}


			$this->Session->Write('Firstid', $firstid);

			$this->Session->Write('Kizon_flg', $kizon_flg);

			if(!is_null($msg))
				$this->Flash->error(__($msg));

			return $this->redirect(array('action' => 'send2'));
		}

		$this->set('title_for_layout', '申込内容確認');
		if($this->Session->read('PRE_URL'))
				$this->layout = 'tsuhan';
		else	$this->layout = 'webout';
		$wk = NULL;
		$Mryoukins = $this->Session->read('Mryoukin');

	 	foreach ($Mryoukins as $mryoukin_id)
			$wk .= ',' . $mryoukin_id;

//var_dump($wk);exit;
		if(is_null($wk)) {
			$this->Flash->error(__('予期せぬエラーが発生いたしました。講座一覧に移動します。'));
			$this->redirect("../../index.php?Application#top"); // 講座一覧
		}
		$sql = 'SELECT Mryoukin.id, Mryoukin.name, Mryoukin.kng, Mryoukin.anytime_flg, Mryoukin.opday, Mryoukin.optime FROM mryoukins AS Mryoukin WHERE Mryoukin.id IN(' . substr($wk, 1) . ')';
		$this->set('Mryoukins', $this->Msubscription->query($sql));
		$sql = 'SELECT name FROM mpaymentmethods WHERE id = ' . 7;
		$data = $this->Msubscription->query($sql);

//		var_dump($data);exit;
		$this->set('user', $this->Session->read('user'));
		$this->set('cardflg', $this->Session->read('user.cardflg'));
		$this->set('remarks', $this->Session->read('Remarks'));
		$this->set('mpaymentmethod', $data[0]['mpaymentmethods']['name']);
		$this->set('Esche', $this->Session->read('Esche'));
		$this->set('Day', $this->Session->read('Day'));
		/*
		if(! $this->Session->read('cardflg')) {
			$sql = 'UPDATE musers SET cardflg = 1 WHERE id = ' .  $this->Session->read('user_id');
			$data1 = $this->User->query($sql);
		}
		*/


	}

	public function send() {
		if(! $this->Session->check('Firstid')) {
			$this->redirect(array('action' => 'send_err'));
		}
		//予約待ちのステータスがある。
		if($this->Session->read('mpaymentmethod_id') == 2) $mworkst_id = 40;
		else $mworkst_id = 20;
		if($this->Session->read('TANDOKU_KBN') == 2)
			$mworkst_id -= 5;
		$dataM = $this->Msubscription->find('all',
			array('conditions'=> array('Msubscription.Firstid' => $this->Session->read('Firstid')), 'recursive'=>-1));
	 	foreach ($dataM as $wk){
			//登録する値
			$data = array('Msubscription' => array('id' => $wk['Msubscription']['id'], 'mworkst_id' => $mworkst_id));
			// 登録するフィールド
			$fields = array('mworkst_id');
			$this->Msubscription->save($data, false, $fields);
			if($this->Session->read('mpaymentmethod_id') == 2) {
				$date = new DateTime();
				//登録する値
				$data1 = array('Msubscription' => array('id ' =>  $wk['Msubscription']['id'], 'date1' => $date->format('Y-m-d H:i:s')));
				// 登録するフィールド
				$fields = array('date1');
				$this->Msubscription->save($data1, false, $fields);
			}
		}
		$Msubscriptions = $this->Msubscription->find('all',
				array('conditions'=> array('Msubscription.Firstid' => $this->Session->read('Firstid'))));
		$mail_temp1 =  $this->Mail->Reception($Msubscriptions, $this->Session->read('TANDOKU_KBN'));


		if($this->Session->read('mpaymentmethod_id') == 1)
			$mail_temp1 .=  $this->Mail->Reception_bank($this->Session->read('Skng'), $Msubscriptions[0]);
		if($this->Session->read('mpaymentmethod_id') == 5)
			$mail_temp1 .=  $this->Mail->Reception_cach($this->Session->read('Skng'));

		$user = $this->Session->read('user');


		$wk = NULL;
		if($this->Session->read('TANDOKU_KBN') == 2)
			$wk['m_subject'] = $user['name']. '様 受講予約申込ありがとうございます。';
		else $wk['m_subject'] = $user['name']. '様 受講申込ありがとうございます。';
		$wk['mail_temp'] =  $mail_temp1;
		$wk['mail_temp'] .= $this->Mail->mail_fooder($Msubscriptions[0]['Mdivision'],$Msubscriptions);
		$this->Session->write('wk', $wk);
		$this->Mail->send_mail($user['usrmail'],  $Msubscriptions[0]['Mdivision']);
		$this->Session->delete('Firstid');

		$this->redirect(array('action' => 'send_end'));
	}


	public function send2() {
		if(! $this->Session->check('Firstid')) {
			$this->redirect(array('action' => 'send_err'));
		}
		if($this->Session->Read('Kizon_flg'))
			 $mworkst_id = 40;
	 	else $mworkst_id = 15;

		$dataM = $this->Msubscription->find('all',
			array('conditions'=> array('Msubscription.Firstid' => $this->Session->read('Firstid')), 'recursive'=>-1));

	 	foreach ($dataM as $wk){
			//登録する値
			$data = array('Msubscription' => array('id' => $wk['Msubscription']['id'], 'mworkst_id' => $mworkst_id));
			// 登録するフィールド
			$fields = array('mworkst_id');
			$this->Msubscription->save($data, false, $fields);
		}

		$Msubscriptions = $this->Msubscription->find('all',
				array('conditions'=> array('Msubscription.Firstid' => $this->Session->read('Firstid'))));
	var_dump($Msubscriptions);
			if($this->Session->Read('Kizon_flg')) {
				$wkti = '受講申込';
				$mail_temp1 =  $this->Mail->Reception($Msubscriptions);
			} else {
				$wkti = '空席待ち';
				$mail_temp1 =  $this->Mail->Reservation($Msubscriptions);
			}


		$user = $this->Session->read('user');
		$wk = NULL;
		$wk['m_subject'] = $user['name']. '様 '. $wkti .'ありがとうございます。';
		$wk['mail_temp'] =  $mail_temp1;
		$wk['mail_temp'] .= $this->Mail->mail_fooder($Msubscriptions[0]['Mdivision'],$Msubscriptions);
		$this->Session->write('wk', $wk);
		$this->Mail->send_mail($user['usrmail'],  $Msubscriptions[0]['Mdivision']);
		$this->Session->delete('Firstid');

		$this->redirect(array('action' => 'send_end'));
	}

	public function send_end() {

		$this->set('hostname', $_SERVER['SERVER_NAME']);

		$this->layout = 'yoyakuform';


	}
	public function send_err() {

		$this->set('hostname', $_SERVER['SERVER_NAME']);

		$this->layout = 'yoyakuform';


	}
}
