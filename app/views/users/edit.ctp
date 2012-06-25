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
<div class="users form">
<?php echo $form->create('User');?>
	<fieldset>
 		<legend><?php __('Edit User');?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('name');
        echo $form->input('firstname');
		echo $form->input('lastname');
		echo "<a href='#' onclick='$(".'"'."changepw".'"'.").toggle();'>change password</a>";
        echo "<div id='changepw' style='display: none;'>";
		echo $form->input('newpw1',array('label'=>'New password', 'type'=>'password'));
		echo $form->input('newpw2',array('label'=>'New password', 'type'=>'password'));
		echo "</div>";
		echo $form->input('group_id');
		echo $form->input('email');
		echo $form->input('Project');
	?>
	</fieldset>
    <div class="cancel"><?php echo $html->link(__('Cancel', true), array('action'=>'index'));?></div>
<?php echo $form->end('Submit');?>
</div>
