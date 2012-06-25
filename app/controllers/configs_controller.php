<?php
/*
Copyright 2007, 2008, 2009 Rasmus Berg Palm, Visti Klï¿½ft and Jeppe Poss Pedersen 

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
class ConfigsController extends AppController {

    public $helpers = array('Html', 'Form','Cache');
    public $main_menu_id = -2;
    public $components = array('Email');

    function beforeFilter() {
        $this->Auth->allow('iamhere');
        $this->Auth->allow('setUS');
        parent::beforeFilter();
    }

    function setEchelon($bool = null) {
        if (isset($bool)) {
            $this->Config->updateAll(array('Config.value'=>$bool), array('Config.name'=>'echelon'));
        }
        $this->redirect(array('controller' => 'echelons', 'action' => 'index'));

    }

    function userStatistics() {
        if(!empty($this->data)) {
            foreach($this->data['Config'] as $name => $value) {
                $data = $this->Config->findByName($name);
                $data['Config']['name'] = $name;
                $data['Config']['value'] = $value;
                $this->Config->create();
                $this->Config->save($data);
            }
        }
        $enableGA = $this->Config->field('value', array('name'=> 'enableGA'));
        $this->set('enableGA', $enableGA);
    }

    function setUS($enable) {
        $data['Config']['name'] = 'enableGA';
        $data['Config']['value'] = $enable;
        $this->Config->create();
        $this->Config->save($data);
        $this->redirect(array('controller' => 'configs', 'action' => 'stateOfTheSystem'));
    }

    function email($do = null) {

        if(!empty($this->data)) {
            foreach($this->data['Config'] as $name => $value) {
                $data = $this->Config->findByName($name);
                $data['Config']['name'] = $name;
                $data['Config']['value'] = $value;
                $this->Config->create();
                $this->Config->save($data);
                $this->Session->setFlash('Email settings saved');
            }
        }
        $email_host = $this->Config->field('value', array('name' => 'email_host'));
        $email_port = $this->Config->field('value', array('name'=> 'email_port'));
        $email_username = $this->Config->field('value', array('name'=> 'email_username'));
        $email_password = $this->Config->field('value', array('name'=> 'email_password'));
        $this->set('email_host', $email_host);
        $this->set('email_port', $email_port);
        $this->set('email_username', $email_username);
        $this->set('email_password', $email_password);

        if ($do == 'sendemail') {
            $this->log('Config');
            $email_host = $this->Config->field('value', array('name' => 'email_host'));
            $email_port = $this->Config->field('value', array('name'=> 'email_port'));
            $email_username = $this->Config->field('value', array('name'=> 'email_username'));
            $email_password = $this->Config->field('value', array('name'=> 'email_password'));
            $this->Email->smtpOptions = array(
                    'port'=>$email_port,
                    'timeout'=>'30',
                    'host' =>$email_host,
                    'username'=>$email_username,
                    'password'=>$email_password
            );
            $this->Email->sendAs = 'html';
            //$this->Email->template = 'report';
            /* Set delivery method */
            $this->Email->delivery = 'smtp';

            $this->Email->to = $this->realname . " <Some name>";
            $this->Email->subject = "Bromine test report " . date("H:i:s d. F Y");
            $this->Email->from = 'Bromine <bromine.eniro@gmail.com>';

            if ( $this->Email->send("This is a test this is only a test") ) {
                $this->set('status','Email send');
            } else {
                $this->set('status',$this->Email->smtpError);
                //$this->log('Email error: '. );
            }
            $this->Email->reset();
        }

    }

    function server() {
        if(!empty($this->data)) {
            foreach($this->data['Config'] as $name => $value) {
                $data = $this->Config->findByName($name);
                $data['Config']['name'] = $name;
                $data['Config']['value'] = $value;
                $this->Config->create();
                $this->Config->save($data);
            }
            Cache::delete('selfcontact');
        }
        $servername = $this->Config->field('value', array('name' => 'servername'));
        $port = $this->Config->field('value', array('name'=> 'port'));
        $this->set('servername', $servername);
        $this->set('port', $port);
        $this->set('status', $this->selfcontact());
    }

    function iamhere() {
        $this->RequestHandler->setContent('text');
        Configure::write('debug', 0);
        $this->layout = 'none';
    }

    public function sauce() {
        $sauce_combinations = array(
                'Windows 2003' => array(
                        'firefox-2.0.' => 'NA',
                        'firefox-3.0.' => 'NA',
                        'firefox-3.5.' => 'NA',
                        'firefox-3.6.' => 'NA',
                        'googlechrome' => 'NA',
                        'iexplore-6.' => 'NA',
                        'iexplore-7.' => 'NA',
                        'iexplore-8.' => 'NA',
                        'opera-10' => 'NA',
                        'opera-9.' => 'NA',
                        'safari-3.' => 'NA',
                        'safari-4.' => 'NA'
                ),
                'Linux' => array(
                        'firefox-3.0.' => 'NA'
                )
        );

        if(!empty($this->data)) {
            foreach($this->data['Config'] as $name => $value) {
                $data = $this->Config->findByName($name);
                if($name == 'sauce_enabled') {
                    $this->loadModel('Operatingsystem');
                    $this->loadModel('Browser');
                    $this->loadModel('Node');
                    $this->Node->bindModel(array('hasMany' => array('BrowsersNode')));

                    if($value == 1 && $data['Config']['value'] == 0) { //enable sauce mode
                        foreach($sauce_combinations as $OS => $browsers) {
                            $this->Operatingsystem->create();
                            $this->Operatingsystem->save(array('Operatingsystem' => array('name' => "SauceLabs $OS")));

                            $this->Node->create();
                            $OS_id = $this->Operatingsystem->getLastInsertId();
                            $nodepath = $this->Config->field('value', array('name'=> 'sauce_nodepath'));
                            $this->Node->save(array('Node' => array('nodepath' => $nodepath, 'operatingsystem_id' => $OS_id, 'limit' => 5, 'description' => 'Sauce Labs node')));
                            $node_id = $this->Node->getLastInsertId();

                            foreach($browsers as $browser => $path) {
                                $current = $this->Browser->findByName("SauceLabs $browser");
                                if(empty($current)) {
                                    $this->Browser->create();
                                    $this->Browser->save(array('Browser' => array('name' => "SauceLabs $browser", 'path' => $path)));
                                    $browser_id = $this->Browser->getLastInsertId();
                                }else {
                                    $browser_id = $current['Browser']['id'];
                                }
                                $this->Node->BrowsersNode->create();
                                $this->Node->BrowsersNode->save(array('BrowsersNode' => array('browser_id' => $browser_id, 'node_id' => $node_id)));
                            }

                        }
                    }elseif($value == 0 && $data['Config']['value'] == 1) { //Disable sauce mode
                        foreach($sauce_combinations as $OS => $browsers) {
                            $OS_id = $this->Operatingsystem->field('id', array('name' => "SauceLabs $OS"));
                            $this->Node->deleteAll(array('Node.operatingsystem_id' => $OS_id));
                            $this->Operatingsystem->deleteAll(array('Operatingsystem.name' => "SauceLabs $OS"));

                            foreach($browsers as $browser => $path) {
                                $browser_id = $this->Browser->field('id', array('name' => "SauceLabs $browser"));
                                $this->Browser->deleteAll(array('Browser.name' => "SauceLabs $browser"));
                            }
                        }
                    }
                }

                $data['Config']['name'] = $name;
                $data['Config']['value'] = $value;
                $this->Config->create();
                $this->Config->save($data);
            }
        }
        $this->redirect($this->referer());
        exit;
    }

    function register() {
        $areas = array('private' => 'Will not disclose',
                'IT development' => 'IT development',
                'Telecommunication' => 'Telecommunication',
                'Software QA' => 'Software QA',
                'other' => 'other');

        $users = array('private' => 'Will not disclose',
                '1' => 'Just me',
                '< 5' => '< 5 Bromine users',
                '< 10' => '< 10 Bromine users',
                '< 25' => '< 25 Bromine users',
                '< 50' => '< 50 Bromine users',
                '< 100' => '< 100 Bromine users',
                '< 500' => '< 500 Bromine users',
                '> 500' => '> 500 Bromine users');

        $founds = array('private' => 'Will not disclose',
                'search engine' => 'from a search engine (Google, Yahoo, eg.)',
                'openqa.org' => 'http://openqa.org',
                'recommendations' => 'recommendations from a friend/colleague',
                'other' => 'other');

        $employees = array('private' => 'Will not disclose',
                'very small(1)' => 'Only me',
                'small (+10)' => '10+ Employees',
                'medium (+50)' => '50+ Employees',
                'large (+100)' => '100+ Employees',
                'very large (+500)' => '500+ Employees',
                'global (+1000)' => '1000+ Employees');


        $this->set('areas',$areas);
        $this->set('users',$users);
        $this->set('founds',$founds);
        $this->set('employees',$employees);

        $reg = $this->Config->findByName('registered');
        if ($reg['Config']['value'] == 1) {
            $this->set('registered','You have already registered this version of Bromine. Thanks');
        }

        if (!empty($this->data)) {
            $error = false;
            if ($this->data['Config']['name'] == "") {
                $this->set('name_error' , 'You can\'t leave this field blank, please write you name');
                $error = true;
            }

            if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,6})$", $this->data['Config']['email'])) {
                $this->set('email_error' , 'You email is not valid.');
                $error = true;
            }

            if ($error == false){
                $respons = $this->do_post_request('http://regs.brominefoundation.org/register.php', http_build_query($this->data['Config'], '', '&amp;'));
            }
            if(!empty($respons)) {
                $error = true;
                $this->set('regError', array('respons' => $respons, 'content' => 'There was a problem with the registration, please try again later. Please report the above error on ', 'url' => 'http://clearspace.openqa.org/community/selenium/bromine'));
            }
            if (!$error) {
                // Save this to db
                $this->Config->updateAll(
                        array('Config.value' => '1'),
                        array('Config.name' => 'registered')
                );

                $this->set('registered','Thank you for your registration.');
            }

        }
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

    function stateOfTheSystem() {

        //if (!empty(Cache::read('stateOfTheSystem'))){
        $stateOfTheSystem = Cache::read('stateOfTheSystem');
        //pr ($stateOfTheSystem);
        if ($stateOfTheSystem !== false) {
            $this->set('states',$stateOfTheSystem);
            return $stateOfTheSystem;
        }
        //}
        //Java
        exec('java -version', $java_output, $java_return);
        $states['Java']['expected'] = 'Java enabled system';
        $ignored = $this->Config->findByName('Java');
        $ignored = $ignored['Config']['value'];
        if ($ignored == 1) {
            $states['Java']['status'] = 'ignored';
            $states['Java']['result'] = ' (IGNORED!)';
        }elseif($java_return == 0) {
            $states['Java']['status'] = true;
            $states['Java']['result'] = implode(',', $java_output);
        }else {
            $states['Java']['status'] = false;
            $states['Java']['result'] = 'Could not run java';
        }

        //PHP
        exec('php --version', $php_output, $php_return);
        $states['PHP']['expected'] = 'PHP enabled system';
        $ignored = $this->Config->findByName('PHP');
        $ignored = $ignored['Config']['value'];
        if ($ignored == 1) {
            $states['PHP']['status'] = 'ignored';
            $states['PHP']['result'] = ' (IGNORED!)';
        }elseif($php_return == 0) {
            $states['PHP']['status'] = true;
            $states['PHP']['result'] = implode(',', $php_output);
        }else {
            $states['PHP']['status'] = false;
            $states['PHP']['result'] = 'Could not run php';
        }

        //Magic Quotes
        $mq_return = get_magic_quotes_gpc();
        $states['Magic Quotes']['expected'] = 'Magic qoutes turned off';
        $ignored = $this->Config->findByName('Magic Quotes');
        $ignored = $ignored['Config']['value'];
        if ($ignored == 1) {
            $states['Magic Quotes']['status'] = 'ignored';
            $states['Magic Quotes']['result'] = ' (IGNORED!)';
        }elseif($mq_return == 0) {
            $states['Magic Quotes']['status'] = true;
            $states['Magic Quotes']['result'] = 'Yours is turned off';
        }else {
            $states['Magic Quotes']['status'] = false;
            $states['Magic Quotes']['result'] = 'Yours is turned on';
        }

        //Max execution time
        $max_exec_time = ini_get('max_execution_time');
        $states['Max execution time']['expected'] = 'Max execution time greater than 60000 seconds';
        $states['Max execution time']['result'] = "Yours is set to $max_exec_time";
        $ignored = $this->Config->findByName('Max execution time');
        $ignored = $ignored['Config']['value'];
        if ($ignored == 1) {
            $states['Max execution time']['status'] = 'ignored';
            $states['Max execution time']['result'] = ' (IGNORED!)';
        }elseif($max_exec_time >= 60000 || $max_exec_time == -1) {
            $states['Max execution time']['status'] = true;
        }else {
            $states['Max execution time']['status'] = false;
        }

        //Max input time
        $max_input_time = ini_get('max_input_time');
        $states['Max input time']['expected'] = 'Max input time greater than 60000 seconds';
        $states['Max input time']['result'] = "Yours is set to $max_input_time";
        $ignored = $this->Config->findByName('Max input time');
        $ignored = $ignored['Config']['value'];
        if ($ignored == 1) {
            $states['Max input time']['status'] = 'ignored';
            $states['Max input time']['result'] = ' (IGNORED!)';
        }elseif($max_input_time >= 60000 || $max_input_time == -1) {
            $states['Max input time']['status'] = true;
        }else {
            $states['Max input time']['status'] = false;
        }

        //Default socket timeout
        $errors = null;
        $socket_timeout = ini_get('default_socket_timeout');
        if ($socket_timeout < 60) {
            $errors = 'Timeout is '. $socket_timeout;
        }
        $states['Socket timeout']['expected'] = 'Default socket timeout is >= 60 sec.';
        $states['Socket timeout']['result'] = "Yours is set to ". (empty($errors) ? $socket_timeout .' secs.' : '<b>< 60 sec.</b>. Please change this in your php.ini file');
        $ignored = $this->Config->findByName('Socket timeout');
        $ignored = $ignored['Config']['value'];
        if ($ignored == 1) {
            $states['Socket timeout']['status'] = 'ignored';
            $states['Socket timeout']['result'] = ' (IGNORED!)';
        }elseif(empty($errors)) {
            $states['Socket timeout']['status'] = true;
        }else {
            $states['Socket timeout']['status'] = false;
        }

        //Argument seperator
        $errors = null;
        $arg_separator = ini_get('arg_separator.output');
        if ($arg_separator != '&') {
            $errors = 'Argument seperator is '. $arg_separator;
        }
        $states['Argument separator']['expected'] = 'Argument seperator equals \'&\'.';
        $states['Argument separator']['result'] = "Argument seperator is ". (empty($errors) ? " $arg_separator." : '<b>'.htmlspecialchars($arg_separator).'</b>. Please change this in your php.ini file. This is normally done by outcommenting the line >>arg_separator.output = "'.htmlspecialchars("&amp;").'<<');
        $ignored = $this->Config->findByName('Argument separator');
        $ignored = $ignored['Config']['value'];
        if ($ignored == 1) {
            $states['Argument separator']['status'] = 'ignored';
            $states['Argument separator']['result'] = ' (IGNORED!)';
        }elseif(empty($errors)) {
            $states['Argument separator']['status'] = true;
        }else {
            $states['Argument separator']['status'] = false;
        }

        //File permissions
        $dirs = array_merge($this->directoryToArray(substr_replace(TMP ,"", -1), true), $this->directoryToArray(WWW_ROOT."testscripts", true));
        $states['Permissions']['expected'] = 'All directories writeable';
        $states['Permissions']['status'] = true;
        $states['Permissions']['result'] = '';
        $ignored = $this->Config->findByName('Permissions');
        $ignored = $ignored['Config']['value'];
        foreach ($dirs as $dir) {
            if(!is_writeable($dir)) {
                $states['Permissions']['result'] .= "<b>$dir is NOT writeable</b><br />";
                $states['Permissions']['status'] = false;

            }else {
                $states['Permissions']['result'] .= "$dir is writeable<br />";
            }
        }
        if ($ignored == true) {
            $states['Permissions']['status'] = 'ignored';
            $states['Permissions']['result'] = ' (IGNORED!)';
        }

        //Server can find itself
        $success = $this->selfcontact();
        $servername = $this->Config->field('value', array('name' => 'servername'));
        $port = $this->Config->field('value', array('name'=> 'port'));
        $states['Selfcontact']['expected'] = 'The server could contact itself at the servername and port specified';
        App::import('Helper', 'Html');
        $this->html = new HtmlHelper();
        $states['Selfcontact']['result'] = "Bromine tried to contact http://$servername:$port/configs/iamhere and ".($success ? '<b>succeded.</b>' : '<b>failed. Please '.$this->html->link('correct it', array('controller'=>'configs', 'action'=>'server')).'</b>');
        $ignored = $this->Config->findByName('Selfcontact');
        $ignored = $ignored['Config']['value'];
        if ($ignored == 1) {
            $states['Selfcontact']['status'] = 'ignored';
            $states['Selfcontact']['result'] = ' (IGNORED!)';
        }elseif($success) {
            $states['Selfcontact']['status'] = true;
        }else {
            $states['Selfcontact']['status'] = false;
        }

        //Scheduler online
        $states['Scheduler']['errors'] = null;
        $states['Scheduler']['expected'] = 'Scheduler is online';
        $states['Scheduler']['status'] = false;
        $states['Scheduler']['result'] = "Java is required to run the scheduler.";

        if($states['Java']['status'] === true) {
            $states['Scheduler']['errors'] = $this->requestAction('/qrtz_job_details/getStatus');
            $states['Scheduler']['expected'] = 'Scheduler is online';
            App::import('Helper', 'Html');
            $this->html = new HtmlHelper();
            $states['Scheduler']['result'] = "Scheduler is ". (empty($states['Scheduler']['errors']) ? 'online' : '<b>OFFLINE</b>. Please '. $this->html->link('correct it', array('controller'=>'qrtz_job_details', 'action'=>'index')));
            $ignored = $this->Config->findByName('Scheduler');
            $ignored = $ignored['Config']['value'];
            if ($ignored == 1) {
                $states['Scheduler']['status'] = 'ignored';
                $states['Scheduler']['result'] = ' (IGNORED!)';
            }elseif(empty($states['Scheduler']['errors'])) {
                $states['Scheduler']['status'] = true;
            }else {
                $states['Scheduler']['status'] = false;
            }
        } elseif($states['Java']['status'] === false) {
            $states['Scheduler']['status'] = false;
            $states['Scheduler']['result'] = "Java is required to run the scheduler.";
        } else {
            $states['Scheduler']['status'] = 'ignored';
            $states['Scheduler']['result'] = "Could not determine if the scheduler is running.";
        }

        //JobChecker job present
        $this->loadModel('QrtzJobDetail');
        $job_name = $this->QrtzJobDetail->field('JOB_NAME', array('JOB_NAME' => 'JobsChecker'));
        $success = empty($job_name) ? false : true;
        $states['JobChecker']['expected'] = 'The scheduler has a JobsChecker job';
        App::import('Helper', 'Html');
        $this->html = new HtmlHelper();
        $states['JobChecker']['result'] = "JobsChecker job is ".($success ? '<b>present.</b>' : '<b>NOT present. Please '.$this->html->link('correct it', array('controller'=>'qrtz_job_details', 'action'=>'index')).'</b>');
        $ignored = $this->Config->findByName('JobChecker');
        $ignored = $ignored['Config']['value'];
        if ($ignored == 1) {
            $states['JobChecker']['status'] = 'ignored';
            $states['JobChecker']['result'] = ' (IGNORED!)';
        }elseif($success) {
            $states['JobChecker']['status'] = true;
        }else {
            $states['JobChecker']['status'] = false;
        }

        $dontCache = false;
        foreach ($states as $state) {
            if ($state['status'] == 0) {
                $dontCache = true;
                break;
            }
        }
        $this->set('states',$states);
        Cache::set(array('duration' => '1 hour'));
        if (!$dontCache) {
            Cache::write('stateOfTheSystem', $states);
        }
        return $states;
    }


    function selfcontact() {
        $status = Cache::read('selfcontact');
        if ($status !== false) return $status;

        $servername = $this->Config->field('value', array('name' => 'servername'));
        $port = $this->Config->field('value', array('name'=> 'port'));
        if(!empty($servername) && !empty($port)) {
            App::import('Core', 'HttpSocket');
            $HttpSocket = new HttpSocket(array('timeout' => 10));
            $result = $HttpSocket->get("http://$servername:$port/configs/iamhere");
            if($result == 'success') {
                Cache::write('selfcontact', true);
                return true;
            }
        }
        Cache::write('selfcontact', false);
        return false;
    }

    function ignore() {
        
        $key = $this->data['Config']['key'];
        $state = $this->data['Config']['state'];

        $id = $this->Config->field('id',array('name' => $key));

        $data['Config']['id'] = $id;
        $data['Config']['name'] = $key;
        $data['Config']['value'] = $state;


        $this->Config->save($data);
        $this->clearCache();
        $this->redirect($this->referer());
    }

    private function directoryToArray($directory, $recursive) {
        $array_items = array();
        if ($handle = opendir($directory)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && $file != '.svn') {
                    if (is_dir($directory. DS . $file)) {
                        if($recursive) {
                            $array_items = array_merge($array_items, $this->directoryToArray($directory. DS . $file, $recursive));
                        }
                        $file = $directory . DS . $file;
                        $array_items[] = preg_replace("/\/\//si", DS, $file);
                    } else {
                        //$file = $directory . "/" . $file;
                        //$array_items[] = preg_replace("/\/\//si", "/", $file);
                    }
                }
            }
            closedir($handle);
        }
        return $array_items;
    }

    function clearCache() {
        Cache::delete('state_of_the_system');
        $this->Session->setFlash('State of the system check cache has been cleared');
        $this->redirect($this->referer());
    }

    function cache() {
        if ($this->data['Config']['clearCache']) {
            Cache::clear();
            clearCache();
            $this->Session->setFlash('All cache was cleared');
        }
    }


}
