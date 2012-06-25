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
    if(!empty($CurrentAros)){
       echo "<h3 style='display: inline;'>".$CurrentAros['Myaro']['alias']."</h3>";
       echo "<br />";
    } 
    if(!empty($CurrentAros)){
        echo $html->link('..','#',array('onclick'=>
        "
          new Ajax.Updater('aros','manage_acl/listAros/".$CurrentAros['Myaro']['parent_id']."', {
                evalScripts: true
            });
          new Ajax.Updater('permissions','manage_acl/listAcos/".$CurrentAros['Myaro']['parent_id']."', {
                evalScripts: true
            });  
        "        
        ));
        echo "<br />";
    }
    foreach($Aros as $Aro){
        $id = $Aro['Myaro']['id'];
        echo $html->link(end(split('/',$Aro['Myaro']['alias'])),'#',array('onclick'=>
        "
            new Ajax.Updater('aros','manage_acl/listAros/$id', {
                evalScripts: true
            });
          new Ajax.Updater('permissions','manage_acl/listAcos/$id');  
        "        
        ));
        echo "<br />";
    }
?>
