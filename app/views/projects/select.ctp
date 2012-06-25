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
<h1><?php __('Select Project');?></h1>
<?php
if(!empty($userprojects)){
    echo $form->create('Project',array('action' => 'select'));
    $options[0]="";
    foreach($userprojects as $project){
        $options[$project['id']] = $project['name']; 
    }
    echo $form->input('project_id',array('options' => $options));
    echo $form->end('Select');
    echo "<br />";
}else{
    echo "You do not have access to any projects. Contact an administrator";
}
echo $html->link("Or go to the Control Panel",array('controller' => 'news', 'action' => 'index'));

?>
