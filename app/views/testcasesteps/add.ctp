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
<div class="testcasesteps form">
<?php echo $form->create('Testcasestep');?>
<table>
    <tr style='height: 40px; vertical-align: top;'>
        <td style='width: 40%; border: 1px solid lightgrey; padding: 5px;'>
            <?php echo $form->input('action', array('label' => '', 'cols' => 28, 'rows' => 2)); ?>
        </td>
        <td style='width: 40%; border: 1px solid lightgrey; padding: 5px;'>
            <?php echo $form->input('reaction', array('label' => '', 'cols' => 28, 'rows' => 2)); ?>
        </td>
        <td class='handle' style='width: 80px;'>
        <?php
            //echo $form->input('orderby',array('value' => $orderby));
            echo $form->hidden('testcase_id',array('value' => $testcaseid));
            echo $ajax->submit("submit", array("url" => array('controller'=>'testcasesteps','action'=>'add',$testcaseid), "update" => "Main"));
        ?>
        </td>
    </tr>
</table>

</div>
