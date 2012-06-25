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
class RunrctestsController  extends AppController {

	public $helpers = array('Html', 'Form');
	public $layout = "green";
	public $uses = array();
	public $pageTitle = 'Run test';
	public $components = array('Email'); 
    
    private function nodes_running($nodes){ //Used in the while loop to determine if there is still nodes running tests 
        foreach($nodes as $node){
            if(!empty($node['Node']['running']))
                return true;
            }
        return false;
    }
    
    function beforeFilter(){
        Configure::write('Cache.disable', true);
        $this->loadModel('Config');
        $this->servername = $this->Config->field('value', array('name'=>'servername'));
        $this->port = $this->Config->field('value', array('name'=>'port'));
        parent::beforeFilter();
    }
    
    private function getFileExt($id){
        App::import('Model','Type');
        $this->Type = new Type();
        $extList = $this->Type->find('list', array('fields' => array('Type.extension')));
        foreach($extList as $ext){
            $file = WWW_ROOT.DS.'testscripts'.DS.$this->Session->read('project_name').DS.$ext.DS.$id.".$ext";
            if(file_exists($file)){
                return $ext;
            }
        }
        return false;
    }
 
    //Should be called when adding to the que    
    function setupSuite($requirement_id){ //Sets up the suite, returns suite_id to viewer, viewer calls runRequirement/runTestcase
        $this->layout = "green_blank";
        $this->set('requirement_id', $requirement_id);
        $suiteName = 'Suite name';
        
        App::import('Model','Suite');
        $this->Suite = new Suite();
        $this->data['Suite'] = array(
            'site_id' => $this->Session->read('site_id'),
            'project_id' => $this->Session->read('project_id'),
            'user_id' => $this->Auth->user('id')
        );
        $this->Suite->save($this->data);
        $suite_id = $this->Suite->id;
        return $suite_id;
    }
    
    private function getOfflineNeeds($requirement_id){ //Find out which tests can't be run.
        App::import('Model','Node');
        $this->Node = new Node();
        $nodes = $this->Node->find('all');
        $onlineCombinations = array();
        foreach($nodes as $node){
            if($this->Node->checkJavaServer($node['Node']['nodepath'])){
                foreach($node['Browser'] as $browser){
                    $onlineCombinations[] = $node['Operatingsystem']['id'].','.$browser['id'];
                }
            }
        }
        App::import('Model','Requirement');
        $this->Requirement = new Requirement();
        
        $this->Requirement->Behaviors->attach('Containable');
		$requirement = $this->Requirement->find('first', array(
            'conditions'=>array(
                'Requirement.id'=>$requirement_id
            ),
        	'contain'=>array(
        		'Combination' => array(
        			'Browser',
        			'Operatingsystem'
        		)
        	)
        ));
        
        $offlineNeeds =  array();
        foreach($requirement['Combination'] as $combination){
            $idCombination = $combination['Operatingsystem']['id'].','.$combination['Browser']['id'];
            if(!in_array($idCombination,$onlineCombinations)){
                $offlineNeeds[] = $combination['Browser']['name'].' on '.$combination['Operatingsystem']['name'];
            }
        }
        return $offlineNeeds;
    }
    
    function suiteAjaxView($suite_id){
        $this->loadModel('Suite');
        $suite = $this->Suite->findById($suite_id);
        $this->layout = 'green_blank';
        $this->set('suite_id', $suite_id);
        $this->set('suite', $suite);
            
    }
    
    
    function runAndViewTestcase($testcase_id, $requirement_id){
        $this->set('testcase_id', $testcase_id);
        $this->set('suite_id', $this->setupSuite($requirement_id));
        $this->set('offlineNeeds', $this->getOfflineNeeds($requirement_id));
    }
    
    function runNoViewTestcase($testcase_id, $requirement_id){      
        $suite_id = $this->setupSuite($requirement_id);
        $this->runTestcase($testcase_id, $requirement_id, $suite_id);
        $this->layout = NULL;
    }
    
    function runNoViewRequirement($requirement_id){
        $suite_id = $this->setupSuite($requirement_id);
        $this->runRequirement($requirement_id, $suite_id);
        $this->layout = NULL;
    }
    
    function runAndViewRequirement($requirement_id){ 
        $this->set('suite_id',$this->setupSuite($requirement_id));
        $this->set('offlineNeeds',$this->getOfflineNeeds($requirement_id));
    }
    
    function runAndViewNestedRequirement($requirement_id){
        $this->set('suite_id',$this->setupSuite($requirement_id));
        $this->set('offlineNeeds',$this->getOfflineNeeds($requirement_id));
    }

    function runNoViewNestedRequirement($requirement_id){
        $suite_id = $this->setupSuite($requirement_id);
        $this->runNestedRequirement($requirement_id, $suite_id);
        $this->layout = NULL;
    }
    
    function runAndViewProject($project_id){
        $this->set('suite_id',$this->setupSuite(0));
        $this->set('project_id',$project_id);  
    }
    
    function runProject($project_id, $suite_id){
        $this->loadModel('Project');
        // get all requirements
        $requirements = $this->Project->Requirement->find('all',  array(
            'conditions' => array(
                'project_id' => $project_id
            ), 
            'recursive' => -1
        ));
        // Runs each requirement      
        foreach($requirements as $requirement){
            $this->runRequirement($requirement['Requirement']['id'],$suite_id);
        }    
    }
    
