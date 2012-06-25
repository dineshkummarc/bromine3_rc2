<?php 
/* SVN FILE: $Id$ */
/* JobsController Test cases generated on: 2010-07-15 16:36:59 : 1279204619*/
App::import('Controller', 'Jobs');

class TestJobs extends JobsController {
    var $autoRender = false;
    var $fixtures = array(
                'app.requirement', 
                'app.project', 
                'app.parent',
                'app.myaco', 
                'app.group',
                'app.myaro',
                'app.user',
                'app.job',
                'app.myaros_myacos',
                'app.myaros_myaco',
                'app.MyarosMyaco',
                'app.myarosMyaco',
                'app.combination'
            );
}

class JobsControllerTest extends CakeTestCase {
	var $Jobs = null;

	function startTest($method) {
        $this->Jobs = new TestJobs();
		$this->Jobs->constructClasses();
		parent::startTest($method);
	}

	function testJobsControllerInstance() {
		$this->assertTrue(is_a($this->Jobs, 'JobsController'));
	}

	function endTest($method) {
        parent::endTest($method);
		unset($this->Jobs);
	}
	
	function testJobIndex(){                                         
                                                        
        $result = $this->testAction('jobs/index', array('return' => 'vars', 'data' => $data,'fixturize' =>  true));
        debug($result);
    }
	
}
?>