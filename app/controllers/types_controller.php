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
class TypesController extends AppController {

	public $helpers = array('Html', 'Form');
	public $main_menu_id = -2;
	public $paginate = array(
        'order'=>array('id DESC')
    );

	function index() {
		$this->Type->recursive = 0;
		$this->set('types', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Type.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('type', $this->Type->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Type->create();
			if ($this->Type->save($this->data)) {
				$this->Session->setFlash(__('The Type has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash('The Type could not be saved. Please, try again.', true, array('class' => 'error_message'));
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Type', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->Type->save($this->data)) {
				$this->Session->setFlash(__('The Type has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash('The Type could not be saved. Please, try again.', true, array('class' => 'error_message'));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Type->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Type', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Type->del($id)) {
			$this->Session->setFlash(__('Type deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}

}
