<?php
App::uses('Mfile', 'Model');

/**
 * Mfile Test Case
 */
class MfileTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.mfile',
		'app.mkbn1',
		'app.mkbn2'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Mfile = ClassRegistry::init('Mfile');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Mfile);

		parent::tearDown();
	}

}
