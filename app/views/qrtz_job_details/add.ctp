<div class="qrtzJobDetails form">
<p>
Please see <a href="http://www.quartz-scheduler.org/docs/tutorials/crontrigger.html" target="_blank">http://www.quartz-scheduler.org/docs/tutorials/crontrigger.html</a> for instructions on the cron trigger format used
</p>
<?php echo $form->create('QrtzJobDetail');?>
	<fieldset>
 		<legend><?php __('Add QrtzJobDetail');?></legend>
	<?php
        echo $form->input('JOB_NAME',array('type' => 'text'));
        echo $form->input('URL');
        echo $form->input('CRON');
	?>
	</fieldset>
    <div class="cancel"><?php echo $html->link(__('Cancel', true), array('action'=>'index'));?></div>
<?php echo $form->end('Submit');?>
</div>
