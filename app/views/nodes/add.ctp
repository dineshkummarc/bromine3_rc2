<?php
/*
Copyright 2007, 2008, 2009 Rasmus Berg Palm, Visti Kl�ft and Jeppe Poss Pedersen 

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
<div class="nodes form">
<?php echo $form->create('Node');?>
	<fieldset>
 		<legend><?php __('Add Node');?></legend>
	<?php
		echo $form->input('nodepath');
		echo $form->input('operatingsystem_id');
		echo $form->input('description');
		echo $form->input('limit');
		echo "Defines how many tests can be run simultaneously on this node";
		echo $form->input('Browser');
	?>
	</fieldset>
    <div class="cancel"><?php echo $html->link(__('Cancel', true), array('action'=>'index'));?></div>
<?php echo $form->end('Submit');?>
</div>