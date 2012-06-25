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
<?php echo $form->create('User', array('action' => 'assign')); ?>
<input type="hidden" name="data[Myaro][Myaro]" value="" />
<div class="users index">
<h2><?php __('Users');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>

<table cellpadding="0" cellspacing="0">
<tr>
    <th><?php echo __('Assign') ?></th>
	<th><?php echo $paginator->sort('name');?></th>
	<th><?php echo $paginator->sort('firstname');?></th>
	<th><?php echo $paginator->sort('lastname');?></th>
	<th><?php echo $paginator->sort('group_id');?></th>
</tr>
<?php
$i = 0;
foreach ($users as $user):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
        <td>
			<?php
                $id = $user['User']['id'];        
                $aroid = $user['Myaro']['id'];
                $checked = '';
                $disabled = '';

                if(in_array($id, $assigned_users)){
                    $checked = 'checked';
                }
                if(in_array($id, $admin_users)){
                    $disabled = 'disabled';
                }
                echo "<input type='checkbox' value='$aroid' name='data[Myaro][Myaro][]' $checked $disabled />";
            ?>
		</td>
        <td>
			<?php echo $user['User']['name']; ?>
		</td>
		<td>
			<?php echo $user['User']['firstname']; ?>
		</td>
		<td>
			<?php echo $user['User']['lastname']; ?>
		</td>

		<td>
			<?php echo $user['Group']['name']; ?>
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
<?php echo $form->end('Submit'); ?>
