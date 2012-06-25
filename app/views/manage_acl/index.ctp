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
<div class="manageacl index">
    <h1><?php __('ACL manager');?></h1>
    <table>
        <tr>
            <th>Requester browser</th>
            <th>Requester permisssions</th>
        </tr>
        <tr style='vertical-align: top;'>
            <td id='aros' style='vertical-align: top;'>
                <script type="text/javascript">
                    <?php 
                        echo $ajax->remoteFunction( 
                        array( 
                            'url' => array( 'controller' => 'manageAcl', 'action' => 'listAros'), 
                            'update' => 'aros' 
                        ) 
                    ); ?>
                </script>
            </td>
            <td id='permissions' style='vertical-align: top;'>
            </td>
        </tr>
    </table>
</div>
