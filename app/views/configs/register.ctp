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
<div class="users form">
<p>
    Registering will help us understand our users. Moreover it will let us have an idea of how many are using Bromine, which will encourage us to continue our efforts
</p>      
<?php 

if (!isset($registered)){
    if(isset($regError)){
        echo '<div class="error">'.$regError['respons'].'<br />'.$regError['content'].'<a href="'.$regError['url'].'">'.$regError['url'].'</a></div>';
    }
    echo $form->create('Config', array('action' => 'register'));?>
	<fieldset>
 		<legend><?php __('Register Bromine');?></legend>
	<?php
        echo $form->input('name', array('label' => 'Type your name:'));
        if(isset($name_error)){echo "<p class='error'>$name_error</p>";}
        echo "<br>";
		echo $form->input('email', array('label' => 'Email:'));
		if(isset($email_error)){echo "<p class='error'>$email_error</p>";}
		echo "<br>";
        echo $form->input('Area', array('label' => 'Area of business'));
		echo "<br>";
		echo $form->input('Employee', array('label' => 'No. of employees at your company'));
        echo "<br>";
        echo $form->input('Found', array('label' => 'How did you come to know about Bromine?'));
		echo "<br>";
        echo $form->input('Users', array('label' => 'How many are (or will be) using Bromine at your company?'));
		echo "<br>";
        echo $form->input('usage',array('label' => 'Any other comments? eg. How are you using Bromine? How can we improve it? What should we change?', 'type' => 'testarea'));
	    echo $form->hidden('version',array('value' => $version));
	    echo $form->hidden('revision',array('value' => ''));  
    ?>
	</fieldset>
<?php echo $form->end('Save registration');
} else{
    echo "<h2>$registered<h2>";
}
?>
</div>
