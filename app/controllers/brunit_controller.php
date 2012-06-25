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
class BrunitController extends AppController {
    public $uses = array('Tests','Users','Testcasesteps');

    function beforeFilter() {
        Configure::write('debug', 0);
        Configure::write('Cache.disable', true);        
        $this->layout = null;
        $this->loadModel('User');
        $this->loadModel('Testcasesteps');
        $this->Auth->allow('*');
        
        // Decode params
        foreach($this->params['form'] as $key => &$param) {
            settype($param['value'], $param['type']);
            $param = $param['value'];
        }

        //test_id required
        if(!isset($this->params['form']['test_id'])) exit('Failed. test_id not set in ' . $this->params['action']);

        //statement1 required unless action === waiting
        if ($this->params['action'] !== 'waiting') {
            if(!isset($this->params['form']['statement1'])) exit('Failed. statement1 not set in ' . $this->params['action']);
        }
        $this->log($this->params['action'] . " called with: " . print_r($this->params['form'],true), 'brunit');

    }

    /*
    * Function to check user authetication
    * @param user - username to autherize
    * @param password - password to autherize
    */

    function waiting() {
        !empty($this->params['form']['test_id']) ? $test_id = $this->params['form']['test_id'] : exit('Failed. Test_id not set');
        $this->log(__FUNCTION__ . " called with: test_id: $test_id", 'brunit');
        $this->updateCommand($test_id, 'waiting', __FUNCTION__ , null, null,'');
    }

    /*
     * Assert the param is TRUE and updates command status and action in the database
     * @param $bool repression to verify
    */

    function verifyTrue() {
        $this->assertTrue();
    }

    function verifyFalse() {
        $this->assertFalse();
    }
    function verifyEquals() {
        $this->assertEquals();
    }
    function verifyNotEquals() {
        $this->assertNotEquals();
    }


    /*
     * Assert the param is TRUE and updates command status and action in the database
     * @param $bool repression to verify
    */

    function assertTrue() {
        if($this->params['form']['statement1'] === true) {
            $status = "passed";
        }
        else {
            $status = "failed";
        }

        $this->updateCommand($this->params['form']['test_id'], $status, $this->params['action'], $this->params['form']['statement1'], null, $this->params['form']['comment']);
    }

    /*
     * Assert the param is FALSE and updates command status and action in the database
     * @param $bool expression to verify
    */

    function assertFalse() {
        if($this->params['form']['statement1'] === false) {
            $status = "passed";
        }
        else {
            $status = "failed";
        }

        $this->updateCommand($this->params['form']['test_id'], $status, $this->params['action'], $this->params['form']['statement1'], null, $this->params['form']['comment']);
    }

    /*
     * Assert the params is EQUAL and updates command status and action in the database
     * @param $var1 first param to verify
     * @param $var2 second param to verify
    */

    function assertEquals() {
        if(!isset($this->params['form']['statement2'])) exit('Failed. Statement2 not set');

        if($this->params['form']['statement1'] === $this->params['form']['statement2']) {
            $status = "passed";
        }
        else {
            $status = "failed";
        }

        $this->updateCommand($this->params['form']['test_id'], $status, $this->params['action'] ,$this->params['form']['statement1'], $this->params['form']['statement2'], $this->params['form']['comment']);
    }

    /*
     * Assert the params is NOT EQUAL and updates command status and action in the database
     * @param $var1 first param to verify
     * @param $var2 second param to verify
    */

    function assertNotEquals() {
        if(!isset($this->params['form']['statement2'])) exit('Failed. Statement2 not set');
        if($this->params['form']['statement1'] !== $this->params['form']['statement2']) {
            $status = "passed";
        }
        else {
            $status = "failed";
        }
        $this->updateCommand($this->params['form']['test_id'], $status, $this->params['action'], $this->params['form']['statement1'], $this->params['form']['statement2'], $this->params['form']['comment']);
    }

    function customCommand() {

        //action required
        if(!isset($this->params['form']['action'])) exit('Failed. action not set');

        //Status must be set
        if(!isset($this->params['form']['status'])) exit('Failed. status not set');

        //Convert status to lowercase
        $this->params['form']['status'] = strtolower($this->params['form']['status']);

        //Status must be one of the following: "passed", "failed", "done" or "waiting"
        if(in_array($this->params['form']['status'], array('passed', 'failed', 'done', 'waiting'), true) === false) exit('Failed. status must be one of the following: "passed", "failed", "done" or "waiting"');

        //If statement2 not set, set it to empty string
        if(!isset($this->params['form']['statement2'])) $this->params['form']['statement2'] = '';

        $this->loadModel('Command');
        $data['Command']['status'] = $this->params['form']['status'];
        $data['Command']['var1'] = $this->params['form']['statement1'];
        $data['Command']['var2'] = $this->params['form']['statement2'];
        $data['Command']['action'] = $this->params['form']['action'];
        $data['Command']['test_id'] = $this->params['form']['test_id'];
        $data['Command']['comment'] = $this->params['form']['comment'];

        $this->Command->save($data);
        echo "OK,".$this->params['form']['status'];
    }

    /*
     * Updates the status of the last command in the database
     * @param $status the status to change it to
     * @param (Optional) $var2 will be inserted in the database as var2, if given
    */

    private function updateCommand($test_id, $status, $action, $var1, $var2, $comment) {
        $this->log(__FUNCTION__ . " called with: test_id: $test_id, status: $status, action: $action, var1: $var1, var2: $var2, comment: $comment", 'brunit');
        $this->loadModel('Command');
        $this->loadModel('Seleniumserver');
        $this->Command->recursive = -1;
        echo "OK,$status";

        $data = $this->Command->find('first', array(
                'conditions' => array('test_id' => $test_id),
                'order' => 'Command.id DESC'
        ));
        $oldAction = $data['Command']['action'];

        if(strpos($oldAction, 'is') === 0 || $action === 'waiting') {
            !empty($action) ? $data['Command']['action'] = "$action($oldAction)" : '';
        }else {
            unset($data);
            $data['Command']['action'] = $action;
            if(isset($var1)) $var1 = $this->to_string($var1);
            if(isset($var2)) $var2 = $this->to_string($var2);
            $data['Command']['var1'] = $var1;
            $data['Command']['comment'] = $comment;
            $this->Command->create();
        }

        !empty($test_id) ? $data['Command']['test_id'] = $test_id : '';
        !empty($status) ? $data['Command']['status'] = $status : '';
        !empty($var2) ? $data['Command']['var2'] = $var2 : '';
        $data['Command']['comment'] = $comment;

        // Updates the lastCommand in selenium server table
        $seleniumserver = $this->Seleniumserver->findByTestId($test_id);
        $seleniumserver['Seleniumserver']['lastCommand'] = time();
        $this->Seleniumserver->save($seleniumserver);

        $this->Command->save($data);

    }

    private function to_string($data) {
        switch (gettype($data)) {
            case 'string':
                return '"'.$data.'"';
                break;
            case 'array':
                return print_r($data, true);
                break;
            case 'boolean':
                if($data === true) return 'true';
                if($data === false) return 'false';
                break;
            default:
                return $data;
                break;
        }
    }

}