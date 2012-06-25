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
class Operatingsystem extends AppModel {

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $hasMany = array(
			'Combination' => array('className' => 'Combination',
								'foreignKey' => 'operatingsystem_id',
								'dependent' => true, //NOTICE: TRUE
								'conditions' => '',
								'fields' => '',
								'order' => '',
								'limit' => '',
								'offset' => '',
								'exclusive' => '',
								'finderQuery' => '',
								'counterQuery' => ''
			),
			'Node' => array('className' => 'Node',
								'foreignKey' => 'operatingsystem_id',
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
								'foreignKey' => 'operatingsystem_id',
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
	
    var $validate = array(
        'name' => array(
            'nameRule-1' => array(
                'rule' => 'notempty',  
                'message' => 'Operating system name can\'t be empty'
             ),
            'nameRule-2' => array(
                'rule' => 'isUnique',  
                'message' => 'The is already an operating system with that name'
            )  
        )
    );

    function afterSave($created){
        $id = $this->id;
        if ($created){
            $text = "Added the operating system: <a href='/requirements#/operatingsystems/view/$id'>" . $this->data['Operatingsystem']['name'] . "</a>";
            
			    
            $this->Combination->Browser->recursive = 0;
            $all_browser = $this->Combination->Browser->find('all');
            	    
            foreach ($all_browser as $value) {
                $data = array();
                $data['Combination']['browser_id'] = $value['Browser']['id'];
                $data['Combination']['operatingsystem_id'] = $id;
                $this->Combination->create();
                $this->Combination->save($data);
            }
        }else{
            $text = "Edited the operating system: <a href='/requirements#/operatingsystems/view/$id'>" . $this->data['Operatingsystem']['name'] . "</a>";
        }
        $this->saveActivity($text);
    }
    
    function beforeDelete(){
        $data = $this->findById($this->id);
        $text = "Deleted the operating system: " . $data['Operatingsystem']['name'];
        $this->saveActivity($text);
        return true;
    }

}
?>