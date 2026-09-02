<?php
App::uses('AppModel', 'Model');
/**
 * Efilelog Model
 *
 */
class Efilelog extends AppModel {

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
	public $displayField = 'mfile_title';

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
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
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
		'Mfile' => array(
			'className' => 'Mfile',
			'foreignKey' => 'mfile_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);
}
