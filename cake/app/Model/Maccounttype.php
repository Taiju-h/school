<?php
App::uses('AppModel', 'Model');
/**
 * Maccounttype Model
 *
 * @property Mprivacy $Mprivacy
 */
class Maccounttype extends AppModel {

/**
 * Use database config
 *
 * @var string
 */
	public $useDbConfig = 'school';

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'maccounttype';

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Mprivacy' => array(
			'className' => 'Mprivacy',
			'foreignKey' => 'maccounttype_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

}
