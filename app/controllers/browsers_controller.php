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
class BrowsersController extends AppController {

	public $helpers = array('Html', 'Form');
	public $main_menu_id = -2;

	function index() {
		$this->Browser->recursive = 0;
		$this->set('browsers', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Browser.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('browser', $this->Browser->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Browser->create();
			if ($this->Browser->save($this->data)) {
				$this->Session->setFlash(__('The Browser has been saved', true));
				$this->redirect(array('action'=>'index'));
				
			} else {
				$this->Session->setFlash('The Browser could not be saved. Please, try again.', true, array('class' => 'error_message'));
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Browser', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->Browser->save($this->data)) {
				$this->Session->setFlash(__('The Browser has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Browser could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Browser->read(null, $id);
		}
	}
	
	function delete($id = null) {  
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Browser', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Browser->del($id, true)) {   //NOTICE: CASCADE = TRUE             
			$this->Session->setFlash(__('Browser deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}

}
