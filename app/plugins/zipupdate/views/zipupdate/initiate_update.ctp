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
    if(!empty($errors)){
        echo "<h1>Don't worry no harm done</h1>";
        foreach($errors as $key => $error){
            echo "<p class='error'>";
            echo "<b>ERROR: $key</b><br />";
            if(is_array($error)){
                foreach($error as $err){
                    echo $err.'<br />';
                }
            }else{
                echo $error;
            }
            echo "</p>";
        }
    }else{
        echo "<h1>SUCCESS</h1>";
        echo "<b>Successfully downloaded patch, extracted patch and checked file permissions. Ready to upgrade.</b><br />";

        echo "<b>";        
        echo $ajax->link( 
            'Apply patch (no turning back from here)', 
            array( 'controller' => 'zipupdate', 'action' => 'doUpdate', $id), 
            array( 'update' => 'do_update', 'onclick' => '$("do_update").toggle();')
        );
        echo "</b>";        
    }
    
    
    
?>