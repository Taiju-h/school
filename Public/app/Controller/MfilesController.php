<?php
App::uses('AppController', 'Controller');
/**
 * Mfiles Controller
 *
 * @property Mfile $Mfile
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class MfilesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Flash', 'Session', 'RequestHandler');

 	public function beforeFilter(){

		$this->Auth->allow('tindex', 'findex', 'oview3', 'oview', 'oview5');
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Mfile->recursive = 0;
		$options = array(
			//フィールド名の配列
		  'fields' => array('Mfile.title', 'Mfile.filesize', 
							'Mfile.filetype','Mfile.limit_flg', 'Mfile.disp_flg',  'Mkbn1.name' ,'Mkbn3.name', 'Mkbn2.name'), 
 		);

		$this->set('mfiles', $this->Paginator->paginate());
	}
	

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Mfile->exists($id)) {
			throw new NotFoundException(__('Invalid mfile'));
		}
		$options = array('conditions' => array('Mfile.' . $this->Mfile->primaryKey => $id));
		$this->set('mfile', $this->Mfile->find('first', $options));
	}
	
	public function oview($id = null, $dummy = null) {

		if($id <> $dummy) {
				$this->Flash->error(__('システム・エラーもしくは不正アクセス 生徒の場合はご連絡ください'));
				exit;
		
		}

		if (!$this->Mfile->exists($id)) {
			throw new NotFoundException(__('Invalid mfile'));
		}
		$options = array('conditions' => array('Mfile.' . $this->Mfile->primaryKey => $id));
		$mfile = $this->Mfile->find('first', $options);

		mt_srand((double) microtime() * 100000);//数値をよりランダムにするためのおまじない
		
		$mfile['Mfile']['filename'] = mt_rand() . '.' . $mfile['Mfile']['filetype'];
		$files = sprintf("%05d%s",  $mfile['Mfile']['id'], '.cgi');
		$files = str_replace('/img/', '/files/', IMAGES . $files);
		$file2s = WWW_ROOT . 'tmp/' . $mfile['Mfile']['filename'];
		copy($files, $file2s); 
				
		$this->set('mfile', $mfile);
		$this->set('hostname', $_SERVER['SERVER_NAME']);
//		if($mfile['Mfile']['filetype'] == 'pdf') {

			$this->autoLayout = false;
		    $this->autoRender = false;
			$dl = 'https://' .  $_SERVER['SERVER_NAME'] . '/Public/tmp/' . $mfile['Mfile']['filename'];
	  		$this->response->header('Location', $dl);

/*
			// 読み込むPDFファイルを指定
			 
			// PDFを出力する
			header("Content-Disposition: attachment; filename=download.pdf");			 
			// ファイルを読み込んで出力
			readfile($file2s);
			exit();
*/	
//		}	
	}
	public function oview3($id = null) {
		if (!$this->Mfile->exists($id)) {
			throw new NotFoundException(__('Invalid mfile'));
		}
		$options = array('conditions' => array('Mfile.' . $this->Mfile->primaryKey => $id));
		$mfile = $this->Mfile->find('first', $options);

		$today = date("Y-m-d H:i:s");

		if($mfile['Mfile']['taboo_flg']) {
			$this->loadModel('Efilelog');
			$this->Efilelog->create();
			//登録する値
			$data = array('Efilelog' => array('date' =>$today, 'muser_id' => $auth['User']['id'], 'ipadd' => $this->request->clientIp(false), 'mfile_id' =>$mfile['Mfile']['id'], 'mfile_title' => $mfile['Mfile']['title'], 'mfilelogsts_id' => 999));
			// 登録するフィールド
			$fields = array('date', 'muser_id', 'ipadd', 'mfile_id', 'mfile_title', 'mfilelogsts_id');
			$this->Efilelog->save($data, false, $fields);
			$this->redirect('httpss://school.heartf.com/err_rt');
			exit;
		}

		

// var_dump($mfile['Mfile']['limit_flg']);exit;
		if($mfile['Mfile']['limit_flg']) {
	//	$auth = 
//			if(! $this->Session->check($Auth)) {
		$auth =$this->Session->read('Auth');
//var_dump($auth);exit;
		if(empty($auth['User']['id'])) {
				$this->Flash->error(__('このファイルは会員しか見ることが出来ません。ログインをしてください。'));
		//カウンター追加
			$this->loadModel('Efilelog');
			$this->Efilelog->create();
			//登録する値
			$data = array('Efilelog' => array('date' =>$today, 'muser_id' => NULL, 'ipadd' => $this->request->clientIp(false), 'mfile_id' =>$mfile['Mfile']['id'], 'mfile_title' => $mfile['Mfile']['title'], 'mfilelogsts_id' => 3));
			// 登録するフィールド
			$fields = array('date', 'muser_id', 'ipadd', 'mfile_id', 'mfile_title', 'mfilelogsts_id');
			$this->Efilelog->save($data, false, $fields);

	//			$this->Session->write('model', 'Mfiles');
	//			$this->Session->write('act', 'oview2');
				$this->Session->write('id', $id);
			//	return $this->redirect(array('controller' => 'users', 'action' => 'login2', 'Mfiles', 'oview2', $id));

				$this->redirect(array('controller' => 'users', 'action' => 'login2', $id));
			}
		}
		$this->set('id', $id);
		$this->layout = 'oview3_bk';

		

	}
	public function oview5($id = null) {
		if (!$this->Mfile->exists($id)) {
			throw new NotFoundException(__('Invalid mfile'));
		}

		$today = date("Y-m-d H:i:s");

		$options = array('conditions' => array('Mfile.' . $this->Mfile->primaryKey => $id));
		$mfile = $this->Mfile->find('first', $options);
		if($mfile['Mfile']['taboo_flg']) {
			$this->loadModel('Efilelog');
			$this->Efilelog->create();
			//登録する値
			$data = array('Efilelog' => array('date' =>$today, 'muser_id' => $auth['User']['id'], 'ipadd' => $this->request->clientIp(false), 'mfile_id' =>$mfile['Mfile']['id'], 'mfile_title' => $mfile['Mfile']['title'], 'mfilelogsts_id' => 999));
			// 登録するフィールド
			$fields = array('date', 'muser_id', 'ipadd', 'mfile_id', 'mfile_title', 'mfilelogsts_id');
			$this->Efilelog->save($data, false, $fields);
//			$this->redirect('httpss://school.heartf.com/err_rt');
			exit;
		}
// var_dump($mfile['Mfile']['limit_flg']);exit;
		if($mfile['Mfile']['limit_flg']) {
	//	$auth = 
			$auth =$this->Session->read('Auth');
	/*　ユーザが管理者の場合スルー*/
			if(!$auth['User']['adminflg']) {
				
				$sql = 'SELECT mcoursename_id FROM msubscriptions, mryoukins WHERE msubscriptions. mryoukin_id = mryoukins.id AND mworkst_id in(46, 50, 55) AND muser_id = ';
				$sql .= $auth['User']['id'] . ' AND mryoukins.mcoursename_id = '. $mfile['Mfile']['mcoursename_id'];
				$data = $this->Mfile->query($sql);

				if(empty($data)) {
//				
					$sql = 'SELECT msubscriptions.mryoukin_id FROM msubscriptions,  mryoukins,  msums  WHERE msubscriptions.mryoukin_id = mryoukins.id AND ';
					$sql .= ' mworkst_id in(46, 50, 52, 55) AND muser_id = ' . $auth['User']['id'] . ' AND  msums.mcoursename2_id = '. $mfile['Mfile']['mcoursename_id'];
					$data = $this->Mfile->query($sql);

					if(empty($data)) {
						//カウンター追加
						$this->loadModel('Efilelog');
						$this->Efilelog->create();
						//登録する値
						$data = array('Efilelog' => array('date' =>$today, 'muser_id' => $auth['User']['id'], 'ipadd' => $this->request->clientIp(false), 'mfile_id' =>$mfile['Mfile']['id'], 'mfile_title' => $mfile['Mfile']['title'], 'mfilelogsts_id' => 2));
						// 登録するフィールド
						$fields = array('date', 'muser_id', 'ipadd', 'mfile_id', 'mfile_title', 'mfilelogsts_id');
						$this->Efilelog->save($data, false, $fields);
					
						printf("%s%s\n", $sql, 'このファイルは講座受講生徒のみ閲覧できます。');
						exit;
					}
				}
			}
		}
		//カウンター追加
			$this->loadModel('Efilelog');
		$this->Efilelog->create();
		//登録する値
		$data = array('Efilelog' => array('date' =>$today, 'muser_id' => $auth['User']['id'], 'ipadd' => $this->request->clientIp(false), 'mfile_id' =>$mfile['Mfile']['id'], 'mfile_title' => $mfile['Mfile']['title'], 'mfilelogsts_id' => 1));
		// 登録するフィールド
		$fields = array('date', 'muser_id', 'ipadd', 'mfile_id', 'mfile_title', 'mfilelogsts_id');
		$this->Efilelog->save($data, false, $fields);

		mt_srand((double) microtime() * 100000);//数値をよりランダムにするためのおまじない
		
		$mfile['Mfile']['filename'] = mt_rand() . '.' . $mfile['Mfile']['filetype'];
		$files = sprintf("%05d%s",  $mfile['Mfile']['id'], '.cgi');
	//	$files = IMAGES . $files;
		$files = str_replace('/img/', '/files/', IMAGES . $files);
		$file2s = WWW_ROOT . 'tmp/' . $mfile['Mfile']['filename'];
	//	var_dump($file2s);exit;
		copy($files, $file2s); 
				
		$this->set('mfile', $mfile);
		$this->set('hostname', $_SERVER['SERVER_NAME']);
//		if($mfile['Mfile']['filetype'] == 'pdf') {

			$this->autoLayout = false;
		    $this->autoRender = false;
			$dl = 'https://' .  $_SERVER['SERVER_NAME'] . '/Public/tmp/' . $mfile['Mfile']['filename'];
	  		$this->response->header('Location', $dl);

/*
			// 読み込むPDFファイルを指定
			 
			// PDFを出力する
			header("Content-Disposition: attachment; filename=download.pdf");			 
			// ファイルを読み込んで出力
			readfile($file2s);
			exit();
*/	
//		}	
	}
		public function oview_taboo($id = null) {

		$auth =$this->Session->read('Auth');
		if(!($auth['User']['taboo_flg'])) {	
				$this->Flash->error(__('禁断の書を閲覧する権限がありません。権限を持っている場合には管理者にご連絡ください。'));
			return $this->redirect(array('controller' => 'Users','action' => 'login_taboo'));
		}
		if (!$this->Mfile->exists($id)) {
			throw new NotFoundException(__('Invalid mfile'));
		}

		$today = date("Y-m-d H:i:s");

		$options = array('conditions' => array('Mfile.' . $this->Mfile->primaryKey => $id));
		$mfile = $this->Mfile->find('first', $options);
//var_dump($mfile['Mfile']['id']);exit;
		if($mfile['Mfile']['limit_flg']) {
	//	$auth = 
			$auth =$this->Session->read('Auth');
	/*　ユーザが管理者の場合スルー*/
			if(!$auth['User']['adminflg']) {



				$sql = 'SELECT mcoursename_id FROM msubscriptions, mryoukins WHERE msubscriptions. mryoukin_id = mryoukins.id AND  mworkst_id = 50 AND muser_id = ' . $auth['User']['id'] . ' AND mryoukins.mcoursename_id = '. $mfile['Mfile']['mcoursename_id'];
				$data = $this->Mfile->query($sql);
				if(empty($data)) {
				
					$sql = 'SELECT msubscriptions.mryoukin_id FROM msubscriptions,  mryoukins,  msums  WHERE msubscriptions.mryoukin_id = mryoukins.id AND ';
					$sql .= ' mworkst_id in(46, 50, 52, 55) AND muser_id = ' . $auth['User']['id'] . ' AND  msums.mcoursename2_id = '. $mfile['Mfile']['mcoursename_id'];




				
		/*		$sql = 'SELECT mryoukin_id FROM msubscriptions WHERE mworkst_id = 50 AND muser_id = ' . $auth['User']['id'] . ' AND mryoukin_id = '. $mfile['Mfile']['mryoukin_id'];
				$data = $this->Mfile->query($sql);
				if(empty($data)) {
				
					$sql = 'SELECT msubscriptions.mryoukin_id FROM msubscriptions  INNER JOIN msums ON msubscriptions.mryoukin_id  = msums.mryoukin_id ';
					$sql .= ' WHERE mworkst_id = 50 AND muser_id = ' . $auth['User']['id'] . ' AND  mryoukin2_id = '. $mfile['Mfile']['mryoukin_id'];

					$sql = 'SELECT msubscriptions.mryoukin_id FROM msubscriptions,  mryoukins,  msums  WHERE msubscriptions.mryoukin_id = mryoukins.id AND ';
					$sql .= ' mworkst_id in(50, 52, 55) AND muser_id = ' . $auth['User']['id'] . ' AND  msums.mcoursename2_id = '. $mfile['Mfile']['mcoursename_id'];

*/
					$data = $this->Mfile->query($sql);
					if(empty($data)) {
						//カウンター追加
						$this->loadModel('Efilelog');
						$this->Efilelog->create();
						//登録する値
						$data = array('Efilelog' => array('date' =>$today, 'muser_id' => $auth['User']['id'], 'ipadd' => $this->request->clientIp(false), 'mfile_id' =>$mfile['Mfile']['id'], 'mfile_title' => $mfile['Mfile']['title'], 'mfilelogsts_id' => 2));
						// 登録するフィールド
						$fields = array('date', 'muser_id', 'ipadd', 'mfile_id', 'mfile_title', 'mfilelogsts_id');
						$this->Efilelog->save($data, false, $fields);
					
						printf("%s\n", 'このファイルは講座受講生徒のみ閲覧できます。');
						exit;
					}
				}
			}
		}
		//カウンター追加
			$this->loadModel('Efilelog');
		$this->Efilelog->create();
		//登録する値
		$data = array('Efilelog' => array('date' =>$today, 'muser_id' => $auth['User']['id'], 'ipadd' => $this->request->clientIp(false), 'mfile_id' =>$mfile['Mfile']['id'], 'mfile_title' => $mfile['Mfile']['title'], 'mfilelogsts_id' => 1));
		// 登録するフィールド
		$fields = array('date', 'muser_id', 'ipadd', 'mfile_id', 'mfile_title', 'mfilelogsts_id');
		$this->Efilelog->save($data, false, $fields);

		mt_srand((double) microtime() * 100000);//数値をよりランダムにするためのおまじない
		
		$mfile['Mfile']['filename'] = mt_rand() . '.' . $mfile['Mfile']['filetype'];
		$files = sprintf("%05d%s",  $mfile['Mfile']['id'], '.cgi');
	//	$files = IMAGES . $files;
		$files = str_replace('/img/', '/files/', IMAGES . $files);
		$file2s = WWW_ROOT . 'tmp/' . $mfile['Mfile']['filename'];
	//	var_dump($file2s);exit;
		copy($files, $file2s); 
				
		$this->set('mfile', $mfile);
		$this->set('hostname', $_SERVER['SERVER_NAME']);
//		if($mfile['Mfile']['filetype'] == 'pdf') {

			$this->autoLayout = false;
		    $this->autoRender = false;
			$dl = 'https://' .  $_SERVER['SERVER_NAME'] . '/Public/tmp/' . $mfile['Mfile']['filename'];
	  		$this->response->header('Location', $dl);

/*
			// 読み込むPDFファイルを指定
			 
			// PDFを出力する
			header("Content-Disposition: attachment; filename=download.pdf");			 
			// ファイルを読み込んで出力
			readfile($file2s);
			exit();
*/	
//		}	
	}

	public function oview4($id = null) {
		if (!$this->Mfile->exists($id)) {
			throw new NotFoundException(__('Invalid mfile'));
		}
		$options = array('conditions' => array('Mfile.' . $this->Mfile->primaryKey => $id));
		$mfile = $this->Mfile->find('first', $options);
// var_dump($mfile['Mfile']['limit_flg']);exit;
		if($mfile['Mfile']['limit_flg']) {
	//	$auth = 
//			if(! $this->Session->check($Auth)) {
		$auth =$this->Session->read('Auth');
//var_dump($auth);exit;
		if(empty($auth['User']['id'])) {
				$this->Flash->error(__('このファイルは会員しか見ることが出来ません。ログインをしてください。'));
	//			$this->Session->write('model', 'Mfiles');
	//			$this->Session->write('act', 'oview2');
				$this->Session->write('id', $id);
			//	return $this->redirect(array('controller' => 'users', 'action' => 'login2', 'Mfiles', 'oview2', $id));
				$this->redirect(array('controller' => 'users', 'action' => 'login2', $id));
			}
		}
/*　申込情報を見る		
		$sql = 'SELECT mryoukin_id FROM msubscriptions WHERE mwortst_id = 50 AND muser_id = ' . $auth['User']['id']
		$data = $this->Mfile->query($sql);
		if(empty($data)) {
			$this->Flash->error(__('このファイルを講座受講者のみ閲覧できます。'));
			$this->redirect(array('controller' => 'Massociations', 'action' => 'tindex'));
*/
/*　ユーザが管理者の場合スルー*/
		if(!$auth['User']['adminflg']) {
			
			$sql = 'SELECT mryoukin_id FROM msubscriptions WHERE mworkst_id in(46, 50, 55) AND muser_id = ' . $auth['User']['id'] . ' AND mryoukin_id = '. $mfile['Mfile']['mryoukin_id'];
			$data = $this->Mfile->query($sql);
			if(empty($data)) {
			
				$sql = 'SELECT msubscriptions.mryoukin_id FROM msubscriptions  INNER JOIN msums ON msubscriptions.mryoukin_id  = msums.mryoukin_id ';
				$sql .= ' WHERE mworkst_id in(46, 50, 52, 55) AND muser_id = ' . $auth['User']['id'] . ' AND  mryoukin2_id = '. $mfile['Mfile']['mryoukin_id'];
				$data = $this->Mfile->query($sql);
				if(empty($data)) {
					printf("%s\n", 'このファイルは講座受講生徒のみ閲覧できます。');
					exit;
				}
			}
		}
		
		mt_srand((double) microtime() * 100000);//数値をよりランダムにするためのおまじない
		
		$mfile['Mfile']['filename'] = mt_rand() . '.' . $mfile['Mfile']['filetype'];
		$files = sprintf("%05d%s",  $mfile['Mfile']['id'], '.cgi');
	//	$files = IMAGES . $files;
		$files = str_replace('/img/', '/files/', IMAGES . $files);
		$file2s = WWW_ROOT . 'tmp/' . $mfile['Mfile']['filename'];
	//	var_dump($file2s);exit;
		copy($files, $file2s); 
				
		$this->set('mfile', $mfile);
		$this->set('hostname', $_SERVER['SERVER_NAME']);
//		if($mfile['Mfile']['filetype'] == 'pdf') {

			$this->autoLayout = false;
		    $this->autoRender = false;
			$dl = 'https://' .  $_SERVER['SERVER_NAME'] . '/Public/tmp/' . $mfile['Mfile']['filename'];
	  		$this->response->header('Location', $dl);

/*
			// 読み込むPDFファイルを指定
			 
			// PDFを出力する
			header("Content-Disposition: attachment; filename=download.pdf");			 
			// ファイルを読み込んで出力
			readfile($file2s);
			exit();
*/	
//		}	
	}

			
