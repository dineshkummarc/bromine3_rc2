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
<div class="plugins index">
    <h1><?php __('Plugins');?></h1>
    <h3>Installed</h3>
    <table cellpadding="0" cellspacing="0">
    <tr>
    	<th><?php echo $paginator->sort('name');?></th>
    	<th>Description</th>
    	<th>Author</th>
    	<th>email</th>
    	<th>website</th>
    	<th><?php echo $paginator->sort('activated');?></th>
    	<th class="actions"><?php __('Actions');?></th>
    </tr>
    <?php
    $i = 0;
    foreach ($plugins as $plugin):
    	$class = null;
    	if ($i++ % 2 == 0) {
    		$class = ' class="altrow"';
    	}
    ?>
    	<tr<?php echo $class;?>>
    		<td>
    			<?php echo $plugin['Plugin']['name']; ?>
    		</td>
    		<td>
    			<?php echo $plugin['Plugin']['description']; ?>
    		</td>
    		<td>
    			<?php echo $plugin['Plugin']['author']; ?>
    		</td>
    		<td>
    			<?php echo $plugin['Plugin']['email']; ?>
    		</td>
    		<td>
    			<?php echo $plugin['Plugin']['website']; ?>
    		</td>
    		<td>
    			<?php
                    $activated = $plugin['Plugin']['activated'];
                    echo $activated ? "Yes" : "No"; ?>
    		</td>
    		<td class="actions">
    			<?php echo $html->link($activated ? 'Deactivate' : 'Activate', array('action' => $activated ? 'deactivate' : 'activate', $plugin['Plugin']['id'])); ?>
    			<?php echo $html->link('Uninstall', array('action' => 'uninstall', $plugin['Plugin']['id']), null, 'Are you sure you want to completely remove the '.$plugin['Plugin']['name'].' plugin?'); ?>
    		</td>
    	</tr>
    <?php endforeach; ?>
    </table>
    
    <div class="paging">
    	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
     | 	<?php echo $paginator->numbers();?>
    	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class' => 'disabled'));?>
    </div>
    <?php if(!empty($notInstalled)): ?>
    <br />
    <h3>Not installed</h3>
    
    <table cellpadding="0" cellspacing="0">
    <tr>
    	<th><?php echo $paginator->sort('name');?></th>
    	<th>Description</th>
    	<th>Author</th>
    	<th>email</th>
    	<th>website</th>
    	<th class="actions"><?php __('Actions');?></th>
    </tr>
    <?php
    $i = 0;
    foreach ($notInstalled as $plugin):
    	$class = null;
    	if ($i++ % 2 == 0) {
    		$class = ' class="altrow"';
    	}
    ?>
    	<tr<?php echo $class;?>>
    		<td>
    			<?php echo $plugin['Plugin']['name']; ?>
    		</td>
    		<td>
    			<?php echo $plugin['Plugin']['description']; ?>
    		</td>
    		<td>
    			<?php echo $plugin['Plugin']['author']; ?>
    		</td>
    		<td>
    			<?php echo $plugin['Plugin']['email']; ?>
    		</td>
    		<td>
    			<?php echo $plugin['Plugin']['website']; ?>
    		</td>
    		<td class="actions">
    			<?php echo $html->link('Install', array('controller' => 'plugins' ,'action' => 'install', $plugin['Plugin']['name'])); ?>
    		</td>
    	</tr>
    <?php endforeach; ?>
    </table>
    <?php endif ?>
    <br />
    <h3>Available online</h3>
    
    <?php 
    if(empty($available)){
        echo "<div class='warning'>Warning: Cannot connect to http://forum.brominefoundation.org/feed.php?f=9 to get a list of available plugins</div>";
    }elseif(!empty($available['Feed']['Entry'])){
        echo '<table cellpadding="0" cellspacing="0">';
        echo "<tr>";
    	echo "<th>Name</th>";
    	echo "<th>Description</th>";
    	echo "<th>Published</th>";
        echo "</tr>";
        if(isset($available['Feed']['Entry']['Author'])){
            $temp = $available['Feed']['Entry'];
            $available = array();
            $available['Feed']['Entry'][0] = $temp;
        } 

        foreach($available['Feed']['Entry'] as $item){
            $content = strip_tags($item['content']['value']);
            $content = substr($content, 0, strpos($content, 'Statistics: Posted by'));
            echo "<tr>";
            echo "<td>".$html->link(str_replace('Plugins ', '', $item['title']['value']), $item['Link']['href'],array('target'=>'_blank'))."</td>";
            echo "<td>".$content."</td>";
            echo "<td>".$time->timeAgoInWords($item['updated'])."</td>";
            echo "</tr>";
        }
        echo "</table>";
    }else{
        echo "There is no plugins available online";
    }
        
    ?>
    
    
</div>
