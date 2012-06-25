<?php
class JobsController extends AppController {

	public $helpers = array('Html', 'Form', 'Time');
    public $timeout = 120; //Seconds 
    public $name = 'Jobs';        
    
    function beforeFilter(){
        Configure::write('Cache.disable', true);
        $this->loadModel('Config');
        $this->loadModel('Node');
        $this->loadModel('Seleniumserver');
        $this->servername = $this->Config->field('value', array('name'=>'servername'));
        $this->port = $this->Config->field('value', array('name'=>'port'));
        $this->Auth->allow('check');
        parent::beforeFilter();
    }
    
	function index() {
		$this->Job->recursive = 0;
		$this->paginate = array(
            'Job' => array(
                'contain' => array('Browser', 'Operatingsystem','Suite' => array('User'),'Testcase'),
                'limit' => 20, 
                'order' => array('suite_id' => 'asc')
            )
        );
        $this->set('jobs', $this->paginate());
		
	}
	
    public function check(){
        //session_write_close(); //Needed for avoiding race conditions even when using ajax
        $this->layout = 'none';
        $this->updateNodes();
        $nextPossible = $this->findNextPossible();
        //pr($nextPossible);
        $count = 0;
        $output = '';
        $this->set('nojobs', false);
        $old_suite_id = 0;
        while ($nextPossible != false && $count<100) {
            $this->updateNodes();
            $this->run($nextPossible);
            //$output .= date("m.d.y @ H:i:s") . ': Job ID:'.$nextPossible['jobs']['id'] . ' started test: \'' . $nextPossible['testcases']['name'] . '('.$nextPossible['testcases']['id'].')\' in ' . $nextPossible['browsers']['name'] . ' on ' . $nextPossible['operatingsystems']['name'] . "<br />";	
            if ($nextPossible['suites']['id'] != $old_suite_id){
                $old_suite_id = $nextPossible['suites']['id'];
                $output = "
                    <tr>
                        <th colspan='4'>Suite ID: $old_suite_id</th>
                        <th><a href='suites/view/".$nextPossible['suites']['id']."'>View</a></th>
                    </tr>";
            }
            
            $output .= "
            <tr>
                <td>".$nextPossible['testcases']['name']."</td>
                <td>".$nextPossible['operatingsystems']['name']."</td>
                <td>".$nextPossible['browsers']['name']."</td>
                <td>".date("d.m.y @ H:i:s")."</td>
            </tr>"; 
            $nextPossible = $this->findNextPossible();
            $count++;
        }
        if ($output == ''){
            $output = 'No tests started. Check if there are any jobs and if there are any nodes online';
            $this->set('nojobs',true);
        }
        $this->set('output',$output);
        
    }
	
    private function updateNodes(){
        //session_write_close(); //Needed for avoiding race conditions even when using ajax
        $this->Seleniumserver->cacheQueries = false;        
        $this->loadModel('Test');
        $seleniumservers = $this->Seleniumserver->find('all');
        foreach($seleniumservers as $seleniumserver){
            if((time() - $seleniumserver['Seleniumserver']['lastCommand']) > $this->timeout && $seleniumserver['Seleniumserver']['lastCommand'] != null){ //The test has not run commands for timeout seconds
                $test_id = $seleniumserver['Seleniumserver']['test_id'];
                $this->log("Test $test_id killed due to timeout of ".$this->timeout." seconds", 'jobs');
                $this->Test->kill($seleniumserver['Seleniumserver']['test_id'], "Bromine judged the test unresponsive because no commands had been run for $this->timeout seconds. The test was terminated to free up node resources.");
              
            }
        }
        //Quick 'n dirty because we can't figure out why the nodes.running aint set to zero sometimes. TODO: FIX the problem, delete this.
        $nodes = $this->Node->find('all', array(
            'contain' => array(
                'Seleniumserver'
            )
        ));
        foreach($nodes as $node){
            if(empty($node['Seleniumserver'])){
                $node['Node']['running'] = 0;
                $this->Node->save($node);
            }
        }
    }
	
	private function findNextPossible(){
        $this->loadModel('Node');
        $nodes = $this->Node->find('all');
        $online = array();
	    foreach ($nodes as $node){
            if ($this->Node->checkJavaServer($node['Node']['nodepath'])){
                $online[] = $node['Node']['id'];
            }
        }
	    $online = implode(',', $online);
        $nextPossible = $this->Job->query("
        SELECT * FROM jobs, browsers, operatingsystems, testcases, nodes, browsers_nodes, sites, suites, projects  WHERE
        testcases.project_id = projects.id AND
        jobs.browser_id = browsers.id AND
        jobs.operatingsystem_id = operatingsystems.id AND
        jobs.testcase_id = testcases.id AND
        operatingsystems.id = nodes.operatingsystem_id AND
        (nodes.running < nodes.limit OR nodes.limit = -1) AND
        browsers_nodes.browser_id = browsers.id AND
        browsers_nodes.node_id = nodes.id AND
        jobs.suite_id = suites.id AND
        suites.site_id = sites.id AND
        nodes.id IN ($online)
        ORDER BY jobs.added ASC, nodes.running ASC
        LIMIT 1;", false
        );
        //echo $nextPossible[0]['jobs']['id']."<br />";
        if(!empty($nextPossible)){
            return $nextPossible[0];
        }else{
            return false;
        }                         
    }
    
