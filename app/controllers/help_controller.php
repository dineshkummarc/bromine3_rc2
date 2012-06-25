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
class HelpController extends AppController {
    public $uses = array();
    public $helpers = array('Cache');
    public $cacheAction = "1 week";
    
    function beforeFilter(){
        parent::beforeFilter();
    }
    
    function show($headline, $subheadline = null){
        $this->redirect("http://wiki.brominefoundation.org/documentation:$headline:$subheadline");
        exit;
    }
    
}
