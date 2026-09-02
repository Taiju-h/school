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
class Vtodaylist extends AppModel {

/**
 * Use database config
 *
 * @var string
 */
	public $useDbConfig = 'school';
	public $primaryKey =  'kaisaidate';

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'kaisaidate';
}
