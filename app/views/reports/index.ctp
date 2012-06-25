<div class="reports index">
<h2><?php __('Reports');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('id');?></th>
	<th><?php echo $paginator->sort('name');?></th>
	<th><?php echo $paginator->sort('user_id');?></th>
	<th><?php echo $paginator->sort('testcase_in_summary');?></th>
	<th><?php echo $paginator->sort('specify_requirements');?></th>
	<th><?php echo $paginator->sort('specify_testcases');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($reports as $report):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $report['Report']['id']; ?>
		</td>
		<td>
			<?php echo $report['Report']['name']; ?>
		</td>
		<td>
			<?php echo $html->link($report['User']['name'], array('controller' => 'users', 'action' => 'view', $report['User']['id'])); ?>
		</td>
		<td>
			<?php echo $report['Report']['testcase_in_summary']; ?>
		</td>
		<td>
			<?php echo $report['Report']['specify_requirements']; ?>
		</td>
		<td>
			<?php echo $report['Report']['specify_testcases']; ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('action' => 'view', $report['Report']['id'])); ?>
			<?php echo $html->link(__('Edit', true), array('action' => 'edit', $report['Report']['id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action' => 'delete', $report['Report']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $report['Report']['id'])); ?>
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
		<li><?php echo $html->link(__('New Report', true), array('action' => 'add')); ?></li>
		<li><?php echo $html->link(__('List Users', true), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $html->link(__('New User', true), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $html->link(__('List Projects', true), array('controller' => 'projects', 'action' => 'index')); ?> </li>
		<li><?php echo $html->link(__('New Project', true), array('controller' => 'projects', 'action' => 'add')); ?> </li>
	</ul>
</div>
