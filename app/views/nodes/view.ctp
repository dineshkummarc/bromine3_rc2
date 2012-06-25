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
<div class="nodes view">
<h1><?php  __('Node');?></h1>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Nodepath'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $node['Node']['nodepath']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Operatingsystem'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $html->link($node['Operatingsystem']['name'], array('controller'=> 'operatingsystems', 'action'=>'view', $node['Operatingsystem']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Limit'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $node['Node']['limit']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Description'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $node['Node']['description']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
	    <li><?php echo $html->link(__('List All Nodes', true), array('action'=>'index'));?></li>
		<li><?php echo $html->link(__('Edit Node', true), array('action'=>'edit', $node['Node']['id'])); ?> </li>
		<li><?php echo $html->link(__('Delete Node', true), array('action'=>'delete', $node['Node']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $node['Node']['id'])); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php __('Browsers');?></h3>
	<?php if (!empty($node['Browser'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Name'); ?></th>
		<th><?php __('Path'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($node['Browser'] as $browser):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $browser['name'];?></td>
			<td><?php echo $browser['path'];?></td>
			<td class="actions">
				<?php echo $html->link(__('View', true), array('controller'=> 'browsers', 'action'=>'view', $browser['id'])); ?> | 
				<?php echo $html->link(__('Edit', true), array('controller'=> 'browsers', 'action'=>'edit', $browser['id'])); ?> | 
				<?php echo $html->link(__('Delete', true), array('controller'=> 'browsers', 'action'=>'delete', $browser['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $browser['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>
</div>
