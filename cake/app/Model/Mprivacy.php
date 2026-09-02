<?php
App::uses('AppModel', 'Model');
/**
 * Mprivacy Model
 *
 * @property Mbank $Mbank
 * @property Mkanteishi $Mkanteishi
 */
class Mprivacy extends AppModel {

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
	public $useTable = 'mprivacy';

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
		'mlecturer_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
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
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Mbank' => array(
			'className' => 'Mbank',
			'foreignKey' => 'mbank_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Maccounttype' => array(
			'className' => 'Maccounttype',
			'foreignKey' => 'maccounttype_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Mlecturer' => array(
			'className' => 'Mlecturer',
			'foreignKey' => 'mlecturer_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

}