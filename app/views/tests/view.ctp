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
                                                                                                                                                                                                                                                            
<div class="tests view">
<h1><?php echo $test['Test']['name']; ?></h1>
	<dl>
		<dt><?php __('Status'); ?></dt>
		<dd>
			<?php echo $test['Test']['status']; ?>
			&nbsp;
		</dd>
		<dt><?php __('Browser'); ?></dt>
		<dd>
			<?php echo $test['Browser']['name']; ?>
			&nbsp;
		</dd>
		<dt><?php __('Operating system'); ?></dt>
		<dd>
			<?php echo $test['Operatingsystem']['name']; ?>
			&nbsp;
		</dd>
		<dt><?php __('Timestamp'); ?></dt>
		<dd>
			<?php echo $test['Test']['timestamp']; ?>
			&nbsp;
		</dd>
		<?php
            if(strpos($test['Operatingsystem']['name'], 'SauceLabs') === 0 && !empty($test["Test"]["session_id"])){
                echo '<dt>Video</dt>';
                echo "<dd>";
                echo $html->link('Video', '/tests/sauce_video/'.$test["Test"]["session_id"].'/'.$sauce_username.'/'.$sauce_apikey, array('onclick'=>'return Popup.open({url:this.href});'), null, false);
                echo "</dd>";
            }                        
		?>
		<dt><?php __('Commands'); ?></dt>
		<dd>
			<?php if (!empty($test['Command'])):?>
			 
			
            	<table cellpadding = "0" cellspacing = "0" style="width: 100%;">
                	<tr>
                		<th><?php __('Command'); ?></th>
                		<th><?php __('Statement 1'); ?></th>
                		<th><?php __('Statement 2'); ?></th>
                        <th><?php __('Comment'); ?></th>
                	</tr>
            	<?php foreach ($test['Command'] as $command): ?>
            		<tr class='<?php echo $command['status'];?>'>
            			<td><?php echo $command['action'];?></td>
            			<td><?php echo $command['var1'];?></td>
            			<td><?php echo $command['var2'];?></td>
                                <td><?php echo $command['comment'];?></td>
            		</tr>
            	<?php endforeach; ?>
            	</table>
            <?php endif; ?>
        </dd>
        
		
		<?php
            $filename = LOGS."testruns" . DS .$test['Test']['id'].'.log';
            if(file_exists($filename)){
                $log = file_get_contents($filename);
                if(!empty($log)){
                    echo "<dt>Notice</dt>";
                    echo "<dd><div class='notice'><p class='prelike'>Notice: $log</p></div></dd>";
                }
            }else{
                    echo "<dt>Notice</dt>";
                    echo "<dd><div class='notice'><p class='prelike'>Notice: $filename does not exist</p></div></dd>";    
            }
        ?>
	</dl>
</div>