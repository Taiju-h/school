<?php
App::uses('Mkbn2', 'Model');

/**
 * Mkbn2 Test Case
 */
class Mkbn2Test extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.mkbn2',
		'app.mfile',
		'app.mkbn1'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Mkbn2 = ClassRegistry::init('Mkbn2');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Mkbn2);

		parent::tearDown();
	}

}
