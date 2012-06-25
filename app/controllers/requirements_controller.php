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
class RequirementsController extends AppController {

	public $helpers = array('Html', 'Form','Tree','Table','Time', 'Scheduler', 'Cache');
    public $needsproject = true;
    public $paginate = array(
        'limit' => 25,
        'order' => array(
            'Requirement.priority' => 'asc',
            'Requirement.nr' => 'asc'
        )
    );
    public $components = array('Script','Googlechart','Scheduler', 'Checker');
    public $name = 'Requirements';

     function reorder($id=null, $parent_id = 0){
        if($parent_id == 'null') $parent_id = 0;    
        //var_dump($parent_id);    
        if(isset($id) && isset($parent_id)){
            $this->data['Requirement']['id'] = $id;
            $this->data['Requirement']['parent_id'] = $parent_id;
            $this->Requirement->save($this->data);
        }
    }
    
    function importFromCSV(){
        $filename = $this->data['Requirement']['datafile']['tmp_name'];
        $type = $this->data['Requirement']['type'];
        $seperator = $this->data['Requirement']['seperator'];
        if (file_exists($filename)){
            //$this->layout = 'green_blank';
            if (!empty($this->data)){
                $this->loadModel('Testcase');
                $row = 1;
                $class = '';
                if (($handle = fopen($filename, "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, $seperator)) !== FALSE) {
                        $num = count($data);
                        $row++;
                        $allowedtypes = array('Requirement', 'Testcase');
                        if (in_array($type, $allowedtypes)){
                            
                            if (isset($data[0]) && isset($data[1])){
                                $reqdata = array();
                                $reqdata[$type]['id'] = '';
                                $reqdata[$type]['name'] = $data[0];
                                $reqdata[$type]['description'] = $data[1];
                                $reqdata[$type]['project_id'] = $this->Session->read('project_id');
                                if ($type == 'Requirement'){
                                    $reqdata[$type]['parent_id'] = 0;
                                }
                                $reqdata[$type]['user_id'] = $this->Auth->user('id');
                                if ($this->$type->save($reqdata)){
                                    $result[] = "$type '".$data[0]. "' was created with '<b>".$this->Auth->user('name')."</b>' as owner<br />";    
                                }
                            }elseif (!isset($data[0])){
                                $result[] = "$type was not saved. Because name (first column in file) was not set. Did you select the correct seperator?<br />";
                                $class = 'error';
                            }elseif (!isset($data[1])){
                                $result[] = "$type $data[0] was not saved. Because description (second column in file) was not set. Did you select the correct seperator?<br />";
                                $class = 'error';
                            }
                        }
                                   
                    }
                    fclose($handle);
                }else{
                    $this->Session->setFlash("Can't open file '$filename'", true, array('class' => 'error_message'));
                }
                //pr($result);
                $class == '' ? $class = 'notice' : '';
                $this->set('result',$result);            
                $this->set('class',$class);
            }
        }
        elseif($type != ''){
            if ($filename == ''){
                $this->Session->setFlash("You have to select a file by using the browse button", true,array('class' => 'error_message'));
            }else{
                $this->Session->setFlash("Can't open file '$filename'", true,array('class' => 'error_message')); 
            }
                  
        }       
    }
    
    function updatetc($case,$testcase_id=null,$requirement_id=null){    
        $testcase_id = end(explode('_', $testcase_id));
        $requirement_id = end(explode('_', $requirement_id));

        $data = $this->Requirement->read(null, $requirement_id);
        $savedata['Requirement']['id'] =  $requirement_id;
        foreach($data['Testcase'] as $testcase){
            $savedata['Testcase']['Testcase'][] = $testcase['id'];
        }
        
        foreach($data['Combination'] as $combination){
            $savedata['Combination']['Combination'][] = $combination['id'];
        }
        
        if($case == 'add'){
            $savedata['Testcase']['Testcase'][] = $testcase_id;
        }elseif($case == 'remove'){
            if(($key=array_search($testcase_id, $savedata['Testcase']['Testcase']))!==false){
                unset($savedata['Testcase']['Testcase'][$key]);
            }else{
                return false;
            }
        }

        $this->Requirement->save($savedata);
    }
    
    function updateCombination($browser_id, $os_id, $requirement_id){

        $combination = $this->Requirement->Combination->find('first',array('conditions'=>array('browser_id'=>$browser_id, 'operatingsystem_id' => $os_id)));

        $combination_id = $combination['Combination']['id'];
        $requirement = $this->Requirement->read(null,$requirement_id);

        $savedata['Requirement']['id'] = $requirement_id;
        
        foreach($requirement['Combination'] as $combination){
            $savedata['Combination']['Combination'][] = $combination['id'];
        }

        foreach($requirement['Testcase'] as $testcase){
            $savedata['Testcase']['Testcase'][] = $testcase['id'];
        }
        
        if(!empty($savedata['Combination']) && ($key=array_search($combination_id, $savedata['Combination']['Combination']))!==false){
            unset($savedata['Combination']['Combination'][$key]);
        }else{
            $savedata['Combination']['Combination'][] = $combination_id;
        }
        
    
        $this->Requirement->save($savedata);
       
    }
    
	function index() {
	   
		//$this->Requirement->recursive = 1;
		$this->set('data',$this->Requirement->find('threaded', 
            array(
                'conditions' => array(
                    'project_id' => $this->Session->read('project_id')
                ),
                'contain'=>array(
            		'Testcase'=>array(
                        'order' => 'Testcase.name'
                    ),
            		'Project'
            		)
            	)
        ));
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Requirement.', true));
			$this->redirect(array('action'=>'index'));
		}
        //$this->Requirement->recursive = 2;
		//$requirements = $this->Requirement->find("Requirement.id=$id");
		$this->Requirement->Behaviors->attach('Containable');
		$requirements = $this->Requirement->find('first', array(
            'conditions'=>array(
                'Requirement.id'=>$id
            ),
        	'contain'=>array(
        		'Combination' => array(
        			'Browser',
        			'Operatingsystem'
        		), 'User'
        	)
        ));
		$this->set('requirement', $requirements);
		$this->set('combinations',$requirements['Combination']);
		$this->Requirement->Combination->Browser->recursive = -1;
		$this->Requirement->Combination->Operatingsystem->recursive = -1;
		$this->set('browsers',$this->Requirement->Combination->Browser->find('all'));
		$this->set('operatingsystems',$this->Requirement->Combination->Operatingsystem->find('all'));
	}
	
