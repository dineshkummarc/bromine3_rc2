<div class="qrtzJobDetails form">
<?php echo $form->create('QrtzJobDetail');?>
	<fieldset>
 		<legend><?php __('Edit QrtzJobDetail');?></legend>
	<?php
		echo $form->input('JOB_NAME');
                echo $form->input('URL', array('value'=>$url));
                echo $form->input('CRON', array('value'=>$cron));
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Delete', true), array('action' => 'delete', $form->value('QrtzJobDetail.JOB_NAME')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('QrtzJobDetail.JOB_NAME'))); ?></li>
		<li><?php echo $html->link(__('List QrtzJobDetails', true), array('action' => 'index'));?></li>
	</ul>
</div>
