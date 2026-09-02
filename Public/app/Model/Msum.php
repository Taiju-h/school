<?php
App::uses('AppModel', 'Model');
/**
 * Msum Model
 *
 * @property Mryoukin $Mryoukin
 * @property Mryoukin2 $Mryoukin2
 */
class Msum extends AppModel {

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
		'mryoukin2_id' => array(
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
		'Mryoukin' => array(
			'className' => 'Mryoukin',
			'foreignKey' => 'mryoukin_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
/*
		'Mryoukin2' => array(
			'className' => 'Mryoukin2',
			'foreignKey' => 'mryoukin2_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
		*/
	);
}
