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
<div class="users view">
<h1><?php  __('User');?></h1>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
	   <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Username'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $user['User']['name']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('First name'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $user['User']['firstname']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Last name'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $user['User']['lastname']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Group'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $user['Group']['name']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Email'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $user['User']['email']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
	    <li><?php echo $html->link(__('List All Users', true), array('action'=>'index'));?></li>
		<li><?php echo $html->aclLink(__('Edit User', true), array('action'=>'edit', $user['User']['id'])); ?> </li>
		<li><?php echo $html->aclLink(__('Delete User', true), array('action'=>'delete', $user['User']['id']), null, sprintf(__('Are you sure you want to delete %s?', true), $user['User']['name'])); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php __('Projects');?></h3>
	<?php if (!empty($user['Project'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Name'); ?></th>
		<th><?php __('Description'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($user['Project'] as $project):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $project['name'];?></td>
			<td><?php echo $project['description'];?></td>
			<td class="actions">
				<?php echo $html->aclLink(__('View', true), array('controller'=> 'projects', 'action'=>'view', $project['id'])); ?> | 
				<?php echo $html->aclLink(__('Edit', true), array('controller'=> 'projects', 'action'=>'edit', $project['id'])); ?> | 
				<?php echo $html->aclLink(__('Delete', true), array('controller'=> 'projects', 'action'=>'delete', $project['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $project['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>
</div>