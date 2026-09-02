<?php
App::uses('AppModel', 'Model');
/**
 * Massociation Model
 *
 * @property Mfile $Mfile
 * @property Mkbn1 $Mkbn1
 * @property Mkbn2 $Mkbn2
 * @property Mkbn3 $Mkbn3
 */
class Massociation extends AppModel {

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
		'mfile_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'mkbn1_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'updateid' => array(
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
		'Mfile' => array(
			'className' => 'Mfile',
			'foreignKey' => 'mfile_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Mkbn1' => array(
			'className' => 'Mkbn1',
			'foreignKey' => 'mkbn1_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Mkbn2' => array(
			'className' => 'Mkbn2',
			'foreignKey' => 'mkbn2_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Mkbn3' => array(
			'className' => 'Mkbn3',
			'foreignKey' => 'mkbn3_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
