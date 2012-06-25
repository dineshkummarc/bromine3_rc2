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
<div class='test index'>
<h1>Latests test runs</h1>
<table>
	<tr>
	   <th>Time stamp</th>
	   <th>Testcase name</th>
	   <th>Requirement</th>
	   <th>Site</th>
	   <th>OS</th>
	   <th>Browser</th>
	   <th>Results</th>
	</tr>
	<?php
	//pr($testcases);
        foreach($testcases as $testcase){
            $req_name = ""; 
            $i = 0;
            
            foreach ($testcase['Testcase']['Requirement'] as $req){
                $i++;
                $req_name .=  $req['name'];
                if ($i != count($testcase['Testcase']['Requirement'])){
                    $req_name .= ", ";
                }
                
            }
            $test_id = 'test_'.$testcase['Test']['id'];
            
            $status = $testcase['Test']['status'];
            if ($status == ''){$status = 'notdone';}
            echo "
            <tr class='$status'>
                <td>
                ".$time->timeAgoInWords($testcase['Test']['timestamp'])."
                </td>
                <td>".
                $testcase['Testcase']['name']
                ."</td>
                <td>
                ".$req_name."
                </td>
                <td>
                ".$testcase['Suite']['Site']['name']."
                </td>
                <td>
                ".$testcase['Operatingsystem']['name']."
                </td>
                <td>
                ".$testcase['Browser']['name']."
                </td>

                    <td>";
                    // use this picture: '/img/tango/32x32/categories/applications-other.png'
                    echo $html->link($html->image('tango/32x32/categories/applications-other.png'),array('controller'=>'testlabs#/Tests', 'action'=>'view', $testcase['Test']['id'], 'plugin'=>null),null,null,false);
                    //echo $html->link($html->image('tango/32x32/categories/applications-other.png'),'testlabs#/Tests/view/'.$testcase['Test']['id'],'plugin'=>null,null,false);
                    //echo "<a href='testlabs#/Tests/view/".$testcase['Test']['id']."'>".$html->image('/img/tango/32x32/categories/applications-other.png')."</a>";
                echo "</td>         
                
            </tr>
            ";
            
        }
        //   <a href='/jira/jira/addToJira/".$testcase['Test']['id']."'>Add</a>
                
    ?>
</table>
</div>