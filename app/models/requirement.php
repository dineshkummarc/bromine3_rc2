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
class Requirement extends AppModel {
    
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
			'Project' => array('className' => 'Project',
								'foreignKey' => 'project_id',
								'conditions' => '',
								'fields' => '',
								'order' => ''
			),
			'User' => array('className' => 'User',
								'foreignKey' => 'user_id',
								'conditions' => '',
								'fields' => '',
								'order' => ''
			)
	);

	var $hasAndBelongsToMany = array(
			'Combination' => array('className' => 'Combination',
						'joinTable' => 'combinations_requirements',
						'foreignKey' => 'requirement_id',
						'associationForeignKey' => 'combination_id',
						'unique' => true,
						'conditions' => '',
						'fields' => '',
						'order' => '',
						'limit' => '',
						'offset' => '',
						'finderQuery' => '',
						'deleteQuery' => '',
						'insertQuery' => ''
			),
			'Testcase' => array('className' => 'Testcase',
						'joinTable' => 'requirements_testcases',
						'foreignKey' => 'requirement_id',
						'associationForeignKey' => 'testcase_id',
						'unique' => true,
						'conditions' => '',
						'fields' => '',
						'order' => '',
						'limit' => '',
						'offset' => '',
						'finderQuery' => '',
						'deleteQuery' => '',
						'insertQuery' => ''
			)
	);

    var $validate = array(
        'name' => array(
            'nameRule-1' => array(
                'rule' => 'notempty',  
                'message' => 'Requirement name can\'t be empty'
             )
        )
    );
    
    function getStatus($requirement_id, $site_id){ 
        $status = Cache::read('requirement_get_status_'.$requirement_id.'_'.$site_id);
        if ($status !== false) return $status;

        $requirement_ids = $this->getNestedRequirements($requirement_id);
        $results = array();
        foreach($requirement_ids as $requirement_id){
            $requirement = $this->find('first', array(
                'conditions' => array(
                    'id' => $requirement_id
                ),
                'contain' => array(
                    'Testcase'
                )
            ));
            foreach($requirement['Testcase'] as $testcase){
                if(($result = $this->Testcase->getStatus($testcase['id'], $requirement_id, $site_id))!=null){
                    $results[] = $result;
                }
            }    
        }

        $status = 'passed';
        if(empty($results)) $status = '';
        if(in_array('notdone', $results)) $status = 'notdone';
        if(in_array('failed', $results)) $status = 'failed';
        
        Cache::write('requirement_get_status_'.$requirement_id.'_'.$site_id, $status);
        return $status;
    }

    function getNestedRequirements($requirement_id){ 
		$requirement = $this->find('first', array(
            'conditions'=>array(
                'Requirement.id'=>$requirement_id
            )
        ));
        $children = $this->find('all', array(
            'conditions'=>array(
                'Requirement.parent_id'=>$requirement_id
            )
        ));
		
		if(!empty($children)){
            foreach($children as $child){
                //if ($child['Requirement']['id'] != $requirement_id){
                    $requirements[] = $this->getNestedRequirements($child['Requirement']['id']);
                //}
            }
        }
        
        $requirements[] = $requirement['Requirement']['id'];
        // Black magic from php.net
        $objTmp = (object) array('aFlat' => array());
        array_walk_recursive($requirements, create_function('&$v, $k, &$t', '$t->aFlat[] = $v;'), $objTmp); //Crazy stuff from php.net
        $requirements = $objTmp->aFlat;
        return $requirements;
    }
    
    function getNestedTestcases($requirement_id){
        $testcases = array();
        $requirements = $this->getNestedRequirements($requirement_id);
        foreach($requirements as $requirement_id){
            $requirement = $this->find('first', array(
                'conditions'=>array(
                    'Requirement.id'=>$requirement_id
                )
            ));
            foreach ($requirement['Testcase'] as $testcase){
                $testcases[] = $testcase['id'];    
            }    
        }
        return $testcases;
    }
    
    function beforeDelete(){
        $data = $this->findById($this->id);
        $text = "Deleted the requirement: " . $data['Requirement']['name'];
        $this->saveActivity($text);
        return true;
    } 

    function afterSave($created){
        $id = $this->id;
        if($created){
            $text = "Added the requirement: <a href='/requirements#/requirements/view/$id'>" . $this->data['Requirement']['name'] . "</a>"; 
        }else{
            $id =  $this->data['Requirement']['id'];
            $req = $this->read(null,$id);
            $text = "Edited the requirement: <a href='/requirements#/requirements/view/$id'>" . $req['Requirement']['name'] . "</a>";
        }
        $this->saveActivity($text);
        
        $requirement = $this->find('first',array(
        	'order' => array('Requirement.id'),
        	'contain' => array(
                'Testcase'
                )
        ));
        $sites = $this->Testcase->Test->Suite->Site->find('all', array(
            'conditions' => array(
                'Site.project_id' =>  $requirement['Requirement']['project_id']
            )
        ));
        
        foreach($sites as $site){
            $site_id = $site['Site']['id'];
            Cache::delete('requirement_get_status_'.$requirement['Requirement']['id'].'_'.$site_id);
            foreach ($requirement['Testcase'] as $testcase){
                Cache::delete('testcase_get_status_'.$testcase['id'].'_'.$requirement['Requirement']['id'].'_'.$site_id);
            }
        }
        
    }
    
    function deleteCache($requirement_id, $site_id){
        $requirement = $this->findById($requirement_id);
        Cache::delete('requirement_get_status_'.$requirement['Requirement']['id'].'_'.$site_id);
        
        if($requirement['Requirement']['parent_id'] != 0){
            $this->deleteCache($requirement['Requirement']['parent_id'], $site_id);
        }   
    }
    
}
?>
