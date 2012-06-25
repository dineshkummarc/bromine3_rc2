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
class Browser extends AppModel {

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $hasMany = array(
			'Combination' => array('className' => 'Combination',
								'foreignKey' => 'browser_id',
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
			'Test' => array('className' => 'Test',
								'foreignKey' => 'browser_id',
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
			'Node' => array('className' => 'Node',
						'joinTable' => 'browsers_nodes',
						'foreignKey' => 'browser_id',
						'associationForeignKey' => 'node_id',
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
                'message' => 'Browser name can\'t be empty'
             ),
            'nameRule-2' => array(
                'rule' => 'isUnique',  
                'message' => 'The is already a browser with that name'
            )  
        ),
        'path' => array(
            'pathRule-1' => array(
                'rule' => 'notempty',  
                'message' => 'Browser path can\'t be empty'
             ) 
        )
    );
	
	function afterSave($created){
	    $id = $this->id;
        if ($created){
            $text = "Added the browser: <a href='/requirements#/browsers/view/$id'>" . $this->data['Browser']['name'] . '(' . $this->data['Browser']['path'] . ')</a>';
		    
		    $this->Combination->Operatingsystem->recursive = 0;
		    $all_os = $this->Combination->Operatingsystem->find('all');
		    		    
		    foreach ($all_os as $value) {
		        $data = array();
		        $data['Combination']['operatingsystem_id'] = $value['Operatingsystem']['id'];
		        $data['Combination']['browser_id'] = $id;
		        $this->Combination->create();
		        $this->Combination->save($data);  
            }
        }else{
            $text = "Edited the browser: <a href='/requirements#/browsers/view/$id'>" . $this->data['Browser']['name'] . "</a>";
        }
        $this->saveActivity($text);
    }
    
    function beforeDelete(){
        $data = $this->findById($this->id);
        $text = "Deleted the browser: " . $data['Browser']['name'];
        $this->saveActivity($text);
        return true;
    }
    
    function afterDelete(){
        
    }
}
?>