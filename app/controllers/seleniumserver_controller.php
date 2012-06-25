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
class SeleniumserverController extends AppController {
	public $layout = null;
	public $uses = array('Seleniumserver', 'Test', 'Command');
	
	function beforeFilter(){
        Configure::write('debug', 0);
        $this->Auth->allow('*');
    }
    

    private function get_seleniumserver($test_id, $session_id){
        if(empty($test_id) && empty($session_id)) $this->halt('No test_id or session_id set. Exiting');
        if(!empty($test_id)){
            $seleniumserver = $this->Seleniumserver->findByTestId($test_id);
            if($seleniumserver !== false) return $seleniumserver;
        }
        if(!empty($session_id)){
            $seleniumserver = $this->Seleniumserver->findBySessionId($session_id);
            if($seleniumserver !== false) return $seleniumserver;
                    
        } 
        $this->halt("Could not find any seleniumserver with test_id = '$test_id' or session_id = '$session_id'"); //Passes along empty test_id to avoid infinite loops of: no seleniumserver found -> testComplete -> no seleniumserver_found     
    }
    
    private function halt($error){
        $this->log($error, 'seleniumserver_controller');
        echo "ERROR: seleniumserver_controller.php reports: $error";
        exit;
    }
    
    
    function driver(){
        $this->log('Called with: '.print_r($_REQUEST, true), 'seleniumserver_controller'); 
        $cmd = ''; $var1 = ''; $var2 = ''; $test_id = ''; $sessionId = ''; $cmdName = ''; $status = ''; 
        if(isset($_REQUEST['cmd'])) $cmd = $_REQUEST['cmd'];
        if(isset($_REQUEST['1'])) $var1 = $_REQUEST['1'];
        if(isset($_REQUEST['2'])) $var2 = $_REQUEST['2'];
        if(isset($_REQUEST['test_id'])) $test_id = $_REQUEST['test_id'];
        if(isset($_REQUEST['sessionId'])) $sessionId = $_REQUEST['sessionId'];
        
        if(empty($cmd)) $this->halt('No cmd defined. Exiting.');

        if ($cmd == 'getNewBrowserSession'){
            list($var1, $test_id) = split(';', $var1);  
        }

        $seleniumserver = $this->get_seleniumserver($test_id, $sessionId);
        $test_id = $seleniumserver['Seleniumserver']['test_id'];
        $sessionId = $seleniumserver['Seleniumserver']['session_id'];
        $nodepath = $seleniumserver['Seleniumserver']['nodepath'];
        
        $session = !empty($sessionId) ? "&sessionId=$sessionId" : "";
        $url = "http://$nodepath/selenium-server/driver/?cmd=".urlencode($cmd)."&1=".urlencode($var1)."&2=".urlencode($var2).$session;
        
        //EXECUTE COMMMAND
        $this->log("executing: $url", 'seleniumserver_controller');                
        $response = $this->executeCommand($url);
        $this->log("got response: $response", 'seleniumserver_controller');
    
        $error = false;        
        if(strpos($response, 'OK') === 0){
            $status = $this->getStatus($response);    
        }else{
            $error = true;
            $status = 'failed';
            $var2 .= " | $response";
        }
        
        $this->insertCommand($status, $cmd, $var1, $var2, $test_id);
        
        if ($cmd == 'getNewBrowserSession'){
            $sessionId = end(split(',',$response));
            $seleniumserver['Seleniumserver']['session_id'] = $sessionId;
            
            $test = $this->Test->findById($test_id);
            $test['Test']['session_id'] = $sessionId;
            $this->Test->save($test);
        }
        
        $seleniumserver['Seleniumserver']['lastCommand'] = time();
        
        $this->Seleniumserver->save($seleniumserver);
        
        if($cmd == 'testComplete'){
            $cmds = $this->Command->find(array(
                'Command.test_id' => $test_id,
                'Command.status' => 'failed')
            );
            $status = empty($cmds) ? 'passed' : 'failed';
            $test = array(
                'Test' => array(
                    'status' => $status,
                    'id' => $test_id
                )
            );
            $this->Test->save($test);
                        
            $this->Seleniumserver->delete($seleniumserver['Seleniumserver']['id']);             
        }
        
        echo $response;
        
        if($error === true) $this->Test->kill($test_id, "The selenium server did not return OK");        

    }
    
    private function insertCommand($status, $cmd, $var1, $var2 = '', $test_id){
        $command = array(
            'Command' => array(
                'status' => $status,
                'action' => $cmd,
                'var1' => $var1,
                'var2' => $var2,
                'test_id' => $test_id
            )
        );
        $this->Command->create();
        $this->Command->save($command);
    }
    
    private function getStatus($response){ //Figures out the status of the command
        $status = "done";
        if(preg_match('/true/', $response) ){
            $status = "passed";
        }
        if(preg_match('/false/', $response) ){
            $status = "failed";
        }
        return $status;
    }
 
    
    private function executeCommand($url){
        $handle = fopen($url, 'r');
        stream_set_blocking($handle, false);
        $response = stream_get_contents($handle);
        fclose($handle);
        
        
        return $response;
    }
    
}