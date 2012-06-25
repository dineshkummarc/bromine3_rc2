<div class="qrtzJobDetails index">
<h1>Bromine scheduler</h1>
<?php
        if(empty($error)){
            echo "<div class='success'>The scheduler is running.</div>";
        } else{
            echo "<div class='error'>The scheduler is not running.<br />";
            echo '<a onclick="Effect.toggle(\'scheduleroutput\',\'blind\');" style=\'cursor: pointer;\'>Show output from scheduler.</a>';
            echo "<div id='scheduleroutput' style='display: none;'>";
            echo implode('<br />', $error);
            echo '</div></div>';
        }
        if(empty($error)){
            echo $form->button('Stop Scheduler', array('onclick' => "window.location.href='".$html->url(array('action' => 'stop')). "'"));
            
            if(isset($nojobschecker) && $nojobschecker == true){
                
                echo "<div style='margin-top: 20px;' class='error'>There is no JobsChecker job present. <b>A JobChecker is needed to run tests</b>. ".$html->link('Add JobsChecker Job', array('controller' => 'qrtz_job_details', 'action' => 'insertCheckJob'))."</div>";
            }
        }else{
            echo $form->button('Start Scheduler', array('onclick' => "window.location.href='".$html->url(array('action' => 'start')). "'"));
        }


?>
<br /><br />
<?php if(empty($error)): ?>
    <h1>Scheduled jobs</h1>
    <p>
    <?php
    echo $paginator->counter(array(
    'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
    ));
    ?></p>
    <p>
    Please see <a href="http://www.quartz-scheduler.org/docs/tutorials/crontrigger.html" target="_blank">http://www.quartz-scheduler.org/docs/tutorials/crontrigger.html</a> for instructions on the cron trigger format used
    </p>
    <table cellpadding="0" cellspacing="0">
    <tr>
    	<th><?php echo $paginator->sort('JOB_NAME');?></th>
    	<th><?php echo $paginator->sort('JOB_DATA');?></th>
    	<th><?php echo $paginator->sort('CRON');?></th>
    	<th class="actions"><?php __('Actions');?></th>
    </tr>
    <?php
    $i = 0;
    foreach ($qrtzJobDetails as $qrtzJobDetail):
    	$class = null;
    	if ($i++ % 2 == 0) {
    		$class = ' class="altrow"';
    	}
    ?>
    	<tr<?php echo $class;?>>
    		<td>
    			<?php echo $qrtzJobDetail['QrtzJobDetail']['JOB_NAME']; ?>
    		</td>
    		<td>
    			<?php echo $qrtzJobDetail['QrtzJobDetail']['JOB_DATA']; ?>
    		</td>
    		<td>
    			<?php echo $qrtzJobDetail['QrtzJobDetail']['CRON']; ?>
    		</td>
    		<td class="actions">
    			<?php //echo $html->link(__('Edit', true), array('action' => 'edit', $qrtzJobDetail['QrtzJobDetail']['JOB_NAME'])); ?>
    			<?php echo $html->link(__('Delete', true), array('action' => 'delete', $qrtzJobDetail['QrtzJobDetail']['JOB_NAME']), null, sprintf(__('Are you sure you want to delete # %s?', true), $qrtzJobDetail['QrtzJobDetail']['JOB_NAME'])); ?>
    		</td>
    	</tr>
    <?php endforeach; ?>
    </table>
    </div>
    <div class="paging">
    	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
     | 	<?php echo $paginator->numbers();?>
    	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class' => 'disabled'));?>
    </div>
    <div class="actions">
    	<ul>
    		<li><?php echo $html->link(__('New Qrtz Job', true), array('action' => 'add')); ?></li>
    	</ul>
    </div>
<?php endif ?>