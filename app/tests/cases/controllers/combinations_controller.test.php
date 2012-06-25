<?php 
/* SVN FILE: $Id$ */
/* CombinationsController Test cases generated on: 2010-07-15 18:16:19 : 1279210579*/
App::import('Controller', 'Combinations');

class TestCombinations extends CombinationsController {
	var $autoRender = false;
}

class CombinationsControllerTest extends CakeTestCase {
	var $Combinations = null;

	function startTest() {
		$this->Combinations = new TestCombinations();
		$this->Combinations->constructClasses();
	}

	function testCombinationsControllerInstance() {
		$this->assertTrue(is_a($this->Combinations, 'CombinationsController'));
	}

	function endTest() {
		unset($this->Combinations);
	}
}
?>