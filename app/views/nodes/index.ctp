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
<div class="nodes index">
<?php
    echo "<h1>Sauce Labs OnDemand</h1>";
    echo $form->create('Config',array('action'=>'sauce'));

    echo "<p>Sauce Labs OnDemand is a service that allows you to run your tests against nodes in the cloud. You can read more and sign up at ".$html->link('http://saucelabs.com', 'https://saucelabs.com/signup?utm_source=bromine&utm_medium=br_application&utm_campaign=signup',array('target' => '_blank')).'</p>';
    echo "Enable Sauce Labs OnDemand integration:";
    $checked = '';    
    $sauce_enabled == 1 ? $checked = 'checked' : '';
    echo $form->checkbox('sauce_enabled', array('checked' => $checked));
    echo "<br /><br />";    
    if($sauce_enabled == 1){
        echo "<div> <b>You have enabled Sauce Labs On Demand integration.</b>
        <br /> A number of special Sauce Labs browser and operatingsystems, as based on this ".$html->link('list,', 'http://saucelabs.com/products/docs/sauce-ondemand/browsers?utm_source=bromine&utm_medium=br_application&utm_campaign=os_and_browser_list',array('target' => '_blank'))." have been added. 
        <a onclick='$(".'"browserformat"'.").toggle();' style='cursor: pointer;'>Your list is outdated, I want to add my own browsers/OS's</a>. <div id='browserformat' style='display: none;'>Ok. go to ".$html->link('browsers/add', array('controller' => 'browsers', 'action' => 'add'))." and ". $html->link('operatingsystems/add', array('controller' => 'operatingsystems', 'action' => 'add'))." to add them. The browsers and OS's both needs to start with the string 'SauceLabs ' (notice the space) followed by the browser/OS name. In the case of browsers, Sauce Labs needs both a browser name and a browser version. To do this Bromine splits the browser name on a hyphen into name and version. Path is redundant. The format is '[name]-[version]', eg. 'firefox-3.0.' will split into [name] = firefox and [version] = 3.0.</div>
        <br /> Two Sauce Labs nodes that are preconfigured to use these OS and browsers have been added. 
        <br /> You will need to setup your requirements to be run on the Sauce Labs OS/browser combinations under ".$html->link('Planning', array('controller' => 'requirements', 'action' => 'index'))."
        <br /> You will need to register an account with Sauce Labs and provide the details below.
        <br /> If you disable Sauce Labs OnDemand integration the added nodes, browsers and OS's will be removed.
        </div>";
        echo "<br />";
        echo $form->input('sauce_username', array('label' => 'Sauce Labs username:', 'value' => $sauce_username));
        echo "<br />";
    	echo $form->input('sauce_apikey', array('label' => 'Sauce Labs API Access Key:', 'value' => $sauce_apikey, 'size' => 40));
        echo "<a onclick='$(".'"advanced_sauce"'.").toggle();' style='cursor: pointer;'>Advanced settings</a>"; 
        echo "<div id='advanced_sauce'  style='display: none;'>";
        echo "The Sauce node IP is used internally to figure out which tests are being run against Sauce Labs. You would only need to change this in case Sauce Labs changes their IP for their OnDemand service. You will still need to change the Nodes IP's seperately as well.";
        echo $form->input('sauce_nodepath', array('label' => 'Sauce node IP:', 'value' => $sauce_nodepath));
        echo "</div>";
    }
    
    echo $form->end('Submit');
?><br><br>
<h1><?php __('Nodes');?></h1>
The nodes online/offline statuses are cached.
<?php
    echo $form->button('Clear cache', array('type'=>'button','onclick' => "window.location.href='/nodes/clearCache'"));
?>
<br />
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th> Online </th>
	<th><?php echo $paginator->sort('nodepath');?></th>
	<th><?php echo $paginator->sort('operatingsystem_id');?></th>
	<th><?php echo $paginator->sort('limit');?></th>
	<th><?php echo $paginator->sort('description');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
//pr($nodes);
foreach ($nodes as $node):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
        <td>
			<?php echo $html->image($node['Node']['status'],array('class'=>'node_indicator')); ?>
		</td>
		<td>
			<?php echo $node['Node']['nodepath']; ?>
		</td>
		<td>
			<?php echo $html->link($node['Operatingsystem']['name'], array('controller'=> 'operatingsystems', 'action'=>'view', $node['Operatingsystem']['id'])); ?>
		</td>
		<td>
			<?php echo $node['Node']['limit']; ?>
		</td>
		<td>
			<?php echo $node['Node']['description']; ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('action'=>'view', $node['Node']['id'])); ?> | 
			<?php echo $html->link(__('Edit', true), array('action'=>'edit', $node['Node']['id'])); ?> | 
			<?php echo $html->link(__('Delete', true), array('action'=>'delete', $node['Node']['id']), null, sprintf(__('Are you sure you want to delete %s?', true), $node['Node']['nodepath'])); ?>
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
		<li><?php echo $html->link(__('New Node', true), array('action'=>'add')); ?></li>
	</ul>
</div>
