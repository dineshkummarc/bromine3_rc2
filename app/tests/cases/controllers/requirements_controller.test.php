<?php 
/* SVN FILE: $Id$ */
/* RequirementsController Test cases generated on: 2010-07-15 10:53:15 : 1279183995*/
App::import('Controller', 'Requirements');

class TestRequirements extends RequirementsController {
	var $autoRender = false; 
}

class RequirementsControllerTest extends CakeTestCase {
	var $Requirements = null;

	function startTest($method) {
        parent::startTest($method);
		$this->Requirements = new TestRequirements();
        $this->Requirements->loadModel('Requirement');
		$this->Requirements->constructClasses();
	}
	
	function endTest($method){
        parent::endTest($method);
    }

	function testRequirementsControllerInstance() {
		$this->assertTrue(is_a($this->Requirements, 'RequirementsController'));
	}
	
	function testThisWillFail(){
        $this->assertTrue(false);
    }
    
    function testImportTestcasesFromCSV(){
        echo "<b>Testing if testdata.csv exists</b>";
        $datafile = TESTS . 'files' . DS . 'testdata.csv';
        $this->assertTrue(file_exists($datafile));
        $data['Requirement']['type'] = 'Testcase';
        $data['Requirement']['seperator'] = ';';
        $data['Requirement']['datafile']['name'] = 'testdata.csv';
        $data['Requirement']['datafile']['type'] = 'application/vnd.ms-excel';
        $data['Requirement']['datafile']['tmp_name'] = APP . 'tests'.DS.'files'.DS.'testdata.csv';
        $data['Requirement']['datafile']['error'] = '0';
        $data['Requirement']['datafile']['size'] = '478';
        
        $result = $this->testAction('/requirements/importFromCSV', 
            array('return' => 'vars', 'data' => $data));
        //debug($result);
        $expected[] = "Testcase 'Normal name' was created with '<b>admin</b>' as owner";
        $expected[] = "Testcase 'Another name' was created with '<b>admin</b>' as owner";
        echo "<b>Testing import function:</b>";
        $this->assertEqual($expected,$result['result']);
    }

    function testImportRequirementsFromCSV(){
        echo "<b>Testing if testdata.csv exists</b>";
        $datafile = TESTS . 'files' . DS . 'testdata.csv';
        $this->assertTrue(file_exists($datafile));
        $data['Requirement']['type'] = 'Requirement';
        $data['Requirement']['seperator'] = ';';
        $data['Requirement']['datafile']['name'] = 'testdata.csv';
        $data['Requirement']['datafile']['type'] = 'application/vnd.ms-excel';
        $data['Requirement']['datafile']['tmp_name'] = APP . 'tests'.DS.'files'.DS.'testdata.csv';
        $data['Requirement']['datafile']['error'] = '0';
        $data['Requirement']['datafile']['size'] = '478';
        
        $result = $this->testAction('/requirements/importFromCSV', 
            array('return' => 'vars', 'data' => $data));
        //debug($result);
        $expected[] = "Requirement 'Normal name' was created with '<b>admin</b>' as owner";
        $expected[] = "Requirement 'Another name' was created with '<b>admin</b>' as owner";
        echo "<b>Testing import function:</b>";
        $this->assertEqual($expected,$result['result']);
    }

}
?>