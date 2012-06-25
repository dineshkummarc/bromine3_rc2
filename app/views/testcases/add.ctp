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

<div class="testcases form">
<?php echo $form->create('Testcase');?>
	<fieldset>
 		<legend><?php __('Add Testcase');?></legend>
	<?php
		echo $form->input('name');
		echo $form->input('description');
		echo $form->input('Requirement');
		echo $form->hidden('project_id',array('value' => $session->read('project_id')));
		echo $form->hidden('parent_id',array('value' => 0));
		echo $form->hidden('user_id',array('value' => $user_id));
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<script type="text/javascript" defer>
$('TestcaseName').style.width = '' + Math.round(($('Main').offsetWidth / 4) * 3) + 'px'; 
$('TestcaseDescription').style.width = '' + Math.round(($('Main').offsetWidth / 4) * 3) + 'px'; 
</script>