	function testlabview($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Requirement.', true));
			$this->redirect(array('action'=>'index'));
		}

		$this->Requirement->Behaviors->attach('Containable');
		$requirements = $this->Requirement->find('first', array(
            'conditions'=>array(
                'Requirement.id'=>$id
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
        
        
        
        $nestedRequirementList = $this->Requirement->getNestedRequirements($id);
        foreach ($nestedRequirementList as $nestedRequirementId){
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
        }
        //General stuff
        $noScripts = array();
        $onlineNodes = $this->Checker->getOnlineNodes();
        $this->set('onlineNodes', $onlineNodes);
        $this->set('nodes', $this->Checker->getAllNodes());
        
        //Checks for the selected requirement
        $combination = $this->Checker->getCombinations($requirements);
        //pr($combination);
        $noScripts = $this->Checker->getTestcasesWithNoScript($requirements);
        
        $this->set('offlineNeeds',$this->Checker->getOfflineNeeds($onlineNodes, $combination));
        $this->set('combinations', $combination);
        $this->set('onlineCombinations', $this->Checker->getOnlineCombinations($onlineNodes));

        $nestedTestcases = $this->Requirement->getNestedTestcases($id);
        $this->set('nestedTestcases', $nestedTestcases);

        //Checks for for nested requirements
        $nestedNoScripts = $this->Checker->getTestcasesWithNoScript($nestedRequirements,true);
        $nestedCombinations = $this->Checker->getCombinations($nestedRequirements,true);
        $nestedOfflineNeeds = $this->Checker->getOfflineNeeds($onlineNodes, $nestedRequirements, true);
        $nestedRequirements = $this->Requirement->getNestedRequirements($id);

        $this->set('nestedNoScripts', $nestedNoScripts);
        $this->set('nestedCombinations', $nestedCombinations);
        $this->set('nestedOfflineNeeds', $nestedOfflineNeeds);
        $this->set('nestedRequirements', $nestedRequirements);
        //pr($nestedOfflineNeeds);
        //$noScripts=array_merge($noScripts, $nestedNoScripts);
        $this->set('noScripts', $noScripts);
        
        if(count($requirements['Testcase']) == count($noScripts)){
            $this->set('noScriptsAll', true);
        }
        if(count($nestedTestcases) == count($nestedNoScripts)){
            $this->set('nestedNoScriptsAll', true);
        } 

		$this->set('requirement', $requirements);
		$this->set('testcases',$requirements['Testcase']);
		
		
		
		
        $this->Requirement->Combination->Browser->recursive = -1;
		$this->Requirement->Combination->Operatingsystem->recursive = -1;
		$this->set('browsers',$this->Requirement->Combination->Browser->find('all'));
		$this->set('operatingsystems',$this->Requirement->Combination->Operatingsystem->find('all'));

        $this->set('stateOfTheSystemErrors', $this->Checker->getStateOfTheSystem());
        
        $this->set('cron', $this->Scheduler->getCron('Req',$id, $this->Session->read('site_id'), true));
        
        $this->loadModel('Config');
        $this->set('servername', $this->Config->field('value', array('name'=>'servername')));
        $this->set('port', $this->Config->field('value', array('name'=>'port')));
	}
	
	

	function add() {
		if (!empty($this->data)) {
			$this->Requirement->create();
			if ($this->Requirement->save($this->data)) {
				$this->Session->setFlash(__('The Requirement has been saved', true));
				$this->redirect(
    				array(
    				    'controller'=>'requirements#/requirements',
    				    'action' => 'edit',
    				    $this->Requirement->id
                    )
                );
			} else {
				$this->Session->setFlash('The Requirement could not be saved. Please, try again.', true, array('class' => 'error_message'));
			}
		}
		$requirements = $this->Requirement->find('list',array('conditions' => array('project_id' => $this->Session->read('project_id'))));
		$requirements[0] = 'No parent';
        $testcases = $this->Requirement->Testcase->find('list');
		$this->set(compact('testcases','requirements'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Requirement', true, true, array('class' => 'error_message')));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->Requirement->save($this->data)) {
				$this->Session->setFlash(__('The Requirement has been saved', true));
                                $this->redirect('requirements/view/'. $id);
			} else {
				$this->Session->setFlash(__('The Requirement could not be saved. Please, try again.', true, true, array('class' => 'error_message')));
			}
		}
		
		if ($id) {
		    $this->data = $this->Requirement->read(null, $id);
			if ($this->data['User']['id'] == $this->Auth->user('id') || $this->Auth->user('group_id') == 1){ // Check if you are the owner of the req or an admin
                $this->Requirement->Behaviors->attach('Containable');
        		$requirements = $this->Requirement->find('first', array(
                    'conditions'=>array(
                        'Requirement.id'=>$id
                    ),
                	'contain'=>array(
                		'Combination' => array(
                			'Browser',
                			'Operatingsystem'
                		)
                	)
                ));
                $this->set('requirement', $requirements);
    			$this->set('combinations',$requirements['Combination']);
    			$this->Requirement->User->recursive = -1;
    			$users_sql = $this->Requirement->User->find('all',array('fields' => array('User.id','User.firstname' , 'User.lastname')));
    			foreach($users_sql as $user){
    			     $users[$user['User']['id']] = $user['User']['firstname'] . ' ' . $user['User']['lastname'];
                
                }
		        $this->set(compact('users'));
        		$this->Requirement->Combination->Browser->recursive = -1;
        		$this->Requirement->Combination->Operatingsystem->recursive = -1;
        		$this->set('browsers',$this->Requirement->Combination->Browser->find('all'));
        		$this->set('operatingsystems',$this->Requirement->Combination->Operatingsystem->find('all'));
        	}else{
                $this->Session->setFlash("Error: You are not allowed to edit this requirement. You should either be an admin or owner of the requirement", true, array('class' => 'error_message'));
                $this->redirect($this->referer() . '/view/'. $id);
            }
		}
		//$testcases = $this->Requirement->Testcase->find('list');
		//$this->set(compact('testcases'));
		
		
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Requirement', true), true, array('class' => 'error_message'));
			$this->redirect(array('controller'=>'requirements','action'=>'index'));
		}
		if ($this->Requirement->del($id)) {
            //Free child requirements
            $this->Requirement->updateAll(array('parent_id' => 0), array('parent_id' => $id));
            //Delete schedules  
		    App::import('Model', 'Site');
	        $this->Site = new Site();
	        $sites = $this->Site->find('list', array('conditions' => array('Site.project_id' => $this->Session->read('project_id')), 'fields' => array('Site.id')));
            foreach($sites as $site){
                $this->deleteSchedule($id, $site);
            }
			$this->Session->setFlash(__('Requirement deleted', true));
			$this->redirect(array('controller'=>'requirements','action'=>'index'));
		}
	}
	
	function copy(){
        $this->Requirement->recursive = 1;
        $req = $this->Requirement->read(null,$this->data['Project']['requirement_id']);
        
        unset($req['Project']);
        unset($req['User']);
        unset($req['Requirement']['id']);
        
        $req['Requirement']['project_id'] = $this->data['Project']['project_id'];
        $this->Requirement->saveAll($req['Requirement']);
        $id = $this->Requirement->id;

        foreach ($req['Testcase'] as $key=>$value) {
            unset($value['RequirementsTestcase']);
            unset($value['id']);
            $value['project_id'] = $this->data['Project']['project_id'];
            //$value['requirement_id'] = $id;
            $output['Testcase'] = $value;
            //pr($output);
            
            $this->Requirement->Testcase->saveAll($output);
            $testcase_ids[] = $this->Requirement->Testcase->id;
        }

        $relations = array('Requirement' => array(
                'id' => $id
                ),
                'Testcase' => array(
                    'Testcase' => $testcase_ids
                    )               
            );
        if ($this->Requirement->save($relations)){
            $this->Session->setFlash('The requirement has been copied', true);
            $this->redirect($this->referer());
        }else{
            $this->Session->setFlash('The requirement has NOT been copied, please try again',true, true, array('class' => 'error_message'));
            $this->redirect($this->referer());
        }
    }
    
    function saveSchedule($modify = false) {
        $outputs = $this->Scheduler->saveSchedule('Requirement', 'Req', $modify);
        foreach($outputs as $key => $output) {
            $this->set($key, $output);
        }
        $this->render('schedule');
    }

    function modifySchedule() {
        $this->saveSchedule(true);
    }

    function deleteSchedule($id, $site_id, $url=null) {
        $outputs = $this->Scheduler->deleteSchedule('Req', $id, $site_id, $url);
        foreach($outputs as $key => $output) {
            $this->set($key, $output);
        }
        $this->render('schedule');
    }
}
