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
class OperatingsystemsController extends AppController {

	public $helpers = array('Html', 'Form');
	public $main_menu_id = -2;

	function index() {
		$this->Operatingsystem->recursive = 0;
		$this->set('operatingsystems', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Operatingsystem.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('operatingsystem', $this->Operatingsystem->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Operatingsystem->create();
			if ($this->Operatingsystem->save($this->data)) {
				$this->Session->setFlash(__('The Operatingsystem has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash('The Operatingsystem could not be saved. Please, try again.', true, array('class' => 'error_message'));
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Operatingsystem', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->Operatingsystem->save($this->data)) {
				$this->Session->setFlash(__('The Operatingsystem has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Operatingsystem could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Operatingsystem->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Operatingsystem', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Operatingsystem->del($id, true)) { //NOTICE: CASCADE = TRUE
			$this->Session->setFlash(__('Operatingsystem deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}

}
