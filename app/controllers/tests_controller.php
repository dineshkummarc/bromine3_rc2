<?php
/*
Copyright 2007, 2008, 2009 Rasmus Berg Palm, Visti Kløft and Jeppe Poss Pedersen 

This file is part of Bromine.

Bromine is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Bromine is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Bromine.  If not, see <http://www.gnu.org/licenses/>.
*/
?>
<?php
class TestsController extends AppController {

	public $helpers = array('Html', 'Form' , 'Time');
	
	function sauce_video($session_id, $sauce_username, $sauce_apikey){
        $this->layout = 'green_blank';
        $this->set('session_id', $session_id);
        $this->set('sauce_username', $sauce_username);
        $this->set('sauce_apikey', $sauce_apikey);
    }

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Test.', true));
			$this->redirect(array('action'=>'index'));
		}
		$sauce_enabled = $this->Config->field('value', array('name'=> 'sauce_enabled'));
        if($sauce_enabled == 1){
            $this->set('sauce_username', $this->Config->field('value', array('name' => 'sauce_username')));
            $this->set('sauce_apikey', $this->Config->field('value', array('name'=> 'sauce_apikey')));
        }
		$this->set('test', $this->Test->read(null, $id));
	}
	
	function index($status = NULL){
        $requirement = new Requirement();
        
        if ($status == NULL){
            $requirement->Testcase->Test->Behaviors->attach('Containable');
            $testcases = $requirement->Testcase->Test->find('all',
                array(
                    'conditions' => array('Testcase.project_id' => $this->Session->read('project_id')
                                         ),
                                         
                    'contain' => array('Testcase' => array('Requirement'),
                                       'Browser', 'Operatingsystem', 'Suite' => array('Site')),
                    
                    'order' => 'Test.timestamp DESC',
                    
                    'limit' => '100'
                )
                
            );
            
        }else{
            $requirement->Testcase->Test->Behaviors->attach('Containable');
            $testcases = $requirement->Testcase->Test->find('all',
                array(
                    'conditions' => array('Testcase.project_id' => $this->Session->read('project_id'),
                                          'Test.status' => $status
                                         ),
                                         
                    'contain' => array('Testcase' => array('Requirement'),
                                       'Browser', 'Operatingsystem', 'Suite' => array('Site')),
                    
                    'order' => 'Test.timestamp DESC',
                    
                    'limit' => '100'
                )
                
            );    
        }
        $this->set('testcases',$testcases);
    }

}
