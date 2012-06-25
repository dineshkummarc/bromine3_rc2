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
class User extends AppModel {
    var $validate = array(
            'name' => array(
                            'notEmpty' => array(
                                            'rule' => 'notEmpty',
                                            'message' => 'This field cannot be left blank',
                                            'last' => true
                            ),
                            'isUnique' => array(
                                            'rule' => 'isUnique',
                                            'message' => 'User already exist'
                            )
            ),
            'firstname' => array('notempty'),
            'lastname' => array('notempty'),
            'email' => array('notempty', 'email'),
            'password' => array(
                            'rule' => array('comparison', '!=', '281e58d9ed1a8a712a1462bb5f8984ca91334eb8'),
                            'message' => 'Password can\'t be left blank'
            )
    );

    var $belongsTo = array(
            'Group' => array('className' => 'Group',
                            'foreignKey' => 'group_id',
                            'conditions' => '',
                            'fields' => '',
                            'order' => ''
            )
    );

    var $hasAndBelongsToMany = array(
            'Project' => array('className' => 'Project',
                            'joinTable' => 'projects_users',
                            'foreignKey' => 'user_id',
                            'associationForeignKey' => 'project_id',
                            'unique' => true,
                            'conditions' => '',
                            'fields' => '',
                            'order' => 'name',
                            'limit' => '',
                            'offset' => '',
                            'finderQuery' => '',
                            'deleteQuery' => '',
                            'insertQuery' => ''
            )
    );

    var $hasOne = array(
            'Myaro' => array(
                            'foreignKey' => 'foreign_key',
                            'className'    => 'Myaro',
                            'conditions'   => array('Myaro.model' => 'user'),
                            'dependent'    => true
            )
    );

    function afterSave($created) {
        App::import('Model','Myaro');
        $this->Myaro = new Myaro();

        $this->Myaro->recursive = 0;
        $this->data=am($this->data, $this->Myaro->find(array('foreign_key'=>$this->data['User']['group_id'],'model'=>'group')));
        //pr($this->data);
        if($created===true) { //If a new user has been created
            $aroData = array();
            $aroData['Myaro']['model'] = 'user';
            $aroData['Myaro']['foreign_key'] = $this->getLastInsertID();
            $aroData['Myaro']['parent_id'] = $this->data['Myaro']['id'];
            $aroData['Myaro']['alias'] = $this->data['Myaro']['alias'].'/'.$this->data['User']['name'];
            $this->Myaro->save($aroData);
        }else { //If a user has been updated
            $this->Myaro->updateAll(
                    array(
                    'alias'=>"'".mysql_real_escape_string($this->data['Myaro']['alias']."/".$this->data['User']['name'])."'",
                    'parent_id' => $this->data['Myaro']['id']
                    ),
                    array(
                    'model'=>'user',
                    'foreign_key'=>$this->data['User']['id']
                    )
            );
        }
        /*
        $id = $this->id;
        if($created){
            $text = "Added the following user:  <a href='/requirements#/users/view/$id'>" . $this->data['User']['name'] ."</a>"; 
        }else{
            $text = "Changed the following user:  <a href='/requirements#/users/view/$id'>" . $this->data['User']['name'] . "</a>";
        }
        $this->saveActivity($text);
        */
    }
    /*
    function beforeDelete(){
        
        $data = $this->findById($this->id);
        
        $text = "Deleted the following user: " . $data['User']['name'];
        $this->saveActivity($text);
        return true;
    } 
    */

}
?>
