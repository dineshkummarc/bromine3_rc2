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
<div style='border: 1px solid black; width: 300px; margin-top: 10px;'>
<strong>Suite overview</strong>
<table>
<?php 

$testcase_count = count($Suite['Test']);
$testcase_passed_count = 0;
$testcase_failed_count = 0;
$testcase_running_count = 0;
$testcase_remaining_count = count($Suite['Job']);
$total = 0;
$overall_status = 'pending...';
foreach ($Suite['Test'] as $test) {
    if ($test['status'] == 'passed'){
        $testcase_passed_count++;    
    }elseif($test['status'] == 'failed'){
        $testcase_failed_count++;    
    }elseif($test['status'] == 'running'){
        $testcase_running_count++; 
    }    
    //pr($test);	
}

// Total number of testcases used for calulating %
$testcase_total_count = $testcase_running_count + $testcase_remaining_count + $testcase_passed_count + $testcase_failed_count;

// If this is true all tests are done running, so set the status to passed or failed. else set it to running
if ($testcase_running_count == 0 && $testcase_remaining_count == 0 && ($testcase_passed_count != 0 || $testcase_failed_count != 0)){
    $testcase_failed_count > 0 ? $overall_status = 'failed' : $overall_status = 'passed';
}elseif($testcase_running_count == 0){
    $overall_status = 'pending...';
}
else{
    $overall_status = 'running...';
}
//check if no tests are starte
if ($testcase_total_count != 0){
    $total = ($testcase_passed_count + $testcase_failed_count) / 
             $testcase_total_count;
}else{
    $total = 0;
}

if ($testcase_failed_count + $testcase_passed_count != 0){
    $error_ratio = $testcase_failed_count / ($testcase_failed_count + $testcase_passed_count);
    $error_ratio = round(($error_ratio * 100),2);
}else{
    $error_ratio = 0;
}

//pr($Suite);
?>
<tr class='<?php if (!empty($overall_status))echo $overall_status; ?>'>
    <td>Status:</td><?php   if($overall_status != 'pending...' && $overall_status != 'running...' ){
                                echo "<td><b>Complete, result: $overall_status</b></td>";
                            }else{
                                echo "<td><b>$overall_status</b></td>";
                            } 
                            ?>

</tr>
<tr>
    <td>Progress:</td><td><?php $progress = round(($total * 100),2); echo "<b>". $progress ."%</b>";?></td>
</tr>
<tr class='<?php if ($error_ratio == 0){echo 'passed';}else{echo 'failed';}?>'>
    <td>Error ratio:</td><td><?php echo "<b>". $error_ratio ."%</b>";?></td>
</tr>
<tr>
    <td>Project:</td><td><?php echo "<b>".$Suite['Project']['name']."</b>";?></td>
</tr>
<tr>
    <td>Site:</td><td><?php echo "<b>".$Suite['Site']['name']."</b>";?></td>
</tr>
<tr>
    <td>User:</td><td><?php echo "<b>".$Suite['User']['name']."</b>";?></td>
</tr>
<tr class='passed'>
    <td>Testcases passed:</td><td><?php echo "<b>".$testcase_passed_count."</b>";?></td>
</tr>
<tr class='failed'>
    <td>Testcases failed:</td><td><?php echo "<b>".$testcase_failed_count."</b>";?></td>
</tr>
<tr class='running'>
    <td>Testcases running:</td><td><?php echo "<b>".$testcase_running_count."</b>";?></td>
</tr>
<tr>
    <td>Jobs remaining:</td><td><?php echo "<b>".$testcase_remaining_count."</b>";?></td>
</tr>
</table>
</div><br><br> 
<?php
    //pr($Suite);
    if(isset($Suite)){
        foreach($Suite['Test'] as $test){
            echo "<div style='border: 1px solid black; margin-top: 10px;'>";
            echo "<strong>".$test['name']."</strong> in ".$test['Browser']['name'].' on '.$test['Operatingsystem']['name'];
            echo "<table>";
            foreach($test['Command'] as $command){
                echo "<tr class='".$command['status']."'>";
                echo "<td>".$command['action']."</td><td>".$command['var1']."</td><td>".$command['var2']."</td><td>".$command['comment']."</td>";
                echo "</tr>";
            }
            echo "</table>";
            $filename = LOGS."testruns" . DS . $test['id'].'.log';
            $log = @file_get_contents($filename);
            if(!empty($log)){
                echo "<pre><p class='notice'>Notice: $log</p></pre>";
            }
            echo "</div><br><br>";
        }
    }
    
?>
