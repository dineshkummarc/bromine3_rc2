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
class Test extends AppModel {
    var $site_id;
    
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
            'Testcase' => array('className' => 'Testcase',
								'foreignKey' => 'testcase_id',
								'conditions' => '',
								'fields' => '',
								'order' => ''
			),
			
			'Browser' => array('className' => 'Browser',
								'foreignKey' => 'browser_id',
								'conditions' => '',
								'fields' => '',
								'order' => ''
			),
			'Operatingsystem' => array('className' => 'Operatingsystem',
								'foreignKey' => 'operatingsystem_id',
								'conditions' => '',
								'fields' => '',
								'order' => ''
			),
			'Suite' => array('className' => 'Suite',
								'foreignKey' => 'suite_id',
								'conditions' => '',
								'fields' => '',
								'order' => ''
			)
			
	);

	var $hasMany = array(
			'Command' => array('className' => 'Command',
								'foreignKey' => 'test_id',
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
	
	var $hasOne = array(
        'Seleniumserver' => array(
            'className'    => 'Seleniumserver'
        )
    );
	
	
	function kill($test_id, $message = null){
        $this->log("Called with: test_id = '$test_id', message: '$message'", 'test_kill');
        $Config = ClassRegistry::init('Config');
        $servername = $Config->field('value', array('name'=>'servername'));
        $port = $Config->field('value', array('name'=>'port'));
        
        $command['Command']['action'] = 'Test terminated';
        $command['Command']['var1'] = $message;
        $command['Command']['test_id'] = $test_id;
        $command['Command']['status'] = 'failed';
        
        $this->Command->create();        
        $this->Command->save($command);
        
        $sessionId = $this->Seleniumserver->field('session_id', array('test_id' => $test_id));
        if($sessionId !== false && !empty($sessionId)){
            $handle = fopen("http://".$servername.':'.$port."/selenium-server/driver/?cmd=testComplete&sessionId=$sessionId",'r');
            fclose($handle);
        }else{
            $test = $this->findById($test_id);
            $test['Test']['status'] = 'failed';
            $this->save($test);
            $this->Seleniumserver->deleteAll(array('Seleniumserver.test_id' => $test_id));    
        }    
    }
    
    function getLastInCombination($testcase_id, $os_id, $browser_id, $site_id){
        $test = $this->find('first', array(
            'conditions' => array(
                'Testcase.id' => $testcase_id,
                'Suite.site_id' => $site_id,
                'Operatingsystem.id' => $os_id,
                'Browser.id' => $browser_id
            ),
            'order' => 'Test.id DESC',
            'contain'=>array(
        		'Browser',
        		'Operatingsystem',
        		'Testcase',
        		'Suite'
            )
        )); 
        
        if(!empty($test)){
            if($test['Test']['status']==''){ //If no status set for the test, find one by looking at commands
                $status = 'failed'; //Assume test failed and try to prove otherwise
                $opts1 = array(
                    'conditions' => array(
                        'Test.id' => $test['Test']['id']
                    )
                );
                $opts2 = array(
                    'conditions' => array(
                        'Test.id' => $test['Test']['id'],
                        'Command.status' => 'failed'
                    )
                );
                if($this->Command->find('count', $opts1)>0){  
                    if($this->Command->find('count', $opts2)==0){ //If no failed commands, set status to passed
                        $status = 'passed';
                    }
                }else{ //If no commands
                    $status = 'notdone';
                }
                $test['Test']['status'] = $status; //Update status
            }
        }

        return $test;
    }
    
    function getStatus($testcase_id, $os_id, $browser_id, $site_id){
        $status = Cache::read('test_get_status_'.$testcase_id.'_'.$os_id.'_'.$browser_id.'_'.$site_id);
        if ($status !== false) return $status;
        
        $test = $this->getLastInCombination($testcase_id, $os_id, $browser_id, $site_id);
        if(!empty($test)){
            $status = $test['Test']['status'];
        }else{
            $status = 'notdone';    
        } 
        Cache::write('test_get_status_'.$testcase_id.'_'.$os_id.'_'.$browser_id.'_'.$site_id, $status); // 1 hr cache 

        return $status;

    }
    
    function afterSave($created){
        $test = $this->find('first',array(
        	'order' => array('Test.id'),
        	'contain' => array(
                'Testcase' => array('Requirement'),
                'Operatingsystem',
                'Browser',
                'Suite'
            )
        ));

        $testcase_id = $test['Testcase']['id'];
        $operationsystem_id = $test['Operatingsystem']['id'];
        $browser_id = $test['Browser']['id'];
        $site_id = $test['Suite']['site_id'];
        
        foreach ($test['Testcase']['Requirement'] as $requirement){
            $this->Testcase->Requirement->deleteCache($requirement['id'], $site_id);
            Cache::delete('testcase_get_status_'.$testcase_id.'_'.$requirement['id'].'_'.$site_id);
        }
        Cache::delete('test_get_status_'.$testcase_id.'_'.$operationsystem_id.'_'.$browser_id.'_'.$site_id);
            
    }

}
?>
