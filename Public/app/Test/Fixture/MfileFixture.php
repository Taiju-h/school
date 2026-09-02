<?php
/**
 * Mfile Fixture
 */
class MfileFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'mkbn1_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'mkbn2_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'file' => array('type' => 'text', 'null' => false, 'default' => null),
		'thumbnail' => array('type' => 'binary', 'null' => false, 'default' => null),
		'description' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 1022, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'limit_flg' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'disp_flg' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
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
			'mkbn1_id' => 1,
			'mkbn2_id' => 1,
			'file' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'thumbnail' => 'Lorem ipsum dolor sit amet',
			'description' => 'Lorem ipsum dolor sit amet',
			'limit_flg' => 1,
			'disp_flg' => 1,
			'created' => '2016-03-28 13:39:46',
			'modified' => '2016-03-28 13:39:46'
		),
	);

}