    public function deleteSuite($suite_id){
        $this->Job->deleteAll(array('suite_id'=>$suite_id));
        exit;    
    }
    
    private function run($nextPossible){
        //$this->log("function run called with nextPossible: ".print_r($nextPossible, true), 'jobs');
        //Sets up the selenium-server in the DB, puts together the command line string 
        //Setup the test in the DB
        $this->loadModel('Test');
        
        $this->data['Test'] = array(
            'name' => $nextPossible['testcases']['name'],
            'suite_id' => $nextPossible['suites']['id'],
            'status' => 'running',
            'timestamp' => null,
            'browser_id' => $nextPossible['browsers']['id'],
            'operatingsystem_id' => $nextPossible['operatingsystems']['id'],
            'testcase_id' => $nextPossible['testcases']['id'],      
        );
        $this->Test->create();
        $this->Test->save($this->data);
        $test_id = $this->Test->id;
        
        //Create the seleniumserver
        $this->Seleniumserver->create();
        $this->Seleniumserver->save(array(
            'test_id' => $test_id,
            'nodepath' => $nextPossible['nodes']['nodepath'],
            'node_id' => $nextPossible['nodes']['id'],
            'lastCommand' => time()
        ));
        
        //delete the job
        $this->Job->delete($nextPossible['jobs']['id']);
        
        $project_name = $nextPossible['projects']['name'];
                
        //Setup type variables (extension, spacer and so on)
        $extension = $this->getFileExt($nextPossible['testcases']['id'], $project_name);        
        $this->loadModel('Type');
        $this->types = $this->Type->find("extension = '$extension'");
        $name = $this->types['Type']['name'];
        $spacer = $this->types['Type']['spacer'];
        $command = $this->types['Type']['command'];
        
        //Put together browser, sauce RC integration:
        $sauce_enabled = $this->Config->field('value', array('name'=> 'sauce_enabled'));
        $sauce_nodepath = $this->Config->field('value', array('name'=> 'sauce_nodepath'));
        if($sauce_enabled == 1 && $nextPossible['nodes']['nodepath'] == $sauce_nodepath){
            $sauce_username = $this->Config->field('value', array('name' => 'sauce_username'));
            $sauce_apikey = $this->Config->field('value', array('name'=> 'sauce_apikey'));
            
            $browser = str_replace('SauceLabs ', '', $nextPossible['browsers']['name']);
            $browser = explode('-', $browser);
            
            $OS = str_replace('SauceLabs ', '', $nextPossible['operatingsystems']['name']);
                        
            $browser = '{"username": "'.$sauce_username.'",'.
                ' "access-key": "'.$sauce_apikey.'",'.
                ' "os": "'.$OS.'",'.
                ' "browser": "'.$browser[0].'",'.
                ' "browser-version": "'.$browser[1].'",'.
                ' "sauce-referrer": "brominefoundation",'.
                ' "job-name": "'.$nextPossible['testcases']['name'].'"}';
            $browser = urlencode($browser);
        }else{
            $browser = $nextPossible['browsers']['path'];
        }
        
        
        //Put together the command line string 
        $cmd =  $command . $spacer . '"'.WWW_ROOT . 'testscripts' . DS . 
                $project_name . DS . $extension . DS . $nextPossible['testcases']['id'] . '.' . $extension .'"'. 
                $spacer . $this->servername . $spacer . $this->port . $spacer . $browser . $spacer . 
                '"'. $nextPossible['sites']['name'] . '"' . $spacer . $test_id . $spacer . $test_id;;
        //Execute it
        //echo $cmd;
        $this->execute($cmd, $test_id);
    }
    
    
    private function execute($cmd, $test_id) {
        //session_write_close(); //Needed for avoiding race conditions even when using ajax
        $this->log("Executing: $cmd", 'jobs');
        $this->log("Output printed to ".'"'.LOGS."$test_id.log".'"', 'jobs');
        
        if (substr(php_uname(), 0, 7) == "Windows"){ //Windows
            pclose(popen("start /B ".$cmd .' > '.'"'.LOGS. "testruns" . DS ."$test_id.log".'"'." && exit", "r")); 
        }
        else { //Unix.
            exec($cmd . " > ".LOGS. "testruns" . DS . "$test_id.log &");  
        }
    }
    
    private function getFileExt($id, $project_name){
        //session_write_close(); //Needed for avoiding race conditions even when using ajax
        $this->loadModel('Type');
        $extList = $this->Type->find('list', array('fields' => array('Type.extension')));
        foreach($extList as $ext){
            $file = WWW_ROOT.DS.'testscripts'.DS.$project_name.DS.$ext.DS.$id.".$ext";
            if(file_exists($file)){
                return $ext;
            }
        }
        return false;
    }

	function delete($id = null) {
		//session_write_close(); //Needed for avoiding race conditions even when using ajax
        if (!$id) {
			$this->Session->setFlash(__('Invalid id for Job', true));
			//$this->redirect($this->referer());
		}
		if ($this->Job->del($id)) {
			$this->Session->setFlash(__('Job deleted', true));
			//$this->redirect($this->referer());
		}
	}

}
?>