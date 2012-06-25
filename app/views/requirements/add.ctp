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

<style>

input {
	margin-bottom: 8px;
}

textarea {
	margin-bottom: 8px;
}

select {
	margin-bottom: 8px;
}

</style>

<div class="requirements form">
<?php echo $form->create('Requirement');?>
	<fieldset>
 		<legend><?php __('Add Requirement');?></legend>
	<?php
		echo $form->input('name');
		echo $form->input('description');
		//echo $form->input('testcases',array('label' => 'Testcase'));
		echo $form->hidden('project_id',array('value' => $session->read('project_id')));
		echo $form->input('parent_id',array('options' => $requirements, 'selected' => 0));
		echo $form->hidden('user_id',array('value' => $user_id));
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<script type="text/javascript" defer>
$('RequirementName').style.width = '' + Math.round(($('Main').offsetWidth / 4) * 3) + 'px'; 
$('RequirementDescription').style.width = '' + Math.round(($('Main').offsetWidth / 4) * 3) + 'px'; 
</script>

