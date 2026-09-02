<?php
App::uses('Mkbn1', 'Model');

/**
 * Mkbn1 Test Case
 */
class Mkbn1Test extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.mkbn1',
		'app.mfile',
		'app.mkbn2'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Mkbn1 = ClassRegistry::init('Mkbn1');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Mkbn1);

		parent::tearDown();
	}

}
