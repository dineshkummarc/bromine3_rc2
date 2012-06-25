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
class ApiController extends AppController {
    public $uses = array('Tests', 'Testcases','Commands','Requirements','Projects','Users','Browsers','Operatingsystems','Sites','Suites');
    public $compoments = array('Auth');
    
    function beforeFilter(){
        $this->layout = 'none';
        $this->loadModel('Test');
        $this->loadModel('Testcase');
        $this->loadModel('Command');
        $this->loadModel('Requirement');
        $this->loadModel('Project');
        $this->loadModel('User');
        $this->loadModel('Site');
        $this->loadModel('Suite');
        $this->loadModel('Browser');
        $this->loadModel('Operatingsystem');
        $this->loadModel('Config');
        $this->Layout = 'none';
        parent::beforeFilter();
    }
    

    /*
     * Creates a testcase. You need to create this before you can upload a script
     * @param $name the name of the testcase
     * @param $requirement_id id of the parent requirement
     * @param $project_id id of the project
     * @param $user_id id ofthe owner of the testcase     
     */    
    
    function createTestcase(){
        !empty($this->params['named']['name']) ? $name = $this->params['named']['name'] : exit('Failed. name not set');
        !empty($this->params['named']['project_id']) ? $project_id = $this->params['named']['project_id'] : exit('Failed. project_id not set');
        !empty($this->params['named']['user_id']) ? $user_id = $this->params['named']['user_id'] : exit('Failed. user_id not set');
        !empty($this->params['named']['requirement_id']) ? $requirement_id = $this->params['named']['requirement_id'] : exit('Failed. $requirement_id not set');
        
        if (isset($name) && isset($requirement_id) && isset($project_id) && isset($user_id)){
            $data['Testcase']['name'] = $name;
            $data['Testcase']['project_id'] = $project_id;
            $data['Testcase']['user_id'] = $user_id;
            $data['Requirement']['Requirement'][0] = $requirement_id;
            
            if ($this->Testcase->save($data)){
                echo "OK";
            }else{
                echo "Failed. Testcase could not be saved.";
            }
            
        }else{
            echo 'Params not set correct.';
        }
        $this->render('none');    
    }

    /*
     * Creates a requirement.
     * @param $name the name of the requirement
     * @param $project_id id of the project
     * @param $user_id id ofthe owner of the testcase     
     */    
    
    function createRequirement(){
        !empty($this->params['named']['name']) ? $name = $this->params['named']['name'] : exit('Failed. name not set');
        !empty($this->params['named']['project_id']) ? $project_id = $this->params['named']['project_id'] : exit('Failed. project_id not set');
        !empty($this->params['named']['user_id']) ? $user_id = $this->params['named']['user_id'] : exit('Failed. user_id not set');
        $data['Requirement']['name'] = $name;
        $data['Requirement']['project_id'] = $project_id;
        $data['Requirement']['user_id'] = $user_id;
        
        if ($this->Requirement->save($data)){
            echo "OK";
        }else{
            echo "Failed. Requirement could not be saved.";
        }

        $this->render('none');    
    }
    
    /*
     * Creates a testrun. You need to create this before you can add commands
     * @param $status the status of the testrun (can be changed by using updateTestrunStatus())
     * @param $browser_name shortname of the browser
     * @param $os_name shortname of the operationsystem
     * @param $testcase_name name of the testcase that have been runned
     * @param $site_name name of the site the test ran on
     * @param $project_name name of the project the testcase is related to     
     */

    function createTestrun($status, $browser_name, $os_name, $testcase_name, $site_name, $project_name){
        $this->Site->recursive = -1;
        $site = $this->Site->find('first',array('conditions' => array('Site.name LIKE' => "%$site_name%")));
        if (empty($site)){echo "Failed. Could not find site '$site_name'. Check spelling and remember that its case sensitive"; exit;}
        $site_id = $site['Site']['id'];
        
        $this->Project->recursive = -1;
        $project = $this->Project->find('first',array('conditions' => array('Project.name LIKE' => "%$project_name%")));
        if (empty($project)){echo "Failed. Could not find project '$project_name'. Check spelling and remember that its case sensitive"; exit;}
        $project_id = $project['Project']['id'];
        
        $this->Browser->recursive = -1;
        $browser = $this->Browser->find('first',array('conditions' => array('Browser.shortname LIKE' => "%$browser_name%")));
        if (empty($browser)){echo "Failed. Could not find browser '$browser_name'. Check spelling and remember that its case sensitive"; exit;}
        $browser_id = $browser['Browser']['id'];

        $this->Operatingsystem->recursive = -1;
        $os = $this->Operatingsystem->find('first',array('conditions' => array('Operatingsystem.shortname LIKE' => "%$os_name%")));
        if (empty($os)){echo "Failed. Could not find Operatingsystem '$os_name'. Check spelling and remember that its case sensitive"; exit;}
        $os_id = $os['Operatingsystem']['id'];

        $this->Testcase->recursive = -1;
        $testcase = $this->Testcase->find('first',array('conditions' => array('Testcase.name LIKE' => "%$testcase_name%")));
        if (empty($testcase)){echo "Failed. Could not find Testcase '$testcase_name'. Check spelling and remember that its case sensitive"; exit;}
        $testcase_id = $testcase['Testcase']['id'];
        
        $data['Test']['status'] = checkStatus($status);
        $data['Test']['name'] = $testcase['Testcase']['name'];
        $data['Test']['browser_id'] = $browser_id;
        $data['Test']['operatingsystem_id'] = $os_id;
        $data['Test']['testcase_id'] = $testcase_id;
        // Must be filled out due to Strict db stuff
        $data['Test']['suite_id'] = 0;
        $data['Test']['manstatus'] = 'auto';
        $data['Test']['timestamp'] = null;
        
        if ($this->Test->save($data)){
            echo "OK," . $this->Test->getLastInsertId();
        }else{
            echo "Failed. Could not save test run.";
            exit;
        }
        $data = array();
        $data['Suite']['name'] = 'API created result';
        $data['Suite']['site_id'] = $site_id;	
        $data['Suite']['timedate'] 	= null;
        $data['Suite']['timetaken'] = null;
        $data['Suite']['selenium_version'] = null;
        $data['Suite']['project_id'] = $project_id;
        $data['Suite']['analysis']  = 0;	
        $data['Suite']['status']  = 'null'; 	
        $data['Suite']['browser_id'] 	 = 0;
        $data['Suite']['operating_system_id']  = 0;	
        $data['Suite']['selenium_revision'] = 0;
        
        if ($this->Suite->save($data)){
        }else{
            echo "Failed. Could not save test run.";
            exit;
        }
        $this->render('none');
    }
    
