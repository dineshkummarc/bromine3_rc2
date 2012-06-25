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
class InstallsController extends AppController {

	public $helpers = array('Html', 'Form');
	public $uses = array();
	public $useTable = false;
	//var $components = array();
	public $layout = 'green_blank';
    
    function beforeFilter() {
        $this->set('enableGA',false);
        $this->Auth->allow('*');
        if (file_exists(TMP.'installed.txt')){
            $this->Session->setFlash('Application already installed. Remove app/tmp/installed.txt to reinstall the application');
            $this->redirect($this->referer());
            exit();
        }
    }
    
    function beforeRender(){}
    
    private function selfcontact($server){
        if(strpos($server, ':') === false){
            $this->Session->setFlash('Server address must include port (ie. ip:port).', true, array('class' => 'error'));
            $this->redirect('index');
            exit;
        }
        list($servername, $port) = explode(':', $server);
        if(!empty($servername) && !empty($port)) {
            App::import('Core', 'HttpSocket');
            $HttpSocket = new HttpSocket(array('timeout' => 10));
            $result = $HttpSocket->get("http://$servername:$port/iamhere.txt");
            if($result == 'success') {
                return true;
            }
        }
        return false;
    } 

    function index() {
        if(!empty($this->data)){
            $host = $this->data['Install']['host'];
            $login = $this->data['Install']['username'];
            $password = $this->data['Install']['password'];
            $database = $this->data['Install']['database'];
            $enableGA = $this->data['Install']['enableGA'];
            $server = $this->data['Install']['server'];
            $userAgreement = $this->data['Install']['userAgreement'];
            $admin_username = $this->data['Install']['admin_username'];
            $admin_password1 = $this->data['Install']['admin_password1'];
            $admin_password2 = $this->data['Install']['admin_password2'];
            $admin_firstname = $this->data['Install']['admin_firstname'];
            $admin_lastname = $this->data['Install']['admin_lastname'];
            $admin_email = $this->data['Install']['admin_email'];
            
            if(!$userAgreement){
                $this->Session->setFlash('You did not accept our end user agreement', true, array('class'=>'error'));    
                $this->redirect('index');
                exit;
            }
            if(!$this->selfcontact($server)){
                $this->Session->setFlash("Server could not contact itself at the specified adress: $server", true, array('class' => 'error'));
                $this->redirect('index');
                exit;
            }
            if ($admin_password1 != $admin_password2){
                $this->Session->setFlash('The admin user\'s passwords must match', true, array('class'=>'error'));
                $this->redirect('index');
                exit;    
            }
            
            if ($admin_firstname == '' || $admin_lastname == '' || $admin_email == '' || $admin_username == '' || $admin_password1 == '' || $admin_password2 == ''){
                $this->Session->setFlash('The following fields can\'t be empty: <br />Admin firstname<br />Admin lastname<br />Admin username<br />Admin email<br />Admin password<br /><br />Please fill out and try again', true, array('class'=>'error'));
                $this->redirect('index');
                exit;    
            }
            //Connect
            //exit;
            $db = @mysql_connect($host, $login, $password);

            if(!$db){
                $this->Session->setFlash('There was an error with the connection: '.mysql_error() , true, array('class'=>'error'));
                $this->redirect('index');
                exit;
            }
            //Drop old db
            @mysql_query("drop database $database",$db);
            
            //Select DB or create if not found
            $status = @mysql_select_db($database, $db);
            if (!$status) {
                $sql = "CREATE DATABASE $database";
                @mysql_query($sql, $db);
                $status = @mysql_select_db($database, $db);
                if (!$status) {
                    $this->Session->setFlash("The database '$database' did not exist and the installer was unable to create it: " . mysql_error());
                    $this->redirect('index');
                    exit;
                }
            }
            
            //Create tables
            $query = trim(file_get_contents(CONFIGS.'sql'.DS.'bromine.sql'));
            $this->multiple_query($query, $db);
            
            //Create admin user
            $userpassword = $this->Auth->password($admin_password1);
                            
            mysql_query("INSERT INTO `users` (
                            `id` ,
                            `firstname` ,
                            `lastname` ,
                            `name` ,
                            `password` ,
                            `group_id` ,
                            `email`
                            )
                            VALUES (
                            50 , '$admin_firstname', '$admin_lastname', '$admin_username', '$userpassword', '1', '$admin_email'
                            ) ",$db) or die("There was an error: " . mysql_error());
                            
            //Insert server details
            list($servername, $port) = explode(':', $server);
            mysql_query("INSERT INTO `configs` (
                            `id` ,
                            `name` ,
                            `value` 
                            )
                            VALUES (
                             null ,
                             'servername',
                             '$servername'
                            ) ",$db) or die("There was an error: " . mysql_error());
            mysql_query("INSERT INTO `configs` (
                            `id` ,
                            `name` ,
                            `value` 
                            )
                            VALUES (
                             null ,
                             'port',
                             '$port'
                            ) ",$db) or die("There was an error: " . mysql_error());                            
                            
            
            //Write database.php file
            $dbFile = CONFIGS.'database.php';
            $fh = fopen($dbFile, 'w') or die("can't open file $dbFile check permissions");
            $stringData = "<?php
    class DATABASE_CONFIG {
    	var \$default = array(
    		'driver' => 'mysql',
    		'persistent' => false,
    		'host' => '$host',
    		'login' => '$login',
    		'password' => '$password',
    		'database' => '$database',
    		'prefix' => '',
    		'encoding' => 'utf8'
    	);
    }
?".">";
            fwrite($fh, $stringData);
            fclose($fh);

            //Write server.properties file for scheduler
            $server_properties_file = APP.'../scheduler/'.'server.properties';
            $fh = fopen($server_properties_file, 'w') or die("can't open file $server_properties_file check permissions");
            $stringData = "
# RMI properties
org.quartz.scheduler.instanceName = Sched1
org.quartz.scheduler.rmi.export = true
org.quartz.scheduler.rmi.registryHost = localhost
org.quartz.scheduler.rmi.registryPort = 1099
org.quartz.scheduler.rmi.createRegistry = true
            
# Job store setup
org.quartz.jobStore.class = org.quartz.impl.jdbcjobstore.JobStoreTX
org.quartz.jobStore.driverDelegateClass = org.quartz.impl.jdbcjobstore.StdJDBCDelegate
org.quartz.jobStore.tablePrefix = QRTZ_
org.quartz.jobStore.dataSource = myDS
org.quartz.jobStore.useProperties = true

# Database setup
org.quartz.dataSource.myDS.driver = com.mysql.jdbc.Driver
org.quartz.dataSource.myDS.URL = jdbc:mysql://localhost:3306/$database
org.quartz.dataSource.myDS.user = $login
org.quartz.dataSource.myDS.password = $password
org.quartz.dataSource.myDS.maxConnections = 30

# Thread properties
org.quartz.threadPool.class = org.quartz.simpl.SimpleThreadPool
org.quartz.threadPool.threadCount = 100
org.quartz.threadPool.threadPriority = 5";
            fwrite($fh, $stringData);
            fclose($fh);
            
            //Write install.txt file, and redirect to stateofthesystem
            file_put_contents(TMP.'installed.txt', date('Y-m-d, H:i:s'));
            $this->redirect(array('controller' => 'configs', 'action' => 'setUS',$enableGA));
        }else{
            $tmp = 'failed';
            $config = 'failed';
            $magic_quotes = 'failed';
            $mod_rewrite = 'failed';
            $failed = true;
        
            if (is_writable(TMP)){
                $tmp = 'passed';
            }
            if (is_writable(CONFIGS.'database.php')){
                $config = 'passed';
            }

            if (get_magic_quotes_gpc() == 0){
                $magic_quotes = 'passed';
            }
            if(@file_get_contents('test.txt') === 'im here' && in_array('mod_rewrite',apache_get_modules())){
                $mod_rewrite = 'passed';
            }else{
                $this->set('nomodrewrite',true);
            }
            
            $server_properties_file = APP.'../scheduler/'.'server.properties';
            if ((file_exists($server_properties_file) && is_writable(APP.'..'.DS.'scheduler'.DS.'server.properties')) || is_writable(APP.'..'.DS.'scheduler'.DS)){
                $server_properties_writeable = 'passed';
            }else{
                $server_properties_writeable = 'failed';
            }
            
            if ($tmp == 'passed' && $config == 'passed' && $magic_quotes == 'passed' && $mod_rewrite == 'passed' && $server_properties_writeable == 'passed'){
                $failed = false;
            }
            
            $this->set('failed',$failed);
            $this->set('tmp',$tmp);
            $this->set('config',$config);
            $this->set('magic_quotes',$magic_quotes);
            $this->set('mod_rewrite',$mod_rewrite);
            $this->set('server_properties_writeable',$server_properties_writeable);
            
            $_SERVER['HTTP_HOST'] == 'localhost' ? $server_name = '127.0.0.1' : $server_name = $_SERVER['HTTP_HOST'];
            $this->set('server_name',$server_name);
            $this->set('server_port',$_SERVER['SERVER_PORT']);
            
        }
        
    }

    private function multiple_query($q, $link) {
        $tok = strtok($q, ";");
        while ($tok) {
            $results = @mysql_query("$tok", $link) or die("There was an error while creating tables and inserting data: " . mysql_error());
            $tok = strtok(";");
        }
        return $results;
    }

}
