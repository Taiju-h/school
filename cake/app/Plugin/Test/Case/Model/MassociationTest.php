<?php
App::uses('Massociation', 'Model');

/**
 * Massociation Test Case
 */
class MassociationTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.massociation',
		'app.mfile',
		'app.mkbn1',
		'app.mkbn2',
		'app.mkbn3'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Massociation = ClassRegistry::init('Massociation');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Massociation);

		parent::tearDown();
	}

}
