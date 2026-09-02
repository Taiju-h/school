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
class EfilelogsController extends AppController {

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
		$this->Efilelogs->recursive = 0;
		$this->set('Efilelogs', $this->Paginator->paginate());
	}
 }