    function runNestedRequirement($requirement_id, $suite_id){
        $this->loadModel('Requirement');
        // Use model function to get a list of nested reqs
        $requirements = $this->Requirement->getNestedRequirements($requirement_id);
        // simply loop through each and run the req
        foreach ($requirements as $req_id){
            $this->runRequirement($req_id, $suite_id);    
        }
    }
       
    function runRequirement($requirement_id, $suite_id){ //Sorts out offline needs, sets up tests array, calls loadBalancer
        $this->loadModel('Requirement');
		$requirement = $this->Requirement->find('first', array(
            'conditions'=>array(
                'Requirement.id'=>$requirement_id
            ),
        	'contain'=>array(
        	    'Testcase',
        		'Combination' => array(
        			'Browser',
        			'Operatingsystem'
        		)
        	)
        ));
        $req = $this->Requirement->find('threaded');
        
        $offlineNeeds = $this->getOfflineNeeds($requirement_id);
        
        $jobs = array();
        foreach ($requirement['Testcase'] as $testcase){
            if($this->getFileExt($testcase['id']) != false){
                
                foreach ($requirement['Combination'] as $combination){
                    $need = $combination['Browser']['name'].' on '.$combination['Operatingsystem']['name'];
                    if(!in_array($need, $offlineNeeds)){ //Sort out the needs that can't be run
                        $jobs[]['Job'] = array(
                            'testcase_id' => $testcase['id'],
                            'operatingsystem_id' => $combination['operatingsystem_id'],
                            'browser_id' => $combination['browser_id'],
                            'suite_id' => $suite_id
                        );
                    }
                }
            }
        }
        $this->loadModel('Job');        
        $this->Job->saveAll($jobs);
    }
    
    function runTestcase($testcase_id, $requirement_id, $suite_id){ //Sorts out offline needs, sets up tests array, calls loadBalancer
        App::import('Model','Requirement');
        $this->Requirement = new Requirement();
        $this->Requirement->Behaviors->attach('Containable');
		$requirement = $this->Requirement->find('first', array(
            'conditions'=>array(
                'Requirement.id'=>$requirement_id
            ),
        	'contain'=>array(
        		'Combination' => array(
        			'Browser',
        			'Operatingsystem'
        		)
        	)
        ));
        
        $offlineNeeds = $this->getOfflineNeeds($requirement_id);
        
        $jobs = array(); 
        foreach ($requirement['Combination'] as $combination){
            $need = $combination['Browser']['name'].' on '.$combination['Operatingsystem']['name'];
            if(!in_array($need, $offlineNeeds)){ //Sort out the needs that can't be run
                $jobs[]['Job'] = array(
                    'testcase_id' => $testcase_id,
                    'operatingsystem_id' => $combination['operatingsystem_id'],
                    'browser_id' => $combination['browser_id'],
                    'suite_id' => $suite_id
                    );
            }
        }
        $this->loadModel('Job');        
        $this->Job->saveAll($jobs);
    }
    
    
    /*
    function notifyUsers($suite_id,$uid){
        $this->loadModel('Suite');
        $this->log("notify users called with: suite_id=$suite_id, uid=$uid");
        $email_host = $this->Config->field('value', array('name' => 'email_host'));
        $email_port = $this->Config->field('value', array('name'=> 'email_port'));
        $email_username = $this->Config->field('value', array('name'=> 'email_username'));
        $email_password = $this->Config->field('value', array('name'=> 'email_password'));
        
        $this->Email->smtpOptions = array(
        'port'=>$email_port,
        'timeout'=>'30',
        'host' =>$email_host,
        'username'=>$email_username,
        'password'=>$email_password);   

        $this->Suite->Behaviors->attach('Containable');
        $data = $this->Suite->find('all',
            array(
                'conditions' => array('Suite.id' => $suite_id
                                     ),
                'contain' => array(
                    'Test' => array('Browser','Operatingsystem','Testcase' => array('User')),
                    'Site' => array('Project')
                )    
            )
            
        );
        $users = array();
        foreach ($data[0]['Test'] as $test) {
        	$newuser = $test['Testcase']['User']['email'];
            if (!in_array($newuser, $users)){
                $this->set('data',$data);
                $this->set('project_name',$data[0]['Site']['Project']['name']);
                $this->set('site_name',$data[0]['Site']['name']);
                
                $this->set('username',$this->Session->read('Auth.User.name'));
                $this->set('user_password',$this->user_password);
                $this->set('project_id',$this->Session->read('project_id'));
                
                $servername = $this->Config->field('value', array('name' => 'servername'));
                $port = $this->Config->field('value', array('name'=> 'port'));
                $this->set('servername', $servername);
                $this->set('port', $port);
                
                $this->Email->sendAs = 'html';
                $this->Email->template = 'report';
                //Set delivery method 
                $this->Email->delivery = 'smtp';
                
                $this->Email->to = $this->realname . " <$newuser>";
                $this->Email->subject = "Bromine test report " . date("H:i:s d. F Y");
                $this->Email->from = 'Bromine <bromine.eniro@gmail.com>';
                
                if ( $this->Email->send() ) {
                    $this->log('Email sent');
                } else {
                    $this->log('Email error: '. $this->Email->smtpError);
                } 
                $this->Email->reset();
            }
            $users[] = $newuser;
        }
        
    }
    */
}
