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
class AppModel extends Model {    
    public $time;
    public $actsAs = array('Containable');
    
    public function saveActivity($text){
        
        $data['Activity']['id'] = null;
        $data['Activity']['user_id'] = $_SESSION['Auth']['User']['id'];
        $data['Activity']['content'] = $text;
        $data['Activity']['timestamp'] = null;
        if (!isset($_SESSION['project_id'])){
            $project_id = 0;
        }else{
            $project_id = $_SESSION['project_id'];
        }
        $data['Activity']['project_id'] = $project_id;
        Classregistry::init('Activity')->save($data);
    }
}

