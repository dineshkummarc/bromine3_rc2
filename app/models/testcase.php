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
class Testcase extends AppModel {

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

	var $hasMany = array(
			'TestcaseStep' => array('className' => 'Testcasestep',
								'foreignKey' => 'testcase_id',
								'dependent' => false,
								'conditions' => '',
								'fields' => '',
								'order' => '',
								'limit' => '',
								'offset' => '',
								'exclusive' => '',
								'finderQuery' => '',
								'counterQuery' => ''
			),
			'Test' => array('className' => 'Test',
								'foreignKey' => 'testcase_id',
								'dependent' => false,
								'conditions' => '',
								'fields' => '',
								'order' => '',
								'limit' => '',
								'offset' => '',
								'exclusive' => '',
								'finderQuery' => '',
								'counterQuery' => ''
			)
	);

	var $hasAndBelongsToMany = array(
			'Requirement' => array('className' => 'Requirement',
						'joinTable' => 'requirements_testcases',
						'foreignKey' => 'testcase_id',
						'associationForeignKey' => 'requirement_id',
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
                'message' => 'Testcase name can\'t be empty'
             )  
        )
    );

    function getStatus($testcase_id, $requirement_id, $site_id){
        $status = Cache::read('testcase_get_status_'.$testcase_id.'_'.$requirement_id.'_'.$site_id);
        if ($status !== false) return $status; //If cached result exist: return it.
        
		$requirement = $this->Requirement->find('first', array(
            'conditions'=>array(
                'Requirement.id' => $requirement_id
            ),
        	'contain'=>array(
        	    'Testcase',
        		'Combination' => array(
        			'Browser',
        			'Operatingsystem'
        		)
        	)
        ));
        
        if(empty($requirement['Combination'])) return null; //If no combinations defined: return null
        
        $results = array();
        foreach ($requirement['Combination'] as $combination){
            $results[] = $this->Test->getStatus($testcase_id, $combination['Operatingsystem']['id'], $combination['Browser']['id'], $site_id);
        }

        $status = 'passed';
        if(in_array('running', $results)) $status = 'running';
        if(in_array('notdone', $results)) $status = 'notdone';
        if(in_array('failed', $results)) $status = 'failed';
        
        
        Cache::write('testcase_get_status_'.$testcase_id.'_'.$requirement_id.'_'.$site_id, $status);
        return $status;
        
    }

    function beforeDelete(){
        $data = $this->findById($this->id);
        $text = "deleted the testcase: " . $data['Testcase']['name'];
        $this->saveActivity($text);
        return true;
    } 
    
    function afterSave($created){
        $id = $this->id;
        $pos = strpos($this->data['Testcase']['name'],'Clone');
        $pos === false ? $isClone = false : $isClone = true;
        
        if($created){
            if($isClone == false){
                $text = "added the testcase: <a href='/requirements#/testcases/view/$id'>" . $this->data['Testcase']['name'] . "</a>";
            }else{
                $text = "cloned a testcase into this: <a href='/requirements#/testcases/view/$id'>" . $this->data['Testcase']['name'] . "</a>";
            } 
        }else{
            if($isClone == false){
                $text = "edited the testcase: <a href='/requirements#/testcases/view/$id'>" . $this->data['Testcase']['name'] . "</a>";
            }else{
                $text = false;
            } 
            
        }
        $text != false ? $this->saveActivity($text) : '';
        //$this->saveActivity($text);
        /*
        if ($isClone == false){
        }elseif($isClone == true){
            $tc = $this->findById($id);
            pr($tc);
            if (empty($tc)){
                
            }else{
                $text = false;
            }
        }
        
        */
    }

}
?>
