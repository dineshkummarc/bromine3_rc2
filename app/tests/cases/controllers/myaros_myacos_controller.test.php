<?php 
/* SVN FILE: $Id$ */
/* MyarosMyacosController Test cases generated on: 2010-07-15 17:17:57 : 1279207077*/
App::import('Controller', 'MyarosMyacos');

class TestMyarosMyacos extends MyarosMyacosController {
	var $autoRender = false;
}

class MyarosMyacosControllerTest extends CakeTestCase {
	var $MyarosMyacos = null;

	function startTest() {
		$this->MyarosMyacos = new TestMyarosMyacos();
		$this->MyarosMyacos->constructClasses();
	}

	function testMyarosMyacosControllerInstance() {
		$this->assertTrue(is_a($this->MyarosMyacos, 'MyarosMyacosController'));
	}

	function endTest() {
		unset($this->MyarosMyacos);
	}
}
?>