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
<style>

input[type="submit"] {
	margin: 12px 5px 8px 0px !important;
}

input {
	margin-bottom: 8px;
}

textarea {
	margin-bottom: 8px;
}

select {
	margin-bottom: 8px;
}

</style>

<style>

input[type="submit"] {
	margin: 12px 5px 8px 0px !important;
}

input {
	margin-bottom: 8px;
}

textarea {
	margin-bottom: 8px;
}

select {
	margin-bottom: 8px;
}

</style>

<div style='float: right;'>
<?php
    echo $ajax->link( 
            $html->image('tango/32x32/actions/go-previous.png').'<br />Back', 
            array( 'controller' => 'testcases', 'action' => 'view', $this->data['Testcase']['id']), 
            array( 'update' => 'Main', 'class'=>'testcases view', 'id' => 'cancel'), null, false);
    echo "<br />";
    echo "<br />";
    echo $html->link( 
            $html->image('tango/32x32/places/user-trash.png').'<br />Delete', 
            array( 'controller' => 'testcases', 'action' => 'delete', $this->data['Testcase']['id']), 
            array( 'class'=>'testcases delete', 'id' => 'delete'),
            'Are you sure you want to delete this testcase?', false
            );
?>
</div>
<div class="testcases form">
<?php echo $form->create('Testcase'); ?>
	<fieldset id="editTestcase">
 		<legend><?php __('Edit Testcase');?></legend>
	<?php
        echo $form->hidden('id');
		echo $form->input('name');
		echo $form->hidden('project_id',array('value' => $session->read('project_id')));
		echo $form->input('description');
		echo $form->input('user_id', array('label' => 'Owner'));
		echo "<div class='input file'>";
        echo "<label>Testscript</label>";
        if(isset($testscript)){
            echo $html->image('tango/32x32/mimetypes/application-x-executable.png');
            echo $html->link('View testscript',array('controller'=>'testcases', 'action'=>'viewscript', $this->data['Testcase']['id']),array('onclick'=>'return Popup.open({url:this.href});'));
    	}else{
    	   echo "No testscript uploaded";
		}
		echo "<br />";
		echo $html->link('Upload new testscript',array('controller'=>'testcases', 'action'=>'upload', $this->data['Testcase']['id']),array('onclick'=>'return Popup.open({url:this.href});'));
		echo "</div>";
		echo $ajax->submit("Submit", array("url" => array('controller'=>'testcases','action'=>'edit',$this->data['Testcase']['id']), "update" => "Main"));
		
	?>
	</fieldset>
</div>
<br />
<br />
<table id="testStepTable">
	<tr>
        <th style='width: 40%; padding: 5px;'>
            Action
		</th>
        <th style='width: 40%; padding: 5px;'>	
            Reaction
		</th>
		<th style='width: 10%;'>
            Drag
		</th>
		<th style='width: 10%;'>
            Delete
		</th>
	</tr>
</table>
<div id='sort'>
<?php foreach($testcasesteps as $testcasestep): ?>
    <?php $id = $testcasestep['TestcaseStep']['id']; ?>
	<div class='container' id='item_<?php echo $id ?>'>
		<table style="width: 100%;">
    		<tr style='min-height: 40px; vertical-align: top;'>
                <td style='width: 40%; border: 1px solid lightgrey; padding: 5px;'>
                        <div id="action<?php echo $id; ?>" style='min-height: 40px; cursor: text;'><?php echo nl2br($testcasestep['TestcaseStep']['action']); ?></div>
    			</td>
    			<?php
                    echo $ajax->editor( 
                        "action$id", //In place editor id
                        array(  //Url
                            'controller' => 'testcasesteps', 
                            'action' => 'edit',
                            $id,
                            'action'
                        ),
			array(
				'rows' => '6',
				'cols' => '30',
				'okText' => 'Submit'
			)
                    );
                ?>
    	        <td style='width: 40%; border: 1px solid lightgrey; padding: 5px;'>	
                    <div id="reaction<?php echo $id; ?>" style='min-height: 40px; cursor: text;'><?php echo nl2br($testcasestep['TestcaseStep']['reaction']); ?></div>
    			</td>
    			<?php
                    echo $ajax->editor( 
                        "reaction$id", //In place editor id
                        array(  //Url 
                            'controller' => 'testcasesteps', 
                            'action' => 'edit',
                            $id,
                            'reaction'
                        ),
			array(
				'rows' => '6',
				'cols' => '30',
				'okText' => 'Submit'
			)
                    );
                ?>
    			<td class='handle' style='width: 10%; height: 50px;background: url(/img/side.png); border: 1px solid lightgrey; cursor: url("/img/openhand.cur"), move;'>&nbsp;</td>
                <td style="width: 10%; text-align: center;">
                    <?php 
                        echo $ajax->link( 
                            $html->image('tango/32x32/places/user-trash.png'), 
                            array( 'controller' => 'Testcasesteps', 'action' => 'delete', $testcasestep['TestcaseStep']['id'], $this->data['Testcase']['id']), 
                            array( 'update' => 'Main'),
                            'Are you sure you want to delete this step?', false
                            );
                    ?>
                </td>
    		</tr>
        </table>
	</div>
<?php endforeach; ?>

</div>
<div id='add'>
</div>

<script type='text/javascript' defer>
Sortable.create("sort", {
    tag: 'div',
    only: 'container',
    handle: 'handle',
    onUpdate: function() {
        new Ajax.Request('/testcasesteps/reorder/'+Sortable.sequence("sort"));
        $('sort').highlight();
    }
});

// setting columns for name and description
$('TestcaseName').style.width = '' + Math.round(($('Main').offsetWidth / 4) * 3) + 'px'; 
$('TestcaseDescription').style.width = '' + Math.round(($('Main').offsetWidth / 4) * 3) + 'px'; 
$('testStepTable').style.width = '' + $('editTestcase').offsetWidth + 'px';
$('sort').style.width = '' + $('editTestcase').offsetWidth + 'px';


<?php
     echo $ajax->remoteFunction(
         array(
             'url' => array( 'controller' => 'Testcasesteps', 'action' => 'add', $this->data['Testcase']['id']),
             'update' => 'add'
         )
     );
?>
</script>