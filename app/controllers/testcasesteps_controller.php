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
class TestcasestepsController extends AppController {

	public $helpers = array('Html', 'Form');

	function index() {
		$this->Testcasestep->recursive = 0;
		$this->set('testcasesteps', $this->paginate());
	}
	
	function reorder($order){    
        $order = split(',',$order);
        foreach($order as $k=>$v){
            $this->data['Testcasestep']['id'] = $v;
            $this->data['Testcasestep']['orderby'] = $k;
            $this->Testcasestep->save($this->data);    
        }
        
        //$this->set('data',$this->data);
    }

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Testcasestep.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('testcasestep', $this->Testcasestep->read(null, $id));
	}

	function add($testcase_id = null) {
	   $this->set('testcaseid', $testcase_id);
	   $orderby = $this->Testcasestep->find('count',
           array(
            	'conditions' => array('Testcasestep.testcase_id' => $testcase_id), //array of conditions
           )
       )+1;
       
		if (!empty($this->data)) {
            $this->data['Testcasestep']['orderby'] = $orderby;
            $this->Testcasestep->create();
			if ($this->Testcasestep->save($this->data)) {
				$this->Session->setFlash(__('The Testcasestep has been saved', true));
				$this->redirect("testcases/edit/$testcase_id");
			} else {
				$this->Session->setFlash(__('The Testcasestep could not be saved. Please, try again.', true));
			}
		}
	}

	function edit($id = null, $type=null) {
	   $value = $_POST['value'];
	   $this->data['Testcasestep']['id'] = $id;
	   $this->data['Testcasestep'][$type] = $value;
	   if ($this->Testcasestep->save($this->data)) {
	       $this->Session->setFlash("The $type was saved",true);
	       $this->set('value',$value);
	   }else{
	       $this->setFlash("The $type could not be saved.",'err');
	       $this->set('value',"The $type could not be saved.");
	   }
	   /*
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Testcasestep', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->Testcasestep->save($this->data)) {
				$this->Session->setFlash(__('The Testcasestep has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Testcasestep could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Testcasestep->read(null, $id);
		}
		$testcases = $this->Testcasestep->Testcase->find('list');
		$this->set(compact('testcases'));
		*/
	}

	function delete($id = null, $testcase_id=null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Testcasestep', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Testcasestep->del($id)) {
			$this->Session->setFlash(__('Testcasestep deleted', true));
			$this->redirect("testcases/edit/$testcase_id");
		}
	}

}
