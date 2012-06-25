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
class Node extends AppModel {

    var $validate = array(
        'limit' => array(
            'limitRule-1' => array(
                'rule' => 'numeric',  
                'message' => 'limit must be a positive integer'
             ),
            'limitRule-2' => array(
                'rule' => 'nonnegative',  
                'message' => 'limit must be a positive integer'
             ) 
        ),
        'name' => array(
            'nameRule-1' => array(
                'rule' => 'notempty',  
                'message' => 'Name must be a non-empty string'
             ) 
        ),
        'nodepath' => array(
            'nodepathRule-1' => array(
                'rule' => 'nodepath',
                'message' => 'Nodepath should be on the form hostname:port, not including http://, eg. "127.0.0.1:4445" or "node.somewhere.com:4444"'  
             ),
              
        )
        
    );
    
    function nonnegative($check){
        return ($check['limit'] >= 0);  
    }
    
    function nodepath($check){
        if(strpos($check['nodepath'], 'http://') !== false) return false;
        if(preg_match('/^[a-zA-Z0-9\.]+:[0-9]+$/', $check['nodepath']) === 0) return false;
        return true;  
    }

    //The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
			'Operatingsystem' => array('className' => 'Operatingsystem',
								'foreignKey' => 'operatingsystem_id',
								'conditions' => '',
								'fields' => '',
								'order' => ''
			)
	);
	
	var $hasMany = array(
			'Seleniumserver' => array('className' => 'Seleniumserver',
								'foreignKey' => 'node_id',
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
			'Browser' => array('className' => 'Browser',
						'joinTable' => 'browsers_nodes',
						'foreignKey' => 'node_id',
						'associationForeignKey' => 'browser_id',
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
	
	function beforeSave(){
        if(isset($this->data['Node']['running']) && $this->data['Node']['running'] < 0) $this->data['Node']['running'] = 0;
        return true; 
    }

    function checkJavaServer($nodepath, $timeout = 2){
        $nodepath = explode(':',$nodepath);
        $host = @$nodepath[0];
        $port = @$nodepath[1];
        
        $status = Cache::read('node_check_java_server_'.$host.$port);
        if ($status !== false) return ($status == 'online' ? true: false);
  
        $fp = @fsockopen($host, $port, $errno, $errstr, $timeout);
        
        $status = 'offline';
        if ($fp) {
            fclose($fp);
            $status = 'online';
        }
        flush();
        Cache::write('node_check_java_server_'.$host.$port, $status);
        return ($status == 'online' ? true : false);
    }

}
?>
