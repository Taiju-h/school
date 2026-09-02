<?php
App::uses('AppController', 'Controller');
App::import('Vendor', 'phpqrcode/qrlib');

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
	public $components = array('Paginator', 'Flash', 'Session');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Mfile->recursive = 0;
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

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {

//var_dump($this->request->data['Mfile']['files'][0]);exit;
/* $image['name'];// もとのファイル名
$image['type'];// ファイルのMIME型
$image['size'];/s/ ファイルのサイズ。バイト単位。
$image['tmp_name']; // アップロードされたファイルの、テンポラリファイル名
*/
//var_dump($this->request->data['Mfile']['files2'][0]['tmp_name']);exit;
			$path = str_replace('/cake/', '/Public/',IMAGES);
			$path = str_replace('/img/', '/files/',$path);
			//str_replace('/cake/', '/Public/', $path);
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
				$filename = sprintf("%05d%s", $this->Mfile->getInsertID(), '.cgi');
				move_uploaded_file($this->request->data['Mfile']['files'][0]['tmp_name'], $path . DS . $filename);
			
					$this->Flash->success(__('The mfile has been saved.'));
				//	return;
					return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The mfile could not be saved. Please, try again.'));
			}
		}
		$mcoursenames = $this->Mfile->Mcoursename->find('list');
		$this->set(compact('mcoursenames'));

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
//var_dump($this->request->data['Mfile']['thumbnail']);exit;

			if($this->request->data['Mfile']['thumbnail']['tmp_name'] == '') {
				unset($this->request->data['Mfile']['thumbnail']);
			} else {
				$this->request->data['Mfile']['thumbnail'] = file_get_contents($this->request->data['Mfile']['thumbnail']['tmp_name']);
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
		$mcoursenames = $this->Mfile->Mcoursename->find('list');
		$this->set(compact('mcoursenames'));
	}
	public function edit2($id = null) {
		$this->Mfile->id = $id;
		if (!$this->Mfile->exists()) {
			throw new NotFoundException(__('Invalid mfile'));
		}
		if ($this->request->is(array('post', 'put'))) {
//var_dump($this->request->data['Mfile']['thumbnail']);exit;
			$path = str_replace('/cake/', '/Public/',IMAGES);
			$path = str_replace('/img/', '/files/',$path);
			//str_replace('/cake/', '/Public/', $path);
			$wk = explode('.', $this->request->data['Mfile']['files'][0]['name']);
			$this->request->data['Mfile']['filesize'] = $this->request->data['Mfile']['files'][0]['size'];
			$this->request->data['Mfile']['filetype'] = $wk[1];  

			if ($this->Mfile->save($this->request->data)) {
				$filename = sprintf("%05d%s", $id, '.cgi');
				move_uploaded_file($this->request->data['Mfile']['files'][0]['tmp_name'], $path . DS . $filename);
				$this->Flash->success(__('The mfile has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The mfile could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Mfile.' . $this->Mfile->primaryKey => $id));
			$this->request->data = $this->Mfile->find('first', $options);
		}
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
