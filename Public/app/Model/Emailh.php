<?php
App::uses('AppModel', 'Model');
/**
 * Emailh Model
 *
 * @property Mkanteishi $Mkanteishi
 * @property User $User
 * @property Mclassification $Mclassification
 * @property Mtime $Mtime
 * @property Mtime2 $Mtime2
 * @property Mticket $Mticket
 * @property Mmenu $Mmenu
 * @property Mquestionnaire $Mquestionnaire
 */
class Emailh extends AppModel {

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
	public $useTable = 'emailh';

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
		'id' => array(
			'uuid' => array(
				'rule' => array('uuid'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'mkanteishi_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'mclassification_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'mquestionnaire_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'yoyakusendflg' => array(
			'boolean' => array(
				'rule' => array('boolean'),
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
		'Mkanteishi' => array(
			'className' => 'Mkanteishi',
			'foreignKey' => 'mkanteishi_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Mtenpo' => array(
			'className' => 'Mtenpo',
			'foreignKey' => 'mtenpo_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Mclassification' => array(
			'className' => 'Mclassification',
			'foreignKey' => 'mclassification_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Mtime' => array(
			'className' => 'Mtime',
			'foreignKey' => 'mtime_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Mtime2' => array(
			'className' => 'Mtime2',
			'foreignKey' => 'mtime2_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Mticket' => array(
			'className' => 'Mticket',
			'foreignKey' => 'mticket_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Mmenu' => array(
			'className' => 'Mmenu',
			'foreignKey' => 'mmenu_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Mquestionnaire' => array(
			'className' => 'Mquestionnaire',
			'foreignKey' => 'mquestionnaire_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);
}
