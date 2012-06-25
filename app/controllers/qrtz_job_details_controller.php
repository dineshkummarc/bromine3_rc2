<?php
class QrtzJobDetailsController extends AppController {

    public $helpers = array('Html', 'Form');
    public $main_menu_id = -2;

    function start() {
        Cache::delete('state_of_the_system');
        $dir = realpath(APP.'..'.DS.'scheduler');
        chdir ($dir);
        $cmd = 'java -jar BromineScheduler.jar';
        if (substr(php_uname(), 0, 7) == "Windows") { //Windows
            pclose(popen("start /B ".$cmd, "r"));
        }
        else { //Unix.
            exec($cmd." > /dev/null &");
        }
        $this->redirect('index');
        exit;
    }

    function stop() {
        Cache::delete('state_of_the_system');
        $dir = realpath(APP.'..'.DS.'scheduler');
        chdir ($dir);
        $cmd = 'java -jar BromineSchedulerClient.jar kill false';
        exec($cmd, $output, $return_var);
        $this->redirect('index');
        exit;
    }

    function getStatus() {
        $dir = realpath(APP.'..'.DS.'scheduler');
        chdir ($dir);
        $exec = 'java -jar BromineSchedulerClient.jar ';
        exec($exec, $error);
        return $error;
    }

    function insertCheckJob() {
        $servername = $this->Config->field('value', array('name' => 'servername'));
        $port = $this->Config->field('value', array('name'=> 'port'));
        $this->data['QrtzJobDetail']['JOB_NAME'] = 'JobsChecker';
        $this->data['QrtzJobDetail']['URL'] = "http://$servername:$port/jobs/check";
        $this->data['QrtzJobDetail']['CRON'] = "0/10 * * * * ?";
        $this->add();
    }

    function index() {
        $states = $this->requestAction(array('controller'=>'configs', 'action' => 'stateOfTheSystem'));
        if($states['Java']['status'] === true) {
            if($states['Selfcontact']['status'] === true) {
                if($states['Scheduler']['status'] !== "ignored") {
                    $error = $this->getStatus();
                    $this->set('error', $error);
                    if(empty($error)) {
                        $this->QrtzJobDetail->recursive = 0;
                        $qrtzJobDetails = $this->paginate();
                        $job_name = $this->QrtzJobDetail->field('JOB_NAME', array('JOB_NAME' => 'JobsChecker'));
                        if(empty($job_name)) {
                            $this->set('nojobschecker', true);
                        }
                        foreach($qrtzJobDetails as &$qrtzJobDetail) {
                            $qrtzJobDetail['QrtzJobDetail']['CRON'] = $this->getCron($qrtzJobDetail['QrtzJobDetail']['JOB_NAME']);
                        }
                        $this->set('qrtzJobDetails',$qrtzJobDetails);
                    }
                }else {
                    $this->Session->setFlash(__('The scheduler is currently being ignored. Please correct this before trying to start the scheduler.', true),true, array('class'=>'error_message'));
                    $this->redirect(array('controller' => 'configs', 'action' => 'stateOfTheSystem'));
                }

            }else {
                $this->Session->setFlash(__('Selfcontact failed or is set to ignored. Please correct this before trying to start the scheduler.', true),true, array('class'=>'error_message'));
                $this->redirect(array('controller' => 'configs', 'action' => 'stateOfTheSystem'));
            }
        } else {
            $this->Session->setFlash(__('Java is not installed or is set to ignored. Please correct this before trying to start the scheduler.', true),true, array('class'=>'error_message'));
            $this->redirect(array('controller' => 'configs', 'action' => 'stateOfTheSystem'));
        }
    }

    function add() {
        if(isset($this->data)) {
            if (!empty($this->data['QrtzJobDetail']['JOB_NAME']) || !empty($this->data['QrtzJobDetail']['URL']) || !empty($this->data['QrtzJobDetail']['CRON'])) {
                $jobName = $this->data['QrtzJobDetail']['JOB_NAME'];
                $url = $this->data['QrtzJobDetail']['URL'];
                $cron = $this->data['QrtzJobDetail']['CRON'];

                $args = '"add" "'.$jobName.'" "'.$url.'" "'.$cron.'"';
                $dir = realpath(APP.'..'.DS.'scheduler');
                chdir($dir);
                $exec = 'java -jar BromineSchedulerClient.jar '.$args;
                exec($exec, $output);
                if(empty($output)) {
                    $this->Session->setFlash(__('The schedule has been saved', true));
                    $this->redirect(array('action'=>'index'));
                } else {
                    $output = implode('<br />', $output);

                    $this->Session->setFlash(__('The schedule was not saved. <br /> '.$output, true),true, array('class'=>'error_message'));
                    $this->redirect(array('action'=>'index'));
                }
            } else {
                $this->Session->setFlash(__('All fields must be filled out!', true));
                $this->redirect(array('action'=>'add'));
            }
        }
    }

    function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid schedule', true));
            $this->redirect(array('action'=>'index'));
        }
        if(isset($this->data)) {
            if (!empty($this->data['QrtzJobDetail']['JOB_NAME']) || !empty($this->data['QrtzJobDetail']['URL']) || !empty($this->data['QrtzJobDetail']['CRON'])) {
                $url = $this->data['QrtzJobDetail']['URL'];
                $cron = $this->data['QrtzJobDetail']['CRON'];

                $args = '"modify" "'.$id.'" "'.$url.'" "'.$cron.'"';
                $dir = realpath(APP.'..'.DS.'scheduler');
                chdir($dir);
                $exec = 'java -jar BromineSchedulerClient.jar '.$args;
                exec($exec, $output);
                if(empty($output)) {
                    $this->Session->setFlash(__('The schedule has been saved', true));
                    $this->redirect(array('action'=>'index'));
                } else {
                    $output = implode('<br />', $output);

                    $this->Session->setFlash(__('The schedule was not saved. <br /> '.$output, true),true, array('class'=>'error_message'));
                    $this->redirect(array('action'=>'index'));
                }
            } else {
                $this->Session->setFlash(__('All fields must be filled out!', true));
                $this->redirect(array('action'=>'add'));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->QrtzJobDetail->read(null, $id);
            $this->set('cron',$this->getCron($id));
            $this->set('url',$this->getURL($id));
        }
    }

    function delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for schedule', true));
            $this->redirect(array('action'=>'index'));
        }
        $dir = realpath(APP.'..'.DS.'scheduler');
        chdir($dir);
        $args = '"remove" "'.$id.'"';
        $exec = 'java -jar BromineSchedulerClient.jar '.$args;
        exec($exec, $output);
        $cron = $this->getCron($id);
        if(empty($output)) {
            if($cron != '') {
                $this->Session->setFlash(__('Could not delete schedule', true));
                $this->redirect(array('action'=>'index'));
            }else {
                $this->Session->setFlash(__('Schedule deleted successfully', true));
                $this->redirect(array('action'=>'index'));
            }
        }
    }

    private function getCron($id) {
        $dir = realpath(APP.'..'.DS.'scheduler');
        chdir($dir);
        $args = '"cron" "'.$id.'"';
        $exec = 'java -jar BromineSchedulerClient.jar '.$args;
        $output = exec($exec);
        if(stristr($output, 'exception')) {
            $output = '';
        }
        return $output;
    }

    private function getURL($id) {
        $dir = realpath(APP.'..'.DS.'scheduler');
        chdir($dir);
        $args = '"url" "'.$id.'"';
        $exec = 'java -jar BromineSchedulerClient.jar '.$args;
        $output = exec($exec);
        return $output;

    }

}
?>