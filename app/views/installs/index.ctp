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
<h1>Welcome to the Bromine installer</h1>
<div class="install form">
<?php if(isset($nomodrewrite)): ?>
<h3 style='color: red;'>ERROR: mod_rewrite is not turned on</h3>
<p style='color: red;'>Which is why this installer looks hideous. Please turn on <a href='http://www.google.com/search?q=enable+mod_rewrite' target='_blank'>mod_rewrite</a> and set <a href='http://www.google.com/search?q=allowoverride+all' target='_blank'>'AllowOverride All'</a> for bromines webroot</p>  
<?php endif ?>
<?php echo $form->create('Install',array('action'=>'index'));?>
    <fieldset>
	   <legend>User agreement</legend>
	   <?php
           echo "
           <h3>Bromine end user agreement:</h3><br />
           Copyright 2007, 2008, 2009 Rasmus Berg Palm, Visti Kløft and Jeppe Poss Pedersen<br /> 
            <br />
            Bromine is free software: you can redistribute it and/or modify<br />
            it under the terms of the GNU General Public License as published by<br />
            the Free Software Foundation, either version 3 of the License, or<br />
            (at your option) any later version.<br />
            <br />            
            Bromine is distributed in the hope that it will be useful,<br />
            but WITHOUT ANY WARRANTY; without even the implied warranty of<br />
            MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the<br />
            GNU General Public License for more details.<br />
            <br />
            You should have received a copy of the GNU General Public License<br />
            along with Bromine.  If not, see <a href='http://www.gnu.org/licenses/' target='_blank'>http://www.gnu.org/licenses/</a>.<br />
           ";
           echo "<br />". $html->link('Click here to view the license', 'http://www.gnu.org/licenses/gpl-3.0.html',array('target' => '_blank'));
           echo " or read it locally here: " .$html->link('Click here to view the license', '/app/webroot/license.html',array('target' => '_blank'));
    	   echo "<br /><br />I agree to the license: ".$form->checkbox('userAgreement',array('value' => '0'));
	   ?>
	</fieldset>
	<fieldset>
 		<legend>State of the system</legend>
    	<table>
    	   <tr>
    	       <th>Condition</th>
    	       <th>Result</th>
    	   </tr>
    	   <tr>
        	   <td><b><?php echo TMP; ?> needs to be writeable</b></td>
        	   <?php echo "<td>".$html->image($tmp.'.png', array('style'=>'height: 28px;', 'title' => $tmp))." $tmp</td>";?>
    	   </tr>
    	   <tr>
               <td><b><?php echo CONFIGS; ?>database.php needs to be writeable </b></td>
        	   <?php echo "<td>".$html->image($config.'.png', array('style'=>'height: 28px;', 'title' => $config))." $config</td>";?>
    	   </tr>
    	   <tr>
               <td><b><?php echo APP.'..'.DS.'scheduler'.DS.'server.properties'; ?> needs to be writeable </b></td>
        	   <?php echo "<td>".$html->image($server_properties_writeable.'.png', array('style'=>'height: 28px;', 'title' => $server_properties_writeable))." $server_properties_writeable</td>";?>
    	   </tr>
    	   <tr>
               <td><b>magic_qoutes needs to be turned OFF</b></td>
        	   <?php echo "<td>".$html->image($magic_quotes.'.png', array('style'=>'height: 28px;', 'title' => $magic_quotes))." $magic_quotes</td>";?>
    	   </tr>
    	   <tr>
        	   <td><b>mod_rewrite needs to be turned ON</b></td>
        	   <?php echo "<td>".$html->image($mod_rewrite.'.png', array('style'=>'height: 28px;', 'title' => $mod_rewrite))." $mod_rewrite</td>";?>
    	   </tr>
    	   
    	</table>
	</fieldset>

</div>
<br />
<fieldset>
<legend>This installer takes the following steps</legend>
<ol>
    <li> Check if the system is ready for the application to be installed (see box above)</li>
    <li> Connect to the MySQL server </li>
    <li> Select the database specified, or create it if not found </li>
    <li> Create tables and populate them with data </li>
    <li> Create the file <tt><?php echo CONFIGS ?>database.php</tt> with the information below </li>
    <li> Redirects to an overview of the system status </li>
</ol>
</fieldset>
<br />
<div class="install form">
<?php echo $form->create('Install',array('action'=>'index'));?>
	
    <fieldset>
 		<legend>Server information</legend>
    	<?php
    		echo $form->input('server', array('value' => $server_name.':'.$server_port));
    		echo "Address where Bromine resides (must include port).";
    	?>
	</fieldset>
    <fieldset>
 		<legend><?php __('Database information');?></legend>
	<?php
		echo $form->input('host');
		echo $form->input('username');
		echo $form->input('password');
		echo $form->input('database');
	?>
	</fieldset>
	<fieldset>
 		<legend><?php __('Create admin user');?></legend>
	<?php
	    echo $form->input('admin_firstname');
	    echo $form->input('admin_lastname');
	    echo $form->input('admin_email');
		echo $form->input('admin_username');
		echo '<label for="Installadmin_password1">Password</label>';
		echo $form->password('admin_password1');
		echo '<label for="Installadmin_password2">Retype Password</label>';
		echo $form->password('admin_password2');
	?>
	</fieldset>
	<fieldset>
	   <legend>Options</legend>
	   <?php
           echo "<h3>Enable anonymous user statistics:</h3>";
           echo "<br />This is an example of the data you supply:";
           echo "<pre>
Array
(
    [brkey] => d528143e836fd5e84c33d409f9e13cc2
    [OS] => WINNT
    [requirements] => 11
    [testcases] => 9
    [projects] => 2
    [sites] => 4
    [users] => 1
    [nodes] => 4
    [sauce_enabled] => 0
    [plugins] => pizza
    [tests] => 474
    [testcasesteps] => 6
    [groups] => 2
    [myaros_myacos] => 4
    [combinations_requirements] => 40
    [version] => 790
)
           </pre>
           <h3>Help us make better software</h3>
           The data is 100% anonymous, but it helps us to see how users are using our system. 
           ";
           echo "<br /><br />Click here to accept:";
    	   echo $form->checkbox('enableGA',array('checked' => 'checked'));
	   ?>
	</fieldset>
	<?php
        if ($failed == false){ 
            echo $form->end('Install');
        }else{
            echo "<h2>Cannot install. Please fix the errors</h2>";
        }
        
        ?>
</div>
