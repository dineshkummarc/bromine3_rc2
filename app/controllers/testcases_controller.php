<?php
/*
Copyright 2007, 2008, 2009 Rasmus Berg Palm, Visti Kl�ft and Jeppe Poss Pedersen 

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
class TestcasesController extends AppController {

    public $helpers = array('Html', 'Form', 'Table', 'Time', 'Scheduler');
    public $components = array('Script', 'Scheduler');

    function index() {
        $this->Testcase->recursive = 0;
        $this->set('testcases', $this->paginate(null, array('Project.id' => $this->Session->read('project_id'))));
    }

    function lilist($search=null) {

        $conditions = array('Project.id' => $this->Session->read('project_id'));
        if(!empty($this->data['tcsearch'])) {
            $conditions['Testcase.name  LIKE'] = $this->data['tcsearch']."%";
        }
        $this->Testcase->recursive = 0;
        $this->set('testcases', $this->Testcase->find('all',array('conditions'=>$conditions,'order'=>'Testcase.name asc')));
    }


    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid Testcase.', true));
            $this->redirect(array('action'=>'index'));
        }
        $this->set('testcase', $this->Testcase->read(null, $id));
        $testcasesteps = $this->Testcase->TestcaseStep->findAll(array('testcase_id' => $id),null,array('order by' => 'TestcaseStep.orderby'));
        $this->set('testcasesteps',$testcasesteps);

        if($script=$this->Script->getTestScript($id)) {
            $this->set('testscript',$script);
        }
    }

    function testlabview($id = null, $requirement_id) {
        //tic('all');
        if (!$id) {
            $this->Session->setFlash(__('Invalid Testcase.', true));
            $this->redirect(array('action'=>'index'));
        }

        // For getting historical data
        $this->Testcase->Behaviors->attach('Containable');
        $this->loadModel('Suite');

        $history = $this->Testcase->Test->find('all',
                array(
                'contain' => array(
                        'Suite.site_id',
                        'Operatingsystem.name',
                        'Browser.name'
                ),
                'conditions' => array('Testcase_id' => $id, 'Suite.site_id' => $this->Session->read('site_id')),
                'order' => 'Test.suite_id DESC',
                'limit' => 20
                )
        );

        $this->set('history', $history);

        $testcase = $this->Testcase->read(null, $id);
        $this->set('testcase', $testcase);
        $testcasesteps = $this->Testcase->TestcaseStep->findAll(array('testcase_id' => $id),null,array('order by' => 'TestcaseStep.orderby'));
        $this->set('testcasesteps',$testcasesteps);

        $this->Testcase->Requirement->Behaviors->attach('Containable');
        $requirement = $this->Testcase->Requirement->find('first', array(
                'conditions'=>array(
                        'Requirement.id'=>$requirement_id
                ),
                'contain'=>array(
                        'Testcase',
                        'Combination' => array(
                                'Browser',
                                'Operatingsystem'
                        ),
                        'User'
                )
        ));

        App::import('Model','Node');
        $this->Node = new Node();
        $nodes = $this->Node->find('all');
        $onlineNodes = array();
        foreach($nodes as &$node) {
            if($this->Node->checkJavaServer($node['Node']['nodepath'])) {
                $onlineNodes[] = $node;
            }
        }
        foreach ($requirement['Combination'] as &$combination) {
            $combination['Result'] = $this->Testcase->Test->getLastInCombination($id, $combination['Operatingsystem']['id'], $combination['Browser']['id'], $this->Session->read('site_id'));
        }
        $files = $this->Script->getTestScript($id);
        if($this->Script->filename == '') {
            $this->set('noScript', "No test script uploaded.");
        }elseif (!is_array($files)) {
            $this->set('modified',filemtime($this->Script->filename));

        }elseif(is_array($files)) {
            foreach($files as $file) {
                $scripts[$file] = filemtime($file);
            }
            $this->set('modified',$scripts);

        }
        $this->set('nodes', $nodes);
        $this->set('requirement', $requirement);
        $this->set('onlineNodes', $onlineNodes);
        $this->set('combinations',$requirement['Combination']);

        $states = $this->requestAction(array('controller'=>'configs', 'action' => 'stateOfTheSystem'));
        $errors = array();
        foreach($states as $state) {
            if($state['status'] == false) {
                $errors[] = $state;
            }
        }
        if(!empty($errors)) {
            $this->set('stateOfTheSystemErrors', $errors);
        }
        //tic('cron');
        $this->set('cron',$this->Scheduler->getCron('Test',$id, $this->Session->read('site_id')));
        //toc('cron');

        $this->loadModel('Config');
        $this->set('servername', $this->Config->field('value', array('name'=>'servername')));
        $this->set('port', $this->Config->field('value', array('name'=>'port')));
        //toc('all');
    }


    function viewscript($id = null) {
        $this->layout = 'green_blank';
        if (!$id) {
            $this->Session->setFlash(__('Invalid Testcase.', true));
            $this->redirect(array('action'=>'index'));
        }else {
            $this->set('testscript',$this->Script->getTestScript($id));
        }
    }

    function cloneThis($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid Testcase.', true));
            $this->redirect(array('action'=>'index'));
        }
        $testcase = $this->Testcase->read(null, $id);
        $newtestcase['Testcase'] = $testcase['Testcase'];
        $newtestcase['Testcase']['id'] = null;
        $newtestcase['Testcase']['name'] = $newtestcase['Testcase']['name'] . ' (Clone)';

        $newtestcase['Project'] = $testcase['Project'];
        $newtestcase['User'] = $testcase['User'];

        $this->Testcase->saveAll($newtestcase);

        $newTestcaseId = $this->Testcase->getLastInsertId();
        $newtestcase = $this->Testcase->read(null, $newTestcaseId);

        // Add [TestcaseStep]
        foreach($testcase['TestcaseStep'] as $teststep) {
            $teststep['id'] = null;
            $teststep['testcase_id'] = $newTestcaseId;
            $newtestcase['TestcaseStep'][] = $teststep;
        }

        // Add [Requirement]
        foreach($testcase['Requirement'] as $testreq) {
            $testreq['RequirementsTestcase']['id'] = null;
            $testreq['RequirementsTestcase']['testcase_id'] = $newTestcaseId;
            $newtestcase['Requirement'][] = $testreq;
        }

        $this->Testcase->saveAll($newtestcase);
        $this->redirect($this->referer()."#/testcases/edit/$newTestcaseId");
        /*$newtestcase = $this->Testcase->read(null, $newTestcaseId);
        $this->set('testcase', $newtestcase);*/

    }


    function add() {
        if (!empty($this->data)) {
            $this->Testcase->create();
            if ($this->Testcase->save($this->data)) {
                $this->Session->setFlash(__('The Testcase has been saved', true));
                $this->redirect(
                        array(
                        'controller'=>'requirements#/testcases',
                        'action' => 'view',
                        $this->Testcase->id
                        )
                );
            } else {
                $this->Session->setFlash('The Testcase could not be saved. Please, try again.', true, array('class' => 'error_message'));
            }
        }
        $projects = $this->Testcase->Project->find('list');
        $Requirements = $this->Testcase->Requirement->find('list', array('conditions' => array('Requirement.project_id' => $this->Session->read('project_id'))));
        $this->set(compact('Requirements'));
    }

    function addNew($id = null) {
        $projects = $this->Testcase->Project->find('list');
        $Requirements = $this->Testcase->Requirement->find('list', array('conditions' => array('Requirement.project_id' => $this->Session->read('project_id'))));
        $this->set(compact('Requirements'));
        $this->set('chosenRequirement', $id);
        $this -> render('add');
    }

    function edit($id = null) {
        $this->Testcase->recursive = 1;
        if (!$id && empty($this->data)) {
            $this->Session->setFlash('Invalid Testcase', true, array('class'=>'error'));
            $this->redirect(array('action'=>'index'));
        }
        if (!empty($this->data)) {
            if ($this->Testcase->save($this->data)) {
                $this->Session->setFlash(__('The Testcase has been saved', true));
                $this->redirect('testcases/view/'. $id);
            } else {
                $this->Session->setFlash('The Testcase could not be saved. Please, try again.', true, array('class'=>'error'));
            }
        }
        if($id) {
            $this->data = $this->Testcase->read(null, $id);
            //$this->Testcase->Requirement->recursive = 2;
            $req_owner = $this->Testcase->Requirement->find('first',array('Testcase.id' => $id));
            if ($this->data['User']['id'] == $this->Auth->user('id') ||
                    $this->Auth->user('group_id') == 1
            //$req_owner['User']['id'] == $this->Auth->user('id')
            ) { // Check if you are the owner of the tc or an admin. Or if req owner is logged in or req_owner is admin
                $testcasesteps = $this->Testcase->TestcaseStep->findAll(array('testcase_id' => $id),null,array('order by' => 'TestcaseStep.orderby'));
                $this->set('testcasesteps',$testcasesteps);
                if($script=$this->Script->getTestScript($id)) {
                    $this->set('testscript',$script);
                }
                $users_sql = $this->Testcase->User->find('all',array('fields' => array('User.id','User.firstname' , 'User.lastname')));
                foreach($users_sql as $user) {
                    $users[$user['User']['id']] = $user['User']['firstname'] . ' ' . $user['User']['lastname'];

                }
                $this->set(compact('users'));
            }else {
                $this->Session->setFlash("Error: You are not allowed to edit this testcase. You should either be an admin or owner of the testcase", true, array('class' => 'error'));
                $this->redirect('testcases/view/'. $id);
            }
        }
    }

    function upload($id = null) {

        $this->layout = 'green_blank';
        $files = $this->Script->getTestScript($id, true);
        if(!empty($files)) {
            $this->Session->setFlash("Warning: Uploading a new file will remove the old one.", true, array('class' => 'warning'));
        }
        if($id) {
            $this->set('id',$id);
            if($this->data['Testcase']['testscript']['name']!='') {
                $ext = end(explode('.', $this->data['Testcase']['testscript']['name']));
                $dir = WWW_ROOT.'testscripts'.DS.$this->Session->read('project_name').DS.$ext;
                if(!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }
                $uploadfile = $dir.DS.$id.".$ext";
                App::import('Model','Type');
                $this->Type = new Type();
                $extList = $this->Type->find('list', array('fields' => array('Type.extension')));
                if(in_array($ext, $extList)) {
                    if(!empty($files)) {
                        foreach($files as $file) {
                            if(!unlink($file)) {
                                $this->Session->setFlash('Error: Could not delete file: '.$file.' please delete manually before trying to upload file again.', true, array('class' => 'error'));
                                exit();
                            }
                        }
                    }
                    if (move_uploaded_file($this->data['Testcase']['testscript']['tmp_name'], $uploadfile)) {
                        $this->Session->setFlash(__('The Testscript has been uploaded',true));
                        $this->set('uploaded',true);
                    }else {
                        $this->Session->setFlash('Error: Script not uploaded. The file could not be uploaded. Check folder permissions', true, array('class' => 'error'));
                    }
                }else {
                    $this->Session->setFlash('Error: Script not uploaded. Filetype not accepted. The accepted filetypes are '.implode(', ', $extList), true, array('class' => 'error'));
                }
            }
        }else {
            $this->Session->setFlash(__('No testcase id provided',true));
        }
    }

    function delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for Testcase', true));
            $this->redirect(array('controller'=>'requirements','action'=>'index'));
        }
        if ($this->Testcase->del($id)) {
            App::import('Model', 'Site');
            $this->Site = new Site();
            $sites = $this->Site->find('list', array('conditions' => array('Site.project_id' => $this->Session->read('project_id')), 'fields' => array('Site.id')));
            foreach($sites as $site) {
                $this->deleteSchedule($id, $site);
            }
            $this->Session->setFlash(__('Testcase deleted', true));
            $this->redirect(array('controller'=>'requirements','action'=>'index'));
        }
    }

    function saveSchedule($modify = false) {
        $outputs = $this->Scheduler->saveSchedule('Testcase', 'Test', $modify);
        foreach($outputs as $key => $output) {
            $this->set($key, $output);
        }
        $this->render('schedule');
    }

    function modifySchedule() {
        $this->saveSchedule(true);
    }

    function deleteSchedule($id, $site_id, $url=null) {
        $outputs = $this->Scheduler->deleteSchedule('Test', $id, $site_id, $url);
        foreach($outputs as $key => $output) {
            $this->set($key, $output);
        }
        $this->render('schedule');
    }
}
