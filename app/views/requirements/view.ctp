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

<div id='messages'>
    <?php $session->flash('auth'); ?>
    <?php $session->flash(); ?>
</div>

<div style='float: right;'>
    <?php echo 
        $ajax->link( 
            'Edit', 
            array( 'controller' => 'requirements', 'action' => 'edit', $requirement['Requirement']['id']), 
            array( 'update' => 'Main', 'class'=>'requirements view', 'id' => 'edit'));
    ?>
    &nbsp;&nbsp;&nbsp;
    <?php echo
        $ajax->link(
            'Add Testcase',
            array( 'controller' => 'testcases', 'action' => 'addNew', $requirement['Requirement']['id']),
            array( 'update' => 'Main', 'class'=>'requirements view', 'id' => 'add'));
    ?>
</div>

<div class="requirements view">
<h1><?php echo $requirement['Requirement']['name']; ?></h1>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Requirement owner'); ?></dt>
		<dd><?php echo $requirement['User']['firstname'] . ' ' . $requirement['User']['lastname'] . ' - ' .$requirement['User']['name'] . '(' . $requirement['User']['email'] . ')'; ?></dd>
    
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Description'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo nl2br($requirement['Requirement']['description']); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Combinations'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
    		<?php echo $table->createTable($browsers, $operatingsystems, $combinations, false, $requirement['Requirement']['id']) ?>
    		&nbsp;
		</dd>
	</dl>
</div>
<div id="log"></div>
