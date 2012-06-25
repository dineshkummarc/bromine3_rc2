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
class NodesController extends AppController {

	public $helpers = array('Html', 'Form');
	public $main_menu_id = -2;


    function index() {
		$this->Node->recursive = 0;
		$nodes =  $this->paginate();
		foreach($nodes as &$node){
            $node['Node']['status'] = ($this->Node->checkJavaServer($node['Node']['nodepath']) ? 'passed.png' : 'failed.png'); 
        }
		$this->set('nodes', $nodes);
		$this->loadModel('Config');
        $sauce_username = $this->Config->field('value', array('name' => 'sauce_username'));
        $sauce_enabled = $this->Config->field('value', array('name'=> 'sauce_enabled'));
        $sauce_apikey = $this->Config->field('value', array('name'=> 'sauce_apikey'));
        $sauce_nodepath = $this->Config->field('value', array('name'=> 'sauce_nodepath'));
        $this->set('sauce_username', $sauce_username);
        $this->set('sauce_enabled', $sauce_enabled);
        $this->set('sauce_apikey', $sauce_apikey);
        $this->set('sauce_nodepath', $sauce_nodepath);
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Node.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('node', $this->Node->read(null, $id));
	}
	
	function clearCache(){
        $nodes = $this->Node->find('all');
        foreach($nodes as $node){
            $nodepath = explode(':',$node['Node']['nodepath']);
            $host = $nodepath[0];
            $port = $nodepath[1];
            
            Cache::delete('node_check_java_server_'.$host.$port);
        }
        $this->Session->setFlash('Cache has been cleared');
        $this->redirect($this->referer());
    }

	function add() {
		if (!empty($this->data)) {
			$this->Node->create();
			if ($this->Node->save($this->data)) {
				$this->Session->setFlash(__('The Node has been saved', true));
				$this->redirect(array('controller'=>'nodes', 'action'=>'index'));
			} else {
				$this->Session->setFlash('The Node could not be saved. Please, try again.', true, array('class'=>'error_message'));
			}
		}
		$browsers = $this->Node->Browser->find('list');
		$operatingsystems = $this->Node->Operatingsystem->find('list');
		$this->set(compact('browsers', 'operatingsystems'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Node', true), true, array('class'=>'error_message'));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->Node->save($this->data)) {
				$this->Session->setFlash(__('The Node has been saved', true));
				$this->redirect(array('controller'=>'nodes', 'action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Node could not be saved. Please, try again.', true), true, array('class'=>'error_message'));
			}
		}
		if (empty($this->data)) {
		  $this->data = $this->Node->read(null, $id);
		}
		$browsers = $this->Node->Browser->find('list');
		$operatingsystems = $this->Node->Operatingsystem->find('list');
		$this->set(compact('browsers','operatingsystems'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Node', true), true, array('class'=>'error_message'));
			$this->redirect(array('controller'=>'nodes', 'action'=>'index'));
		}
		if ($this->Node->del($id)) {
			$this->Session->setFlash(__('Node deleted', true));
			$this->redirect(array('controller'=>'nodes', 'action'=>'index'));
		}
	}

}
