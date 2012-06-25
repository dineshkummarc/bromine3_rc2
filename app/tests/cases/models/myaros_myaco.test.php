<?php 
/* SVN FILE: $Id$ */
/* MyarosMyaco Test cases generated on: 2010-07-15 17:15:21 : 1279206921*/
App::import('Model', 'MyarosMyaco');

class MyarosMyacoTestCase extends CakeTestCase {
	var $MyarosMyaco = null;
	var $fixtures = array('app.myaros_myaco');

	function startTest() {
		$this->MyarosMyaco =& ClassRegistry::init('MyarosMyaco');
	}

	function testMyarosMyacoInstance() {
		$this->assertTrue(is_a($this->MyarosMyaco, 'MyarosMyaco'));
	}

	function testMyarosMyacoFind() {
		$this->MyarosMyaco->recursive = -1;
		$results = $this->MyarosMyaco->find('first');
		$this->assertTrue(!empty($results));

		$expected = array('MyarosMyaco' => array(
			'id'  => 1,
			'myaro_id'  => 1,
			'myaco_id'  => 1,
			'access'  => 1
		));
		$this->assertEqual($results, $expected);
	}
}
?>