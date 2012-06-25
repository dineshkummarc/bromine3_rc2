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
    if ($status){
        $class = 'success';
    }else{
        $class = 'error';
    }
    echo "<div class='$class'>";
    echo "Bromine tried to contact http://$servername:$port/configs/iamhere and ".($status ? '<b>succeded</b>' : '<b>failed</b>');
    echo '</div><br />';
    if(!$status && $servername=='localhost'){
        echo "<p>Try using 127.0.0.1 instead of localhost</p>";
    }
    echo $form->create('Config',array('action'=>'server'));
	
    echo $form->input('servername', array('label' => 'Servername (use "127.0.0.1" instead of "localhost"):', 'value' => $servername));
    echo "<br>";
	echo $form->input('port', array('label' => 'Port:', 'value' => $port));

    echo $form->end('Submit');
?>
