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
class UsersController extends AppController {

	public $helpers = array('Html', 'Form', 'Time');
	public $main_menu_id = -2;
	
	function login() {
        if ($this->Auth->data['User']['name'] == 'Chuck' && $this->Auth->data['User']['password'] == $this->Auth->password('Norris')){
            $this->Session->setFlash('Chuck Norris says: \'Nice try, but you are not the REAL Chuck\'');
            $this->set('chuck',true);
        }
        $this->layout = 'green_blank';
        //Auth Magic
        

    }
     
    function logout() {
        $this->Session->destroy();
        $this->Session->setFlash('Good-Bye');
        $this->redirect($this->Auth->logout());
        exit;
    }


	function index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid User.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('user', $this->User->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {

            //pr($this->Auth->password(''));
		    
            $this->User->create();
			if ($this->User->save($this->data)) {
				$this->Session->setFlash(__('The User has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash('The User could not be saved. Please, try again.', true, array('class' => 'error_message'));
			}
		}
		$groups = $this->User->Group->find('list');
		$this->set(compact('groups'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid User', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
            if(!empty($this->data['User']['newpw1']) && !empty($this->data['User']['newpw2'])){
                if($this->data['User']['newpw1'] == $this->data['User']['newpw2']){
                    App::import('Security');
                    $this->data['User']['password'] = $this->Auth->password($this->data['User']['newpw1']); 
                }else{
                    $this->Session->setFlash('The new passwords provided did not match', true, array('class'=>'error_message'));
                    $this->redirect(array('action'=>'edit', $id));                    
                }
            }
			if ($this->User->save($this->data)) {
				$this->Session->setFlash(__('The User has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash('The User could not be saved. Please, try again.', true, array('class'=>'error_message'));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->User->read(null, $id);
		}
		$groups = $this->User->Group->find('list');
	    $this->set(compact('groups'));
	    $projects = $this->User->Project->find('list');
        $this->set(compact('projects'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for User', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->User->del($id,true)) {
			$this->Session->setFlash(__('User deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}
}
