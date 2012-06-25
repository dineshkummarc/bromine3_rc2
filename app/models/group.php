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
class Group extends AppModel {
    
	var $hasOne = array(
        'Myaro' => array(
            'foreignKey' => 'foreign_key',
            'className'    => 'Myaro',
            'conditions'   => array('Myaro.model' => 'group'),
            'dependent'    => true
        )
    );
    
	var $hasMany = array(
    	'User' => array('className' => 'User',
    						'foreignKey' => 'group_id',
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
	
	var $validate = array(
        'name' => array(
            'nameRule-1' => array(
                'rule' => 'notempty',  
                'message' => 'Group name can\'t be empty'
             ),
            'nameRule-2' => array(
                'rule' => 'isUnique',  
                'message' => 'The is already a group with that name'
            )  
        )
    );
	
	function afterSave($created){
        App::import('Model','Myaro');
		$this->Myaro = new Myaro();
		$this->Myaro->recursive = 0;
        
		if($created===true){ //If a new group has been created
            $aroData = array();
    		$aroData['Myaro']['model'] = 'group';
    		$aroData['Myaro']['foreign_key'] = $this->getLastInsertID();
    		$aroData['Myaro']['alias'] = '/'.$this->data['Group']['name'];
            $this->Myaro->save($aroData);
        }else{ //If a group has been updated.
            $this->Myaro->updateAll( //Update the group Aro
                array('alias'=>"'".mysql_real_escape_string("/".$this->data['Group']['name'])."'"),
                array(
                    'model'=>'group',
                    'foreign_key'=>$this->data['Group']['id']
                )
            );
            $this->data=am($this->data, $this->Myaro->find(array('foreign_key'=>$this->data['Group']['id'],'model'=>'group')));
            $affectedUserAros = $this->Myaro->find('all',array('conditions'=>array('parent_id'=>$this->data['Myaro']['id'],'model'=>'user')));
            foreach($affectedUserAros as $affectedUserAro){ //Update the user Aros that are children of the updated group
                $alias = split('/',$affectedUserAro['Myaro']['alias']);
                $alias = '/'.$this->data['Group']['name'].'/'.$alias[2];
                $this->Myaro->updateAll(
                    array('alias'=>"'".mysql_real_escape_string($alias)."'"),
                    array(
                        'id'=>$affectedUserAro['Myaro']['id']
                    )
                );
            }
        }

    }

}
?>
