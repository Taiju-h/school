<?php
App::uses('AppModel', 'Model');
/**
 * Mryoukin Model
 *
 * @property Msubscription $Msubscription
 * @property Muser $Muser
 */
class Mryoukin extends AppModel {

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
		'kng' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'name' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
	);

	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Msubscription' => array(
			'className' => 'Msubscription',
			'foreignKey' => 'mryoukin_id',
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
