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
class Command extends AppModel {
    
    function afterSave($created){

        clearCache(array('testlabs','projects_testlabsview/','requirements_testlabview'));
        $command = $this->find('first',array(
            'conditions' => array(
                 'Command.id' => $this->getLastInsertID()
            ),
            'contain' =>  array(
                'Test' => array(
                    'Suite'
                )
            )
        ));
        //Debugger::log("getStatus.".$command['Test']['testcase_id'].'.'.$command['Test']['operatingsystem_id'].'.'.$command['Test']['browser_id'].'.'.$command['Test']['Suite']['site_id']);
        //Cache::delete("getStatus.".$command['Test']['testcase_id'].'.'.$command['Test']['operatingsystem_id'].'.'.$command['Test']['browser_id'].'.'.$command['Test']['Suite']['site_id'].date("Ymd"));
        Cache::delete("getStatus.".$command['Test']['testcase_id'].'.'.$command['Test']['operatingsystem_id'].'.'.$command['Test']['browser_id'].'.'.$command['Test']['Suite']['site_id'].'.');
        Cache::delete("getStatus.".$command['Test']['testcase_id'].'.'.$command['Test']['operatingsystem_id'].'.'.$command['Test']['browser_id'].'.'.$command['Test']['Suite']['site_id']);
    }
    
    //WHERE command.test_id = test.id and test.suite_id = suite.id and suite.project_id = project_id
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
			'Test' => array('className' => 'Test',
								'foreignKey' => 'test_id',
								'conditions' => '',
								'fields' => '',
								'order' => ''
			)
	);

}
?>
