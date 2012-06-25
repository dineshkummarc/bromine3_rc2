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
<?php if(!isset($uploaded)): ?>
<div class="testcases form">
	<?php if(isset($result)){
        echo "<p class='$class'>";
        foreach ($result as $r){
            echo $r;
        }
        echo "</p>";
    }?>
<?php echo $form->create('Requirement',array('action'=>"importFromCSV", 'enctype' => 'multipart/form-data')); ?>
	<h3>Usage of import function</h3>
	<p>
	To use this import:<br>
	0. <b>MAKE SURE YOUR FILE IS IN UTF8 ENDCODING ELSE IT WILL FAIL</b><br>
    1. create an CSV file (you could use Excel) containing name and description (there shouldn't be any headline in the CSV file), could look like this:
	<pre>
    My Requirement 1;My description 1
    My Requirement 2;My description 2
    My Requirement 3;My description 3
    My Requirement 4;My description 4
    My Requirement 5;My description 5
	</pre>
	2. Browse to the file<br>
	3. Choose type to import (Requirement/Testcase)<br>
	4. Choose seperator (in this case it's a ; but yours could be different)<br>
	5. Click import<br><br>
	</p>
    <fieldset>
 		<legend><?php __('Upload CSV file');?></legend>
	<?php
		echo $form->file('datafile');
		echo $form->input('type',array('options' => array('Requirement' => 'Requirement', 'Testcase' => 'Testcase'), 'selected' => 0));
        echo $form->input('seperator',array('options' => array(';' => 'Semicolon (;)',',' => 'Comma (,)', '.' => 'Period (.)', ' ' => 'Space ( )'), 'selected' => ';'));
		echo $form->end('Import');
	?>
	</fieldset>
</div>
<?php endif ?>