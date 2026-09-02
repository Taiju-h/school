<?php
App::uses('AppModel', 'Model');
/**
 * Mryoukin Model
 *
 * @property Msubscription $Msubscription
 * @property Muser $Muser
 */
class Mstudentst2 extends AppModel {
/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'mstudentsts';


/**
 * Use database config
 *
 * @var string
 */
	public $useDbConfig = 'school';

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Msubscription' => array(
			'className' => 'Msubscription',
			'foreignKey' => 'mstudentst2_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
	);

}