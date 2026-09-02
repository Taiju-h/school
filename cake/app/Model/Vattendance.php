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
class Vattendance extends AppModel {

/**
 * Use database config
 *
 * @var string
 */
	public $useDbConfig = 'school';
	public $primaryKey =  'id';

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';
}
