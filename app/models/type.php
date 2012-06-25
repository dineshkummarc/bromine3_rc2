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
class Type extends AppModel {
var $validate = array(
        'name' => array(
            'nameRule-1' => array(
                'rule' => 'notempty',  
                'message' => 'Type name can\'t be empty'
             ),
            'nameRule-2' => array(
                'rule' => 'isUnique',  
                'message' => 'The is already a type with that name'
            )  
        ),
        'command' => array(
            'commandRule-1' => array(
                'rule' => 'notempty',  
                'message' => 'Command can\'t be empty'
             ) 
        ),
        'spacer' => array(
            'spacerRule-1' => array(
                'rule' => 'notempty',  
                'message' => 'Spacer can\'t be empty'
             ) 
        ),
        'extension' => array(
            'extensionRule-1' => array(
                'rule' => 'notempty',  
                'message' => 'Extension can\'t be empty'
             ),
            'extensionRule-2' => array(
                'rule' => 'isUnique',  
                'message' => 'The is already an extension with that name'
            )  
        )
    );
}
