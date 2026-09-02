<?php
App::uses('Eschedule', 'Model');

/**
 * Eschedule Test Case
 */
class EscheduleTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.eschedule',
		'app.mryoukin',
		'app.msubscription',
		'app.muser',
		'app.mdivision',
		'app.mbank',
		'app.mpaymentmethod'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Eschedule = ClassRegistry::init('Eschedule');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Eschedule);

		parent::tearDown();
	}

}
