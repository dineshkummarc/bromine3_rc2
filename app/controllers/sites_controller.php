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
class SitesController extends AppController {

	public $helpers = array('Html', 'Form');
	public $main_menu_id = -2;
	
	function select(){
        $this->Session->write('site_id',$this->data['Site']['site_id']);
        $anchor = $this->data['Site']['anchor'] != 'false' ? '#'.$this->data['Site']['anchor'] : '';  
        $this->redirect($this->referer().$anchor);
    }
    
    function delete($site_id){
        $this->Site->del($site_id);
    }

	function add() {
	}
}