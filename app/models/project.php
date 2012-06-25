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
class Project extends AppModel {
  public $validate = array(
            'name' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'This field cannot be left blank',
                    'last' => true
                ),
                'custom' => array(
                    'rule' => '/^[a-zA-Z0-9\s]{1,}$/i',
                    'message' => 'This field may only contain alphanumeric values and spaces'
                ),
                'isUnique' => array(
                    'rule' => 'isUnique',
                    'message' => 'Project already exist'
                )
            )
        );

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $hasMany = array(
			'Requirement' => array('className' => 'Requirement',
								'foreignKey' => 'project_id',
								'dependent' => true,
								'conditions' => '',
								'fields' => '',
								'order' => '',
								'limit' => '',
								'offset' => '',
								'exclusive' => '',
								'finderQuery' => '',
								'counterQuery' => ''
			),
			'Site' => array('className' => 'Site',
								'foreignKey' => 'project_id',
								'dependent' => true,
								'conditions' => '',
								'fields' => '',
								'order' => '',
								'limit' => '',
								'offset' => '',
								'exclusive' => '',
								'finderQuery' => '',
								'counterQuery' => ''
			),
			'Suite' => array('className' => 'Suite',
								'foreignKey' => 'project_id',
								'dependent' => true,
								'conditions' => '',
								'fields' => '',
								'order' => '',
								'limit' => '',
								'offset' => '',
								'exclusive' => '',
								'finderQuery' => '',
								'counterQuery' => ''
			),
			'Testcase' => array('className' => 'Testcase',
								'foreignKey' => 'project_id',
								'dependent' => true,
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
			'User' => array('className' => 'User',
						'joinTable' => 'projects_users',
						'foreignKey' => 'project_id',
						'associationForeignKey' => 'user_id',
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
			'Report' => array(
            			'className' => 'Report',
            			'joinTable' => 'projects_reports',
            			'foreignKey' => 'project_id',
            			'associationForeignKey' => 'report_id',
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
	
	function afterSave($created){
        /*
        if (!$created){
            $text = "Edited this project";
            $this->saveActivity($text);
        }
        */
        
    }


}
?>