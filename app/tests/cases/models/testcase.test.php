<?php 
/* SVN FILE: $Id$ */
/* Testcase Test cases generated on: 2010-02-05 12:47:37 : 1265370457*/
App::import('Model', 'Testcase');

class TestcaseTestCase extends CakeTestCase {
	var $Testcase = null;
	var $fixtures = array('app.testcase', 'app.project', 'app.user', 'app.testcasestep', 'app.test', 'app.testcasestep', 'app.test');

	function startTest() {
		$this->Testcase =& ClassRegistry::init('Testcase');
	}

	function testTestcaseInstance() {
		$this->assertTrue(is_a($this->Testcase, 'Testcase'));
	}

	function testTestcaseFind() {
		$this->Testcase->recursive = -1;
		$results = $this->Testcase->find('first');
		$this->assertTrue(!empty($results));

		$expected = array('Testcase' => array(
			'id'  => 1,
			'name'  => 'Lorem ipsum dolor sit amet',
			'project_id'  => 1,
			'description'  => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida,phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam,vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit,feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'user_id'  => 1
		));
		$this->assertEqual($results, $expected);
	}
}
?>