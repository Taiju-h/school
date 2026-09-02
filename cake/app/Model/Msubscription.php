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
class Msubscription extends AppModel {

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
		'mryoukin_id' => array(
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
		'fee' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'paidkng' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'mdivision_id' => array(
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
		'Mryoukin' => array(
			'className' => 'Mryoukin',
			'foreignKey' => 'mryoukin_id',
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
		'Mstudentst1' => array(
			'className' => 'Mstudentst1',
			'foreignKey' => 'mstudentst1_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Mstudentst2' => array(
			'className' => 'Mstudentst2',
			'foreignKey' => 'mstudentst2_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Mstudentst3' => array(
			'className' => 'Mstudentst3',
			'foreignKey' => 'mstudentst3_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Mdivision' => array(
			'className' => 'Mdivision',
			'foreignKey' => 'mdivision_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
 		'Mintroduction' => array(
			'className' => 'Mintroduction',
			'foreignKey' => 'mintroduction_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Mday' => array(
			'className' => 'Mday',
			'foreignKey' => 'mday_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Sale' => array(
			'className' => 'sale',
			'foreignKey' => 'sales_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