/**
 * add method
 *
 * @return void
 */
	public function video($filetype = NULL) {
		//response.addHeader("Content-disposition", "inline; filename=(PDFファイル名).pdf")
		$this->set('filetype', $filetype);
 	}
	public function add() {
		if ($this->request->is('post')) {

//var_dump($this->request->data['Mfile']['files'][0]);exit;
/* $image['name'];// もとのファイル名
$image['type'];// ファイルのMIME型
$image['size'];/s/ ファイルのサイズ。バイト単位。
$image['tmp_name']; // アップロードされたファイルの、テンポラリファイル名
*/
//var_dump($this->request->data['Mfile']['files2'][0]['tmp_name']);exit;
			$path = IMAGES;
			$wk = explode('.', $this->request->data['Mfile']['files'][0]['name']);
			$this->request->data['Mfile']['filesize'] = $this->request->data['Mfile']['files'][0]['size'];
			$this->request->data['Mfile']['filetype'] = $wk[1];  
			$filename = $path . DS . "temp";
		    move_uploaded_file($this->request->data['Mfile']['files2'][0]['tmp_name'], $filename);
			$handle = fopen($filename, "r");
			$this->request->data['Mfile']['thumbnail'] = fread($handle, 65000);
			fclose($handle);			
		//var_dump($path . DS . $this->request->data['Mfile']['files'][0]['name']);exit;
			$this->Mfile->create();
			if ($this->Mfile->save($this->request->data)) {
				//$filename = sprintf("%03d%03d%05d%s%s", $this->request->data['Mfile']['mkbn1_id'], $this->request->data['Mfile']['mkbn2_id'], $this->Mfile->getInsertID(), '.', $this->request->data['Mfile']['filetype']);
				$filename = sprintf("%05d%s", $this->Mfile->getInsertID(), '.cgi');
				move_uploaded_file($this->request->data['Mfile']['files'][0]['tmp_name'], $path . DS . $filename);
			
					$this->Flash->success(__('The mfile has been saved.'));
				//	return;
					return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The mfile could not be saved. Please, try again.'));
			}
		}
		$mkbn1s = $this->Mfile->Mkbn1->find('list');
		$mkbn2s = $this->Mfile->Mkbn2->find('list');
		$mkbn3s = $this->Mfile->Mkbn3->find('list');
		$this->set(compact('mkbn1s', 'mkbn2s', 'mkbn3s'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->Mfile->id = $id;
		if (!$this->Mfile->exists()) {
			throw new NotFoundException(__('Invalid mfile'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if($this->request->data['Mfile']['files2'][0]['tmp_name'] != '') {
				$path = IMAGES;
				$filename = $path . DS . "temp";
			    move_uploaded_file($this->request->data['Mfile']['files2'][0]['tmp_name'], $filename);
				$handle = fopen($filename, "r");
				$this->request->data['Mfile']['thumbnail'] = fread($handle, 65000);
			}
			if ($this->Mfile->save($this->request->data)) {
				$this->Flash->success(__('The mfile has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The mfile could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Mfile.' . $this->Mfile->primaryKey => $id));
			$this->request->data = $this->Mfile->find('first', $options);
		}
		$mkbn1s = $this->Mfile->Mkbn1->find('list');
		$mkbn2s = $this->Mfile->Mkbn2->find('list');
		$mkbn3s = $this->Mfile->Mkbn3->find('list');
		$this->set(compact('mkbn1s', 'mkbn2s', 'mkbn3s'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Mfile->id = $id;
		if (!$this->Mfile->exists()) {
			throw new NotFoundException(__('Invalid mfile'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Mfile->delete()) {
			$this->Flash->success(__('The mfile has been deleted.'));
		} else {
			$this->Flash->error(__('The mfile could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