    /*
     * Add a command to a testrun
     * @param $status the status of the command
     * @param $testrun_id id of the test run
     * @param $action action of the command
     * @param $var1 - var1 of the command
     * @param $var2 - var2 og the command               
     */
    
    function addCommandToTestrun(){
        $status = '';
        !empty($this->params['named']['testrun_id']) ? $testrun_id = $this->params['named']['testrun_id'] : exit('Failed. Testrun_id not set');
        !empty($this->params['named']['action']) ? $action = $this->params['named']['action'] : exit('Failed. Action not set');
        !empty($this->params['named']['var1']) ? $var1 = $this->params['named']['var1'] : exit('Failed. var1 not set');
        !empty($this->params['named']['var2']) ? $var2 = $this->params['named']['var2'] : exit('Failed. var2 not set');
        !empty($this->params['named']['status']) ? $status = $this->params['named']['status'] : exit('Failed. status not set');
        
        $status = $this->checkStatus($status);

        $this->Test->recursive = -1;
        $testrun = $this->Test->find('first',array('conditions' => array('Test.id' => $testrun_id)));
        if (empty($testrun)){
            echo "Failed. The given testrun id does not exist.";
            exit;
        }
        $test_id = $testrun['Test']['id'];
        $data['Command']['status'] = strtolower($status);
        $data['Command']['action'] = $action;
        $data['Command']['var1'] = $var1;
        $data['Command']['var2'] = $var2;
        $data['Command']['test_id'] = $test_id;
        
        if ($this->Command->save($data)){
            echo "OK";
        }else{
            echo "Failed. Command could not be saved.";
        }
        $this->render('none');
        
    }
    
    /*
     * Update the status of a certain testrun
     * @param $status the status to be checked
     * @param $testrun_id id of the test run to be changed     
     */
    
    function updateTestrunStatus(){
        $status ='';
        !empty($this->params['named']['testrun_id']) ? $testrun_id = $this->params['named']['testrun_id'] : exit('Failed. Testrun_id not set');
        !empty($this->params['named']['status']) ? $status = $this->params['named']['status'] : exit('Failed. status not set');
        $status = $this->checkStatus($status);
        
        if($this->Test->updateAll(array('Test.status'=>"'$status'"), array('Test.id'=>$testrun_id))){
            echo "OK";
        }else{
            echo "Failed. Testrun status could not be updated";
        }
        $this->render('none');
    }

    /*
     * Check if the status is either 'passed' or 'failed'
     * @param $status the status to be checked
     */
    
    private function checkStatus($status){
        $status = strtolower($status);
        if ($status != 'failed' && $status != 'passed'){
            echo "Failed. Status must be 'failed' or 'passed'";
            exit;
        }
        return $status;    
    }
    
    
    /*
     * Updates the status of the last command in the database
     * @param $status the status to change it to 
     * @param (Optional) $var2 will be inserted in the database as var2, if given
     */
    
    private function updateCommand($test_id, $status, $action,$var1, $var2){
        $this->log(__FUNCTION__ . " called with: test_id: $test_id, status: $status, action: $action, var1: $var1, var2: $var2");
        $this->loadModel('Command');
        $this->Command->recursive = -1;
        echo "OK,$status";
        $data = $this->Command->find('first', array(
                                        'conditions' => array('test_id' => $test_id),
                                        'order' => 'Command.id DESC'
                                        )
                            );
        
        $oldAction = $data['Command']['action'];    
        !empty($status) ? $data['Command']['status'] = $status : '';
        !empty($var1) ? $data['Command']['var1'] = $var1 : '';
        !empty($var2) ? $data['Command']['var2'] = $var2 : '';
        !empty($action) ? $data['Command']['action'] = "$action($oldAction)" : '';
        $this->Command->save($data);

    }

    public function log($text){
        $logLine = date('l jS \of F Y h:i:s A') . ": $text\n";
        file_put_contents('logs/BRUnit_output.txt', $logLine, FILE_APPEND); 
    }
}