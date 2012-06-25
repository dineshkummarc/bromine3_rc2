<?php 
/* SVN FILE: $Id$ */
/* Job Test cases generated on: 2010-07-15 16:37:59 : 1279204679*/
App::import('Model', 'Job');

class JobTestCase extends CakeTestCase {
	var $Job = null;

	function startTest($method) {
        parent::startTest($method);
		$this->Job =& ClassRegistry::init('Job');
	}

	function testJobInstance() {
		$this->assertTrue(is_a($this->Job, 'Job'));
	}

	function testJobFind() {
		$this->Job->recursive = -1;
		$result = $this->Job->find('first');
		$this->assertTrue(!empty($result));
        debug($result);
		$expected = array(
            'Job' => array(
    			'id'  => 1,
    			'testcase_id'  => 1,
    			'operatingsystem_id'  => 1,
    			'browser_id'  => 1,
    			'added' => '0000-00-00 00:00:00',
    			'suite_id'  => 1
		  )
        );
		$this->assertEqual($result, $expected);
	}

	function testAddJobToQue(){
        $data['Job']['id'] = null;
        $data['Job']['testcase_id'] = 1;
        $data['Job']['operatingsystem_id'] = 1;
        $data['Job']['browser_id'] = 1;
        $data['Job']['added'] = null;
        $data['Job']['suite_id'] = 1;
        $result = $this->Job->save($data);
        $this->assertTrue(!empty($result),'Testing job saved');
        
        $id = $this->Job->getLastInsertId();
        $this->assertTrue($this->Job->delete($id),'Testing job deleted');   
    }
}
?>