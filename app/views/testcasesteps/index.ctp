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
<div class="testcasesteps index">
<h2><?php __('Testcasesteps');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('id');?></th>
	<th><?php echo $paginator->sort('orderby');?></th>
	<th><?php echo $paginator->sort('action');?></th>
	<th><?php echo $paginator->sort('reaction');?></th>
	<th><?php echo $paginator->sort('testcase_id');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($testcasesteps as $testcasestep):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $testcasestep['Testcasestep']['id']; ?>
		</td>
		<td>
			<?php echo $testcasestep['Testcasestep']['orderby']; ?>
		</td>
		<td>
			<?php echo $testcasestep['Testcasestep']['action']; ?>
		</td>
		<td>
			<?php echo $testcasestep['Testcasestep']['reaction']; ?>
		</td>
		<td>
			<?php echo $html->aclLink($testcasestep['Testcase']['name'], array('controller'=> 'testcases', 'action'=>'view', $testcasestep['Testcase']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $html->aclLink(__('View', true), array('action'=>'view', $testcasestep['Testcasestep']['id'])); ?>
			<?php echo $html->aclLink(__('Edit', true), array('action'=>'edit', $testcasestep['Testcasestep']['id'])); ?>
			<?php echo $html->aclLink(__('Delete', true), array('action'=>'delete', $testcasestep['Testcasestep']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $testcasestep['Testcasestep']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>
<div class="paging">
	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->aclLink(__('New Testcasestep', true), array('action'=>'add')); ?></li>
		<li><?php echo $html->aclLink(__('List Testcases', true), array('controller'=> 'testcases', 'action'=>'index')); ?> </li>
		<li><?php echo $html->aclLink(__('New Testcase', true), array('controller'=> 'testcases', 'action'=>'add')); ?> </li>
	</ul>
</div>
