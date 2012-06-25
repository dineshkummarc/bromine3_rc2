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
/**
 * TableHelper class.
 *
 * Creates a table of browser/OS and highlights a passed array of combinations
 */

/**
 * 
 * @Author: Rasmus Berg Palm and Visti Kløft
*/


class TableHelper extends Helper{

    var $helpers = array('Html','Ajax','Javascript');
    var $output = "";
    
    function createTable($browsers, $operatingsystems, $combinations=array(), $edit=false, $id=null){

        $this->output .= "<table class='combinations' cellspacing='0' cellspacing='0' style='width: 400px;'>";
        $this->output .= "<tr>";
        $this->output .= "<td></td>";
        foreach($operatingsystems as $os){
            $this->output .= "<td>".$os['Operatingsystem']['name']."</td>";
        }
        $this->output .= "</tr>";
        foreach($browsers as $browser){
            $this->output .= "<tr>";
            $u=0;
            foreach($operatingsystems as $os){
                if($u==0){
                    $this->output .= "<td>".$browser['Browser']['name']."</td>";
                }
                $u++;
                
                $checked = false;
                $disabled = "DISABLED='DISABLED'";
                if($edit){
                    $disabled = false;
                }
                foreach($combinations as $combination){
                    if($os['Operatingsystem']['id'] == $combination['Operatingsystem']['id'] && $browser['Browser']['id'] == $combination['Browser']['id']){
                        $checked = "CHECKED='CHECKED'";   
                    }
                }
                $this->output .= "<td>";

                $values = $browser['Browser']['id'].'/'.$os['Operatingsystem']['id'].'/'.$id;
                $this->output .=  "<input type='checkbox' $checked $disabled onclick='new Ajax.Updater(".'"log",'.'"'."requirements/updateCombination/$values".'"'.")' />";

                
                $this->output .= "</td>";
            }
            $this->output .= "</tr>";
        }
        $this->output .= "</table>";
        return $this->output;
    }
    
}
?>
