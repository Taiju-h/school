<?php
/**
 * Eschedule Fixture
 */
class EscheduleFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'mryoukin_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'name' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'date1' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'date2' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'date3' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'deadline' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'upddateid' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'indexes' => array(
			'id' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'mryoukin_id' => 1,
			'name' => 1,
			'date1' => '2016-10-23 15:37:27',
			'date2' => '2016-10-23 15:37:27',
			'date3' => '2016-10-23 15:37:27',
			'deadline' => '2016-10-23 15:37:27',
			'created' => '2016-10-23 15:37:27',
			'modified' => '2016-10-23 15:37:27',
			'upddateid' => 1
		),
	);

}
