<?php
class ReportsController extends AppController {

	var $helpers = array('Html', 'Form');

	function index() {
		$this->Report->recursive = 0;
		$this->set('reports', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Report.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Report->Project->bindModel(array('hasOne' => array('ProjectsReport')));
        $projects = $this->Report->Project->find('all', array(
            'conditions' => array(
                'ProjectsReport.report_id' => $id
            ),
            'contain' => array(
                'ProjectsReport',
                'Site'
            )
        ));
        foreach($projects as &$project){
            $project['Data'] = $this->Report->Project->Requirement->find('threaded', array(
                'conditions' => array(
                    'project_id' => $project['Project']['id'] 
                ),
                'contain' => array(
                    'Testcase'
                )
            ));
            foreach($project['Data'] as &$data){
                $data = $this->getStatusRecursive($data, $project['Site']);    
            }    
        }
        $this->set('projects', $projects);
	}
	
	function getStatusRecursive(&$data, $sites){
        foreach($sites as $site){
            
            $data['Requirement']['status'][$site['name']] = $this->Report->Project->Requirement->getStatus($data['Requirement']['id'], $site['id']);
            foreach($data['Testcase'] as &$testcase){
                $testcase['status'][$site['name']] = $this->Report->Project->Requirement->Testcase->getStatus($testcase['id'], $data['Requirement']['id'], $site['id']);            
            }
        }		
		if(!empty($data['children'])){
            foreach($data['children'] as &$child){
                $this->getStatusRecursive($child, $sites);
            }
        }
        
        return $data;
    }

	function add() {
		if (!empty($this->data)) {
			$this->Report->create();
			if ($this->Report->save($this->data)) {
				$this->Session->setFlash(__('The Report has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Report could not be saved. Please, try again.', true));
			}
		}
		$projects = $this->Report->Project->find('list');
		$users = $this->Report->User->find('list');
		$this->set(compact('projects', 'users'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Report', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->Report->save($this->data)) {
				$this->Session->setFlash(__('The Report has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Report could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Report->read(null, $id);
		}
		$projects = $this->Report->Project->find('list');
		$users = $this->Report->User->find('list');
		$this->set(compact('projects','users'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Report', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Report->del($id)) {
			$this->Session->setFlash(__('Report deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}

}
?>