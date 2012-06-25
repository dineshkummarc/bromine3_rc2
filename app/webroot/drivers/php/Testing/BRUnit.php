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
/*
 * @Author Visti Kløft & Jeppe Poss
 * @Date 9 june 2009.  Updated 26/2-2010 by Visti Kløft
 * @Date 12 july 2010. Updated by Jeppe Poss Pedersen. Old BRUnit has been
 * removed due to obsolescence.
 * @Description replacement for the normal unittest.
*/ 

Class BRUnit {

    function setUp($host, $port, $browser, $sitetotest, $test_id) {
        $this->host = $host;
        $this->port = $port;
        $this->test_id = $test_id;
        $this->sitetotest = $sitetotest;

        $this->selenium = new Testing_Selenium($browser, $sitetotest, $host ,$port);
        $this->selenium->start();
    }

    /*
     * Teardown the selenium object
    */

    function tearDown() {
        $this->selenium->stop();
    }

    function do_post_request($url, $data, $optional_headers = null) {
        $params = array('http' => array(
                        'method' => 'POST',
                        'content' => $data
        ));
        if ($optional_headers !== null) {
            $params['http']['header'] = $optional_headers;
        }
        $ctx = stream_context_create($params);
        $fp = @fopen($url, 'rb', false, $ctx);
        if (!$fp) {
            throw new Exception("Problem with $url, $php_errormsg");
        }
        $response = @stream_get_contents($fp);
        if ($response === false) {
            throw new Exception("Problem reading data from $url, $php_errormsg");
        }
        return $response;
    }

    private function brominecall($function_name, $function_params) {
        $params = Array();
        foreach($function_params as $key=>$value){
            $params[$key] = array('type' => gettype($value) , 'value'=> $value);
        }
        $params['test_id'] =  array('type' => gettype($this->test_id), 'value' =>$this->test_id);
        $postdata = http_build_query(
            $params
        );

        $url = 'http://' . $this->host . ':' . $this->port . "/brunit/$function_name/";

        $result = $this->do_post_request($url, $postdata, 'Content-type: application/x-www-form-urlencoded');
        if (strpos($result, 'OK') !== 0) {
            $this->tearDown();
            throw new Exception('BRUnit controller returned error:' . $result ."<br />in function $function_name");
        }
        return $result;
    }

    /*
    * Assert function. Breaks test if fails
    *
    */

    private function assert($result) {
        $result = split(',', $result);
        if (strpos($result[1], 'failed') === 0) {
            $this->tearDown();
            throw new Exception('Assertion failed. Test stopped!');
        }
    }


    /*
     * Assert the param is TRUE and updates command status and action in the database
     * @param $bool repression to verify 
    */

    function assertTrue($statement1, $comment = '') {
        $result = $this->brominecall(__FUNCTION__,array('statement1' => $statement1, 'comment' => $comment));
        $this->assert($result);

    }

    /*
     * Assert the param is FALSE and updates command status and action in the database
     * @param $bool expression to verify 
    */

    function assertFalse($statement1, $comment = '') {
        $result = $this->brominecall(__FUNCTION__,array('statement1' => $statement1, 'comment' => $comment));
        $this->assert($result);
    }

    /*
     * Assert the params is EQUAL and updates command status and action in the database
     * @param $var1 first param to verify
     * @param $var2 second param to verify
    */

    function assertEquals($statement1, $statement2, $comment = '') {
        $result = $this->brominecall(__FUNCTION__,array('statement1' => $statement1,'statement2' => $statement2, 'comment' => $comment));
        $this->assert($result);
    }

    /*
     * Assert the params is NOT EQUAL and updates command status and action in the database
     * @param $var1 first param to verify
     * @param $var2 second param to verify
    */

    function assertNotEquals($statement1, $statement2, $comment = '') {
        $result = $this->brominecall(__FUNCTION__,array('statement1' => $statement1,'statement2' => $statement2, 'comment' => $comment));
        $this->assert($result);
    }

    /*
     * Assert the param is TRUE and updates command status and action in the database
     * @param $bool repression to verify 
    */

    function verifyTrue($statement1, $comment = '') {
        $result = $this->brominecall(__FUNCTION__,array('statement1' => $statement1, 'comment' => $comment));

    }

    /*
     * Assert the param is FALSE and updates command status and action in the database
     * @param $bool expression to verify 
    */

    function verifyFalse($statement1, $comment = '') {
        $result = $this->brominecall(__FUNCTION__,array('statement1' => $statement1, 'comment' => $comment));
    }

    /*
     * Assert the params is EQUAL and updates command status and action in the database
     * @param $var1 first param to verify
     * @param $var2 second param to verify
    */

    function verifyEquals($statement1, $statement2, $comment = '') {
        $result = $this->brominecall(__FUNCTION__,array('statement1' => $statement1,'statement2' => $statement2, 'comment' => $comment));
    }

    /*
     * Assert the params is NOT EQUAL and updates command status and action in the database
     * @param $var1 first param to verify
     * @param $var2 second param to verify
    */

    function verifyNotEquals($statement1, $statement2, $comment = '') {
        $result = $this->brominecall(__FUNCTION__,array('statement1' => $statement1,'statement2' => $statement2, 'comment' => $comment));
    }

    function customCommand($status, $action, $statement1, $statement2, $comment = '') {
        $result = $this->brominecall(__FUNCTION__,array('action' => $action,'status' => $status,'statement2' => $statement2,'statement1' => $statement1, 'comment' => $comment));
    }

    /*
     * Makes the test fail with a custom text, that will be inserted into the database
     * @param $text the text to be inserted into the database 
    */

    function fail($action) {
        $result = $this->brominecall('customCommand',array('action' => $action,'status' => 'failed', 'statement1' => '.' , 'statement2' => '.', 'comment' => ''));
        $this->assert('OK,failed');
    }

    function waiting() {
        $result = $this->brominecall(__FUNCTION__,array());
    }

}
/**
 * Function used to start the test
 *
 * @param $name name of the test class
 * @param $argv arguments from bromine
 */
function startTest($name, $args='', $function = 'testMyTestCase') {

    $host = $args[1];
    $port = $args[2];
    $browser = $args[3];
    $sitetotest = $args[4];
    $test_id = $args[5];
    $brows2 = urldecode($browser).";".$test_id;

    $t = new $name();
    $t->setUp($host, $port, $brows2, $sitetotest, $test_id);
    $t->$function();
    $t->tearDown();
}
?>