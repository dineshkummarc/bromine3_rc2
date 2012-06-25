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
class SelftestController extends AppController {

	public $helpers = array('Html', 'Form');
	public $uses = array();
	
	function build(){
        App::import('Core', 'File');
        $controllers = Configure::listObjects('controller');
        
        //Clean controllers
        $controllerWhiteList = array('App','Selftest','BuildAcl','Pages');
        foreach($controllerWhiteList as $controller){
            $index = array_search($controller, $controllers);
            if ($index !== false ) {
                unset($controllers[$index]);
            }
        }
        $baseMethods = get_class_methods('Controller');
        array_push($baseMethods, 'tic');
        array_push($baseMethods, 'toc');
        
        // look at each controller in app/controllers
        foreach ($controllers as &$controller) {
            App::import('Controller', $controller);
            $methods = get_class_methods($controller . 'Controller');
            
            //clean the methods. to remove those in Controller and private actions.
            
            foreach ($methods as $k => $method) {
                if (strpos($method, '_', 0) === 0) {
                    unset($methods[$k]);
                    continue;
                }
                if (in_array($method, $baseMethods)) {
                    unset($methods[$k]);
                    continue;
                }
            }
            
            $controllers2[$controller] = $methods;
            
        }
        //pr($controllers2);
        App::import('Model','Project');
        $this->Project = new Project();
        
        App::import('Model','Testcase');
        $this->Testcase = new Testcase();
        
        App::import('Model','Requirement');
        $this->Requirement = new Requirement();
        
        $this->log = array();
        
        $project = $this->Project->find('first',array('conditions'=>array('Project.name'=>'selftest')));
        if(empty($project)){ //create project
            $this->data['Project']['name'] = 'selftest';
            $this->Project->save($this->data);
            $project_id = $this->Project->id;
            $this->log[] = "Created project 'selftest'";
        }else{
            $project_id = $project['Project']['id'];
        }
        
        $requirement = $this->Requirement->find('first',array('conditions'=>array('Requirement.name'=>'selftest', 'Requirement.project_id'=>$project_id)));
        if(empty($requirement)){ //create project
            $this->data['Requirement']['name'] = 'selftest';
            $this->data['Requirement']['project_id'] = $project_id;
            $this->Requirement->save($this->data);
            $requirement_id = $this->Requirement->id;
            $this->log[] = "Created requirement 'selftest'";
        }else{
            $requirement_id = $requirement['Requirement']['id'];
        }
        
        foreach($controllers2 as $controller => $methods){ 
            foreach($methods as $method){//create testcases
                $testcase = $this->Testcase->find('first',array('conditions'=>array('Testcase.name'=>"$controller: $method")));
                if(empty($testcase)){
                    $this->data = null;
                    $this->data['Testcase']['name'] = "$controller: $method";
                    $this->data['Testcase']['project_id'] = $project_id;
                    $this->data['Requirement']['Requirement'][] = $requirement_id;
                    $this->Testcase->create();
                    $this->Testcase->save($this->data);
                    $this->log[] = "Created testcase '$controller: $method'";
                }
            }
        }
        pr($this->log);
    }

}
