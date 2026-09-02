<?php
App::uses('AppModel', 'Model');
/**
 * Msubscription Model
 *
 * @property Muser $Muser
 * @property Mryoukin $Mryoukin
 * @property Mpaymentmethod $Mpaymentmethod
 * @property Mdivision $Mdivision
 */
class Vssubscription extends AppModel {

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
	public $displayField = 'id';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'muser_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'mpaymentmethod_id' => array(
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

	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Muser' => array(
			'className' => 'Muser',
			'foreignKey' => 'muser_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Mpaymentmethod' => array(
			'className' => 'Mpaymentmethod',
			'foreignKey' => 'mpaymentmethod_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Mworkst' => array(
			'className' => 'Mworkst',
			'foreignKey' => 'mworkst_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);
}
