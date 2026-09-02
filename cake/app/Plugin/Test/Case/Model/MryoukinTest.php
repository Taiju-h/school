<?php
App::uses('Mryoukin', 'Model');

/**
 * Mryoukin Test Case
 */
class MryoukinTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
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
		$this->Mryoukin = ClassRegistry::init('Mryoukin');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Mryoukin);

		parent::tearDown();
	}

}
