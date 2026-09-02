<?php
App::uses('AppController', 'Controller');
/**
 * Eoshirases Controller
 *
 * @property Eoshirase $Eoshirase
 * @property PaginatorComponent $Paginator
 */
class WebsiteController extends AppController {
	var $uses = null;

	public function beforeFilter(){
			
		//ログインなしでアクセス可能なページを列挙
		//
		$this->Auth->allow(); 
	}

/**
 * index method
 *
 * @return void
 */
	public function index($kbn = NULL) {
	

		$this->layout = 'webout';
		
	}

}





}