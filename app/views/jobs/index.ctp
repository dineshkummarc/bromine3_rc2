<div class="jobs index">
<h1>Jobs</h1>
<?php
//echo $html->image("tango/32x32/actions/view-refresh.png", array('title'=>'Click to force jobs from que'));
if(!empty($jobs)){            
    echo $ajax->link('Force test run', '/jobs/check', 
                     array('update' => 'output')
                    );
}
?>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Showing %current% jobs out of %count% total', true)
));
?></p>
<table cellpadding="0" cellspacing="0">

<?php
$i = 0;
$old_suite_id = 0;
foreach ($jobs as $job):
//pr($job);
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>  
    <?php 
    if ($job['Job']['suite_id'] != $old_suite_id){
        $old_suite_id = $job['Job']['suite_id'];   
    ?>
    <tr>
        <th colspan='4'>Suite <?php echo $old_suite_id;?> started by <?php echo $job['Suite']['User']['name'];?></th>
    	<th class="actions" colspan="2"><?php __('Actions');?></th>
    </tr>
    <tr>
    	<th>Testcase</th>
    	<th>OS</th>
    	<th>Browser</th>
    	<th>Added</th>
        <th><?php echo $html->link('View', array('controller' => 'runrctests','action' => 'suiteAjaxView', $job['Job']['suite_id']),array('target' => '_blank')); ?></th>
        <th><?php echo $ajax->link('Delete', array('action' => 'deleteSuite', $job['Job']['suite_id']), null, sprintf(__('Are you sure you want to delete suite %s?', true), $job['Job']['suite_id'])); ?></th>
    </tr>
    <?php }?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $job['Testcase']['name']; ?>
		</td>
		<td>
			<?php echo $job['Operatingsystem']['name']; ?>
		</td>
		<td>
			<?php echo $job['Browser']['name']; ?>
		</td>
		<td>
			<?php echo $time->timeAgoInWords($job['Job']['added']); ?>
		</td>
		
		<td class="actions" colspan="2">
			<?php echo $ajax->link(__('Delete job', true), array('action' => 'delete', $job['Job']['id']), null, sprintf(__('Are you sure you want to delete job %s?', true), $job['Job']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>