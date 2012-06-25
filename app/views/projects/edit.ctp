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
<?php //pr($this->data) ?>
<div class="projects form">
<?php echo $form->create('Project');?>
	<fieldset>
 		<legend><?php __('Edit Project');?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('name');
		echo $form->input('description');
		
		echo "<label>Sites</label>";
		foreach($this->data['Site'] as $site){
            echo "<div class='input text' id='site".$site['id']."'><input name='data[Sites][".$site['id']."]' type='text' value='".$site['name']."' />";
            echo $html->link( 
                'Remove', 
                '#',
                array('onclick' => "
                    if(confirm('Are you sure you want to delete this site?')){
                    new Ajax.Request('/sites/delete/".$site['id']."'); 
                    $('site".$site['id']."').remove();}"
                )
            );
            echo "</div>";
        }
        echo "<div id='addsite'>";
		echo "</div>";
		echo $html->link('Add Site', '#', array('onclick'=>"
             new Ajax.Updater('addsite', '/sites/add', {
              insertion: Insertion.Bottom
            });
        "));
        
        echo $form->input('User',array('label'=>'Users'));
		
	?>
	</fieldset>
    <div class="cancel"><?php echo $html->link(__('Cancel', true), array('action'=>'index'));?></div>
<?php echo $form->end('Submit');?> 
</div>
