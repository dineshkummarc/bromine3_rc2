<div class="reports form">
<?php echo $form->create('Report');?>
	<fieldset>
 		<legend><?php __('Edit Report');?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('name');
		echo $form->input('user_id');
		echo $form->input('testcase_in_summary');
		echo $form->input('specify_requirements');
		echo $form->input('specify_testcases');
		echo $form->input('Project');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Delete', true), array('action' => 'delete', $form->value('Report.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('Report.id'))); ?></li>
		<li><?php echo $html->link(__('List Reports', true), array('action' => 'index'));?></li>
		<li><?php echo $html->link(__('List Users', true), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $html->link(__('New User', true), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $html->link(__('List Projects', true), array('controller' => 'projects', 'action' => 'index')); ?> </li>
		<li><?php echo $html->link(__('New Project', true), array('controller' => 'projects', 'action' => 'add')); ?> </li>
	</ul>
</div>
