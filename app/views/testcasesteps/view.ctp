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
<div class="testcasesteps view">
<h2><?php  __('Testcasestep');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $testcasestep['Testcasestep']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Orderby'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $testcasestep['Testcasestep']['orderby']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Action'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $testcasestep['Testcasestep']['action']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Reaction'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $testcasestep['Testcasestep']['reaction']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Testcase'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $html->aclLink($testcasestep['Testcase']['name'], array('controller'=> 'testcases', 'action'=>'view', $testcasestep['Testcase']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->aclLink(__('Edit Testcasestep', true), array('action'=>'edit', $testcasestep['Testcasestep']['id'])); ?> </li>
		<li><?php echo $html->aclLink(__('Delete Testcasestep', true), array('action'=>'delete', $testcasestep['Testcasestep']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $testcasestep['Testcasestep']['id'])); ?> </li>
		<li><?php echo $html->aclLink(__('List Testcasesteps', true), array('action'=>'index')); ?> </li>
		<li><?php echo $html->aclLink(__('New Testcasestep', true), array('action'=>'add')); ?> </li>
		<li><?php echo $html->aclLink(__('List Testcases', true), array('controller'=> 'testcases', 'action'=>'index')); ?> </li>
		<li><?php echo $html->aclLink(__('New Testcase', true), array('controller'=> 'testcases', 'action'=>'add')); ?> </li>
	</ul>
</div>
