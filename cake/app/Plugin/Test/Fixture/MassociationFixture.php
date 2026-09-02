<?php
/**
 * Massociation Fixture
 */
class MassociationFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'massociation';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'mfile_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'mkbn1_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'mkbn2_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'mkbn3_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'updateid' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'indexes' => array(
			'id_2' => array('column' => 'id', 'unique' => 1),
			'id' => array('column' => 'id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB', 'comment' => 'MFile???????')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'mfile_id' => 1,
			'mkbn1_id' => 1,
			'mkbn2_id' => 1,
			'mkbn3_id' => 1,
			'created' => '2016-09-20 11:28:51',
			'modified' => '2016-09-20 11:28:51',
			'updateid' => 1
		),
	);

}
