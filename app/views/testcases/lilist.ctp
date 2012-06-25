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
<?php
    foreach($testcases as $testcase){
        $id = $testcase['Testcase']['id'];
        echo "<div id='$id' style='clear: both;' class='tc'>";
        echo "<span class='spacer'></span>";
       
        $fullname = $testcase['Testcase']['name'];
        $name = (strlen($fullname)>20 ? substr($fullname,0,20).'...' : $fullname);
        echo $ajax->link(
                $name, 
                array( 'controller' => 'testcases', 'action' => 'view', $testcase['Testcase']['id']), 
                array( 'update' => 'Main', 'title'=>$fullname, 'condition'=>"$('$id').hasClassName('been_dragged')==false")
            );
        echo "</div>";
        ?>
            <script type='text/javascript'>
                new Draggable("<?php echo $id ?>",{ //make it dragable
                        scroll: window,
                        ghosting: true,
                        revert: true,
                        onEnd: function(draggable, event) {
                            $(draggable.element).addClassName('been_dragged');
                        }
                    });
            </script>
        <?php
        
    }
?>
