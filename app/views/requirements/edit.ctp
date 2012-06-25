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


submit {
	margin-top: 12px;
	margin-left 0px;
}

</style>

<div style='float: right;'>
<?php
    echo $ajax->link( 
            $html->image('tango/32x32/actions/go-previous.png').'<br />Back', 
            array( 'controller' => 'requirements', 'action' => 'view', $requirement['Requirement']['id']), 
            array( 'update' => 'Main', 'class'=>'requirements view', 'id' => 'cancel'), null, false);
    echo "<br />";
    echo "<br />";
    echo $html->link( 
            $html->image('tango/32x32/places/user-trash.png').'<br />Delete', 
            array( 'controller' => 'requirements', 'action' => 'delete', $requirement['Requirement']['id']), 
            array( 'class'=>'requirements delete', 'id' => 'delete'),
            'Are you sure you want to delete this requirement?', false
            );
?>
</div>
<div class="requirements form">
<?php echo $form->create('Requirement');?>
	<fieldset>
 		<legend><?php __('Edit Requirement');?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('name');
		echo $form->input('description');
		echo $form->input('user_id', array('label' => 'Owner'));
		echo $form->hidden('project_id');
	?>
	</fieldset>
<?php 
    echo $ajax->submit("submit", array("url" => array('controller'=>'Requirements','action'=>'edit',$requirement['Requirement']['id']), "update" => "Main"));
    echo $form->end();
?>
<?php
    //@todo make this make with a confirm before submitting!
    //Todo: Make this work when there are no testcases attached
    //Todo: Make this also copy the combinations
    /*
    echo "<br />";
    echo $form->create('Project',array('url'=>array('controller'=>'Requirements','action' => 'copy', 'plugin' => null),'onsubmit'=>"return confirm('Are you sure you want to delete this requirement?')"));
    $options[0] = 'Select project to copy to...';
    foreach($userprojects as $project){
        $options[$project['id']] = $project['name'];
    }//
    echo $form->input('project_id',array('options' => $options, 'selected'=>0, 'label' => 'Copy requirement to a project', 'onchange' => 'form.submit()'));
    echo $form->hidden('requirement_id', array('value' => $requirement['Requirement']['id']));
    echo $form->end();
    echo "<br />";
    */
?>

<script type="text/javascript" defer>
$('RequirementName').style.width = '' + Math.round(($('Main').offsetWidth / 4) * 3) + 'px'; 
$('RequirementDescription').style.width = '' + Math.round(($('Main').offsetWidth / 4) * 3) + 'px'; 
</script>

<div class='requirements combinations'>
<?php
    echo $table->createTable($browsers, $operatingsystems, $combinations, true, $requirement['Requirement']['id'])
?>
</div>
</div>
