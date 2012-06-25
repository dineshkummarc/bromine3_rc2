<?php
/*
Copyright 2007, 2008, 2009 Rasmus Berg Palm, Visti Kl�ft and Jeppe Poss Pedersen 

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
class ProjectsController extends AppController {

	public $helpers = array('Html', 'Form', 'Cache','Xml');
    public $main_menu_id = -2;
    public $components = array('Googlechart','Script','Checker');
    //var $cacheAction = array('testlabsview' => "1 day");

	function index() {
		$this->Project->recursive = 0;
		$this->set('projects', $this->paginate());
	}
	
	function run(){
	    $return = @$this->passedArgs['return'];
	    $return == '' ? $return = 'none' : '';
	    $project_id = @$this->passedArgs['project'];
        // check if viewer exists
        if (!file_exists(VIEWS . 'projects\\' . $return . '.ctp')){
            exit ("<b>'$return'</b> return type does not have a corresponding viewer. Create $return.ctp in <b>". VIEWS . 'projects\\' . '</b> folder');    
        }
        //$this->render($return);
        if(empty($this->passedArgs['project']) ||  empty($this->passedArgs['site_id'])){
            echo('project and site_id parameters must be set');
        }
        // Stuff needed to ensure no debug text
        session_write_close();
        Configure::write('debug', 0);
        
        $this->layout = null;
        $this->loadModel('Job');
        $this->loadModel('Node');

        // get all requirements
        $requirements = $this->Project->Requirement->find('all',  array(
            'conditions' => array(
                'project_id' => $project_id
            ) 
        ));

        // Setup suite
        $suite_id = $this->requestAction('runrctests/setupSuite/0');
        // Runs each requirement      
        foreach($requirements as $requirement){
            //echo $requirement['Requirement']['id'] . "<br />";
            $this->requestAction('runrctests/runRequirement/'.$requirement['Requirement']['id'].'/'.$suite_id);
        }
        // Find all job
        $jobs = $this->Job->find('all', array('conditions' => array('suite_id' => $suite_id)));
        // Wait until the is no jobs with $suite_id and there is no running tests
        while(!empty($jobs) || !empty($running_tests)){
            $jobs = $this->Job->find('all', array('conditions' => array('suite_id' => $suite_id)));
            $running_tests = $this->Node->find('list', array(
                'contain' => array(
                    'Test' => array(
                        'conditions' => array(
                            'suite_id' => $suite_id
                        )
                    )
                ),
                'fields' => array(
                    'Node.test_id'
                )
            ));
            // Remove all empty tests
            $running_tests = array_filter($running_tests);
            sleep(10);
                           
        }
        //Get data for the viewer
        $suite = $this->Project->Suite->find('first', array(
                'contain' => array(
                    'Test' => array(
                        'Command',
                        'Browser',
                        'Operatingsystem',
                        'Testcase'
                    )
                    ,
                    'Project',
                    'Site'                         
                ),
                'conditions' => array(
                    'Suite.id' => $suite_id
                )
        
        ));
        //Set the data for the viewer
        $this->set('suite', $suite);
        //Use the viewer as requested
        $this->render($return);
    }

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Project.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Project->recursive = 1;
		$project = $this->Project->find('first', array(
			    'conditions'=>array(
                    'Project.id' => $id    
                ),
                'contain'=>array(
                    'User'=>array(
                        'Group'
                    ),
                    'Site'
                )
            ));
        $this->set('project', $project);
	}

	function add() {
		if (!empty($this->data)) {
			$this->Project->create();
			if ($this->Project->save($this->data)){
                if(!empty($this->data['Newsites'])){
                    foreach($this->data['Newsites'] as $newsite){
                        $this->Project->Site->create();
                        $this->Project->Site->save(array('Site'=>array('name'=>$newsite, 'project_id'=>$this->Project->id)));
                    }
                }
                
                $project_path = WWW_ROOT . DS . 'testscripts' . DS . $this->data['Project']['name'];
                mkdir($project_path);
                mkdir($project_path . DS . 'php');
                mkdir($project_path . DS . 'jar');
                mkdir($project_path . DS . 'jar' . DS . 'lib');
                $libs_path = $project_path . DS . 'jar' . DS . 'lib';
                
                if ($this->data['Project']['copyjava']){
                    $path = WWW_ROOT . 'javalibs';
                    if ($handle = opendir($path)) {
                        while (false !== ($file = readdir($handle))) {
                            if ($file != "." && $file != ".." && $file != '.svn') {
                                //echo "$file\n";
                                $from = $path . DS . $file;
                                $to = $libs_path . DS . $file;
                                copy($from, $to);
                            }
                        }
                        closedir($handle);
                    }    
                }
                
				$this->Session->setFlash(__('The Project has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Project could not be saved. Please, try again.', true), true, array('class'=>'error_message'));
			}
		}
		$users = $this->Project->User->find('list');
		$this->set(compact('users'));
	}
	
	function edit($id = null) {
        if (!$id && empty($this->data)) {
        	$this->Session->setFlash(__('Invalid Project', true));
        	$this->redirect(array('action'=>'index'));
        }
        if (!empty($this->data)){
            $old = $this->Project->findById($id);
            $oldName = $old['Project']['name'];
            $newName = $this->data['Project']['name'];
            $project_path = WWW_ROOT . 'testscripts' . DS;
            if(@rename($project_path . $oldName, $project_path . $newName)){
                if ($this->Project->save($this->data)){
        		    if(!empty($this->data['Sites'])){
                        foreach($this->data['Sites'] as $id => $site){
                            $this->Project->Site->create();
                            $this->Project->Site->save(array('Site'=>array('id'=>$id, 'name'=>$site)));
                        }
                    }
                    if(!empty($this->data['Newsites'])){
                        foreach($this->data['Newsites'] as $newsite){
                            $this->Project->Site->create();
                            $this->Project->Site->save(array('Site'=>array('name'=>$newsite, 'project_id'=>$this->data['Project']['id'])));
                        }
                    }
        			$this->Session->setFlash(__('The Project has been saved', true));
        			$this->redirect(array('action'=>'index'));
        		} else {
        			$this->Session->setFlash(__('The Project could not be saved. Please, try again.', true), true, array('class'=>'error_message'));
        		}
            }else{
                $this->Session->setFlash('Could not rename '.$project_path . $oldName.' to '.$project_path . $newName.'. Please, try again.', true, array('class'=>'error_message'));
            } 
            
		}

	    $this->Project->recursive = 1;
	    
		$this->data = $this->Project->find('first', array(
		    'conditions'=>array(
                'Project.id' => $id    
            ),
            'contain'=>array(
                'User'=>array(
                    'Group'
                ),
                'Site'
            )
        ));

		$users = $this->Project->User->find('list');
		$this->set(compact('users'));
	}
	
	function statistics($send_data = false){
	   
        //Configure::write('debug', 0);
        $this->loadModel('Type');
        $this->Type->recursive = 0;
        $types = $this->Type->find('all');
        
        $this->Project->recursive = 0;
        $projects = $this->Project->find('all');
        $result = array();
        foreach ($projects as $project){
            $project_name = $project['Project']['name'];
            foreach($types as $type){
                $type_name = $type['Type']['extension'];
                $path = WWW_ROOT.'testscripts'. DS . $project_name . DS . $type_name . DS;
                if (is_dir($path)){
                    $dir = scandir($path);
                    foreach($dir as $file){
                        if (is_file($path . $file)){
                            $ext = substr($file, strrpos($file, '.') + 1);
                            if (isset($result[$ext])){
                                $result[$ext]++;
                            }else{
                                $result[$ext] = 1;
                            }
                        }
                    }
                }
            }
        }
        
        
        $this->loadModel('Config');
        
        //Create unique bromine key
        $key = $this->Config->findByName('brkey');
        if(empty($key)){
            $data['Config'] = array('name' => 'brkey', 'value' => md5(microtime().rand(0, 1000000)));
            $this->Config->save($data);
            $this->Config->create();
            $data['Config'] = array('name' => 'last_statistics', 'value' => 0);
            $this->Config->save($data);
            $key = $this->Config->findByName('brkey');
        }
        $key = $key['Config']['value'];

        $enableGA = $this->Config->findByName('enableGA');
        $enableGA = $enableGA['Config']['value'];
        
        $last_statistics = $this->Config->findByName('last_statistics');
        $last_statistics = $last_statistics['Config']['value']; 
        if (($enableGA == 1 && $last_statistics < strtotime('-1 week')) || $send_data == false){
            //do statistics
            
            $this->loadModel('Requirement');
            $this->loadModel('Testcase');
            $this->loadModel('Project');
            $this->loadModel('Site');
            $this->loadModel('User');
            $this->loadModel('Node');
            $this->loadModel('Plugin');
            $this->loadModel('Test');
            $this->loadModel('Group');
            $this->loadModel('Testcasestep');
            $this->loadModel('Myaros_myaco');
            $this->loadModel('Combinations_requirement');
            
            $params = array('brkey', 'OS','requirements', 'testcases', 'projects', 'sites', 'users', 'nodes', 'sauce_enabled', 'plugins', 'tests', 'testcasesteps', 'groups', 'myaros_myacos', 'combinations_requirements', 'version','php','java');
            
            $brkey = $key;
            $OS = PHP_OS; 
            $requirements = $this->Requirement->find('count');
            $testcases = $this->Testcase->find('count');
            $projects = $this->Project->find('count');
            $sites = $this->Site->find('count');
            $users = $this->User->find('count');
            $nodes = $this->Node->find('count');
            $sauce_enabled = $this->Config->findByName('sauce_enabled');
            $sauce_enabled = $sauce_enabled['Config']['value'];
            $plugins = $this->Plugin->find('list');
            $plugins = implode(',', $plugins);
            $tests = $this->Test->find('count', array(
                'conditions' => array(
                    'timestamp >' => date('Y-m-d H:i:s', $last_statistics)
                )
            ));
            $testcasesteps = $this->Testcasestep->find('count');
            $groups = $this->Group->find('count');
            $myaros_myacos = $this->Myaros_myaco->find('count');
            $combinations_requirements = $this->Combinations_requirement->find('count');
            $version = $this->Config->findByName('version');
            $version = $version['Config']['value'];
            $php = $result['php'];
            $java = $result['jar'];
            
            
            $url = 'http://brominefoundation.org/statistics/track/';            
            foreach($params as $param){
                
                //echo "$param: ".$$param."<br />";
                $url .= $param.':'.$$param.'/';
                $user_data[$param] = $$param;
            }
            if($send_data == true){
                @fopen($url, 'r');
                //Update last statistic time            
                $data = $this->Config->findByName('last_statistics');
                $data['Config']['name'] = 'last_statistics';
                $data['Config']['value'] = strtotime('now');
                $this->Config->save($data);    
            }else{
                $this->set('user_data', $user_data);            
            }            
                      
            
        }
    }

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Project', true), true, array('class'=>'error_message'));
			$this->redirect(array('action'=>'index'));
		}
        $project = $this->Project->read(null,$id);
        if ($this->Project->del($id)) {
    		//find the path
            $dir = WWW_ROOT . 'testscripts' . DS . $project['Project']['name']; 
            $files = $this->directoryToArray($dir, true);
            // deletes files, incl. testscripts
            foreach ($files as $file) {
                unlink($file);        	
            }
            // Removes dirs
            rmdir($dir . DS . 'jar' . DS . 'lib');
            rmdir($dir . DS . 'jar');
            rmdir($dir . DS . 'php');
            rmdir($dir);
            
            //Delete the session if this is the selected project
            if ($this->Session->read('project_id') == $id){
                $this->Session->delete('project_id');
                $this->Session->delete('project_name');    
            }	    
			$this->Session->setFlash(__('Project deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		
	}

    private function directoryToArray($directory, $recursive) {
        $array_items = array();
        if ($handle = opendir($directory)) {
        	while (false !== ($file = readdir($handle))) {
        		if ($file != "." && $file != ".." && $file != '.svn') {
        			if (is_dir($directory. "/" . $file)) {
        				if($recursive) {
        					$array_items = array_merge($array_items, $this->directoryToArray($directory. "\\" . $file, $recursive));
        				}
        				//$file = $directory . "/" . $file;
        				//$array_items[] = preg_replace("/\/\//si", "/", $file);
        			} else {

                        $file = $directory . DS . $file;
        				//$array_items[] = preg_replace("/\/\//si", "", $file);
        				
                        $array_items[] = $file;
        				
        			}
        		}
        	}
        	closedir($handle);
        }
        return $array_items;
    }
	
	function testlabview($id = null) {
	   //echo date('H:i:s');
	   $this->main_menu_id = -2;
	   if($id == null && $this->Session->check('project_id')){
	        $id = $this->Session->read('project_id');
       }
        $project = $this->Project->read(null, $id);
        $this->set('project', $project);
        
        $this->Project->Requirement->Behaviors->attach('Containable');
        $requirements = $this->Project->Requirement->find('all',array(
            'conditions'=>array(
                'project_id' => $id
            ),
            'contain'=> array(
                'Testcase'
            )
        ));

        // Pie chart
        $passed=0; $failed = 0; $notdone = 0;
        foreach($requirements as $requirement){
            foreach($requirement['Testcase'] as $testcase){
                $status = $this->Project->Testcase->getStatus($testcase['id'],$requirement['Requirement']['id'], $this->Session->read('site_id'));
                switch ($status) {
                case 'passed':
                    $passed++;
                    break;
                case 'failed':
                    $failed++;
                    break;
                case 'notdone':
                    $notdone++;
                	break;
                }
            }
        }
        $this->set('chart1',$this->Googlechart->pie($failed,$passed,$notdone));
        
        //Percent test run chart
        //$dates = $this->Googlechart->getAllDates(7);
        /*
        foreach ($dates as $date){
            $result[$date] = '';
            foreach($requirements as $requirement){
                foreach($requirement['Testcase'] as $testcase){
                    $statuses = $this->Project->Testcase->getStatus($testcase['id'],$requirement['Requirement']['id'],$date);
                    foreach ($statuses as $status) {
                        @$result[$date][$status]++;
                    }
                    
                }
            }
        }
        //pr($result);
        $this->set('chart2',$this->Googlechart->percentTestruns($result));
        // Accumulated testruns chart
        if (!isset($this->data['Filter']['days'])){$this->data['Filter']['days'] = 6;}
        $this->data['Filter']['project_id'] = $this->Session->read('project_id');
        $this->set('chart3',$this->Googlechart->accumulatedTestruns($this->data['Filter']));
                                                          
        //Used for selecting dates etc:
        /*
        $this->loadModel('Browser');
		$this->loadModel('Operatingsystem');
		$this->loadModel('Requirement');
        $testcases = $this->Requirement->Testcase->find('list', array('conditions' => array('Testcase.project_id' => $this->Session->read('project_id'))));
        $browsers = $this->Browser->find('list');
        $operatingsystems = $this->Operatingsystem->find('list');
        $days[1] = "1 day";
        for ($i=2;$i<=30;$i++ ) {
            $days[$i] = "$i days";
        }
        $days['60'] = "2 months";
        $days['90'] = "3 months";
        $days['120'] = "4 months";
        $days['150'] = "5 months";
        $days['180'] = "6 months";
        $days['360'] = "12 months";
        $this->set(compact('testcases','browsers','operatingsystems','days'));
        */
        $this->loadModel('Requirement');
        $nestedTestcases = array();
        $nestedRequirements = array();
        $nestedRequirementList = $this->Requirement->find('list',array('conditions' => array('Requirement.project_id' => $this->Session->read('project_id'))));
        foreach ($nestedRequirementList as $nestedRequirementId=>$nestedRequirementName){
            $nestedRequirements[] = $this->Requirement->find('first', array(
                'conditions'=>array(
                    'Requirement.id'=>$nestedRequirementId
                ),
            	'contain'=>array(
            		'Combination' => array(
            			'Browser',
            			'Operatingsystem'
            		),
            		'Testcase'=> array(
                        'order' => 'Testcase.name'
                    ),
            		'User'
            	)
            ));
            
            //$nestedTestcases = array_merge($nestedTestcases, $this->Requirement->getNestedTestcases($nestedRequirementId));
                
        }
        
        foreach($nestedRequirements as $requirement){
            foreach($requirement['Testcase'] as $testcase)
            $nestedTestcases[] = $testcase['id'];     
        }
        
        
        $stateOfTheSystemErrors = $this->Checker->getStateOfTheSystem();
        $nestedCombinations = $this->Checker->getCombinations($nestedRequirements,true);
        
        
        //$nestedTestcases = $this->Requirement->getNestedTestcases($id);
        
        
        $onlineNodes = $this->Checker->getOnlineNodes();
        $nestedOfflineNeeds = $this->Checker->getOfflineNeeds($onlineNodes, $nestedRequirements, true);
        $nestedNoScripts = $this->Checker->getTestcasesWithNoScript($nestedRequirements,true);
        $nestedOfflineNeeds = $this->Checker->getOfflineNeeds($onlineNodes, $nestedRequirements, true);

        if(count($nestedNoScripts) == count($nestedTestcases)){
            $this->set('noScriptsAll', true);
        }
        

        $this->set('stateOfTheSystemErrors',$stateOfTheSystemErrors);
        $this->set('nestedCombinations',$nestedCombinations);
        $this->set('nestedTestcases',$nestedTestcases);
        $this->set('nestedOfflineNeeds',$nestedOfflineNeeds);
        $this->set('nestedNoScripts',$nestedNoScripts);
        $this->set('nestedOfflineNeeds',$nestedOfflineNeeds);
        $this->set('onlineNodes',$onlineNodes);
        $this->set('nodes', $this->Checker->getAllNodes());
        
        $this->loadModel('Config');
        $this->set('servername', $this->Config->field('value', array('name'=>'servername')));
        $this->set('port', $this->Config->field('value', array('name'=>'port')));
        
	}
	
	function select($project_id = null, $noredirect = false){
        $this->statistics(true);
        $this->layout = "green_blank";
        if(empty($project_id)){
            $project_id = $this->data['Project']['project_id'];
        }

        if($project_id){
            foreach($this->userprojects as $userproject){
                $userprojects_list[] = $userproject['id']; 
            }
            if(in_array($project_id, $userprojects_list)){
                $project = $this->Project->findById($project_id);
    			if ($this->Session->write('project_id',$project_id) && $this->Session->write('project_name',$project['Project']['name'])) {
                    if($noredirect === false){
                        if($this->referer()=='/projects/select'){
                            $this->redirect('/testlabs#/projects/testlabview');
                        }else{
                            $this->redirect($this->referer());
                        }
                    }
    			}else {
    				$this->Session->setFlash(__('The project session could not be set. Please, try again.', true), true, array('class'=>'error_message'));
    			}
            }else{
                $this->Session->setFlash(__('You do not have access to this project.', true), true, array('class'=>'error_message'));
            }
		}
		
	    if(!empty($this->data)){
            $this->Session->setFlash(__('No project selected. Please, try again.', true), true, array('class'=>'error_message'));
        }
    }

}
