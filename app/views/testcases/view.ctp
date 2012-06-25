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
            array( 'controller' => 'testcases', 'action' => 'edit', $testcase['Testcase']['id']), 
            array( 'update' => 'Main', 'class'=>'testcases view', 'id' => 'edit'));
    ?>
    &nbsp;&nbsp;&nbsp;
    <?php echo
        $html->link(
            'Delete', 
            array( 'controller' => 'testcases', 'action' => 'delete', $testcase['Testcase']['id']),
            array( 'class'=>'testcases delete', 'id' => 'delete'),
            'Are you sure you want to delete this testcase?', false);
    ?>
    &nbsp;&nbsp;&nbsp;
    <?php echo
        $html->link(
            'Clone',
            array( 'controller' => 'testcases', 'action' => 'cloneThis', $testcase['Testcase']['id'])
            /*array(
                'id' => 'clonelink',
                'onclick'=>'
                    anchor = getAnchor();
                    if(anchor != false)
                    $("clonelink").href += "#"+anchor;
                    '
            )*/
        );
    ?>

    
</div>

<div id ='testcase' class="testcases view">
<h1><?php echo $testcase['Testcase']['name']; ?></h1>
	<dl>
		<dt><?php __('Testcase owner'); ?></dt>
		<dd><?php echo $testcase['User']['firstname'] . ' ' . $testcase['User']['lastname'] . ' - ' .$testcase['User']['name'] . '(' . $testcase['User']['email'] . ')'; ?></dd>
		
    <dt><?php __('Description'); ?></dt>
		<dd>
			<?php echo nl2br($testcase['Testcase']['description']); ?>
			&nbsp;
		</dd>
		<dt>Testscript</dt>
		<dd>
		<?php if(isset($testscript)): ?>
            <?php echo $html->image('tango/32x32/mimetypes/application-x-executable.png'); ?>
            <?php echo $html->link('View testscript',array('controller'=>'testcases', 'action'=>'viewscript', $testcase['Testcase']['id']),array('onclick'=>'return Popup.open({url:this.href});')); ?>
    	<?php else: 
    	   echo $html->image('tango/32x32/emblems/emblem-important.png') . " No script uploaded";
           //No testscript uploaded
		endif; ?>
		</dd>
        <dt>Steps:</dt>
        <dd>
            <?php if(!empty($testcasesteps)): ?>
                <table>
                	<tr>
                        <th style='width: 250px; padding: 5px;'>
                            Action
                		</th>
                        <th style='width: 250px; padding: 5px;'>	
                            Reaction
                		</th>
                	</tr>
                <?php foreach($testcasesteps as $testcasestep): ?>
            		<tr style='height: 40px; vertical-align: top;'>
                        <td style='width: 250px; border: 1px solid lightgrey; padding: 5px;'>
                            <?php echo nl2br($testcasestep['TestcaseStep']['action']); ?>
            			</td>
            	        <td style='width: 250px; border: 1px solid lightgrey; padding: 5px;'>	
                            <?php echo nl2br($testcasestep['TestcaseStep']['reaction']); ?>
            			</td>
            		</tr>
                <?php endforeach; ?>
                </table>
            <?php endif ?>
        </dd>
    </dl>
</div>




