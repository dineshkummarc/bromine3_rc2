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
class CheckerComponent extends Object
{
    var $controller = true;
    var $components = array('Session','Script');
    var $cacheAction = false;
 
    function startup(&$controller)
    {
        App::import('Model','Node');
        App::import('Model','Requirement');
        
        $this->Node = new Node();
        $this->nodes = $this->Node->find('all');
        
        $this->Requirement = new Requirement();
    }
 
    function getOnlineNodes(){
        //$this->loadModel('Node');
        App::import('Model','Node');
        $this->Node = new Node();
        $nodes = $this->Node->find('all');
        $onlineNodes = array();
        foreach($nodes as &$node){
            if($this->Node->checkJavaServer($node['Node']['nodepath'])){
                $onlineNodes[] = $node;
            }
        }
        return $onlineNodes;
    }
    
    function getAllNodes(){
        return $this->nodes;
    }
    
    function getCombinations($requirements, $recursive = false){
        if (!$recursive){
            foreach ($requirements['Combination'] as &$combination){
                foreach ($requirements['Testcase'] as $testcase){
                    $combination['tc'.$testcase['id']]['status'] = $this->Requirement->Testcase->Test->getStatus($testcase['id'], $combination['Operatingsystem']['id'], $combination['Browser']['id'], $this->Session->read('site_id'));
                    $t = $this->Requirement->Testcase->Test->getLastInCombination($testcase['id'], $combination['Operatingsystem']['id'], $combination['Browser']['id'], $this->Session->read('site_id'));
                    $combination['tc'.$testcase['id']]['timestamp'] = $t['Test']['timestamp'];
                    $combination['tc'.$testcase['id']]['Test_id'] = $t['Test']['id'];
                } 
            }
            return $requirements['Combination'];
        }else{
            foreach($requirements as &$requirement){
                foreach ($requirement['Combination'] as &$combination){
                    foreach ($requirement['Testcase'] as $testcase){
                        $combination['tc'.$testcase['id']]['status'] = $this->Requirement->Testcase->Test->getStatus($testcase['id'], $combination['Operatingsystem']['id'], $combination['Browser']['id'], $this->Session->read('site_id'));
                        $t = $this->Requirement->Testcase->Test->getLastInCombination($testcase['id'], $combination['Operatingsystem']['id'], $combination['Browser']['id'], $this->Session->read('site_id'));
                        $combination['tc'.$testcase['id']]['timestamp'] = $t['Test']['timestamp'];
                        $combination['tc'.$testcase['id']]['Test_id'] = $t['Test']['id'];
                    } 
                }
            }
            $combinations = array();
            foreach ($requirements as $requirement){
                $combinations = array_merge($combinations, $requirement['Combination']);
            }
            return $combinations;
        }
    }
    
    function getOnlineCombinations($onlineNodes){
        $onlineCombinations = array();
        foreach($onlineNodes as $onlineNode){
            foreach($onlineNode['Browser'] as $browser){
                $onlineCombinations[] = $onlineNode['Operatingsystem']['id'].','.$browser['id'];
            }
        }
        return $onlineCombinations;
    }
    
    function getOfflineNeeds($onlineNodes, $combinations, $recursive = false){
        $onlineCombinations = array();
        $offlineNeeds =  array();
        $onlineCombinations = $this->getOnlineCombinations($onlineNodes);
        if ($recursive){
            $requirements = $combinations;
            foreach($requirements as &$combinations){
                $requirementName = $combinations['Requirement']['name'];
                foreach($combinations['Combination'] as $combination){
                    $idCombination = $combination['Operatingsystem']['id'].','.$combination['Browser']['id'];
                    if(!in_array($idCombination,$onlineCombinations)){
                        $offlineNeeds[] = "(Req.: $requirementName): ".$combination['Browser']['name'].' on '.$combination['Operatingsystem']['name'];
                    }
                }
            }
        }else{
            foreach($combinations as $combination){
                $idCombination = $combination['Operatingsystem']['id'].','.$combination['Browser']['id'];
                if(!in_array($idCombination,$onlineCombinations)){
                    $offlineNeeds[] = $combination['Browser']['name'].' on '.$combination['Operatingsystem']['name'];
                }
            }    
        }
        return $offlineNeeds;
    }
    
    function getTestcasesWithNoScript($requirements, $recursive=false){
        $noScripts = null;
        if (!$recursive){
            foreach($requirements['Testcase'] as $testcase){
                if(!$this->Script->getTestScript($testcase['id'])){
                    $noScripts[] = $testcase['name'];
                }
            }
        }else{
            foreach($requirements as $requirement){ 
                foreach($requirement['Testcase'] as $testcase){
                    if(!$this->Script->getTestScript($testcase['id'])){
                        $noScripts[] = "(Req.: ".$requirement['Requirement']['name']."): ".$testcase['name'];
                    }
                }
            } 
        }
        return $noScripts;
    }
    
    function getStateOfTheSystem(){
        $states = $this->requestAction(array('controller'=>'configs', 'action' => 'stateOfTheSystem'));
        $errors = null;
        foreach($states as $state) {
            if($state['status'] == false) {
                $errors[] = $state;       
            }
        }
        return $errors;
    }
    
}
?>