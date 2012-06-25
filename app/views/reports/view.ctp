<?php
pr($projects);

foreach($projects as $project){
    echo "<h1>".$project['Project']['name']."</h1>";
    echo "<table>";
    foreach($project['Data'] as $requirement){
        echo "<tr>";
        echo "<td>";
        echo $requirement['Requirement']['name'];
        echo "</td>";
        foreach($requirement['Requirement']['status'] as $site => $status){
            echo "<td>";
            echo $status;
            echo "</td>";
        }
        echo "</tr>";
        
        foreach($requirement['Testcase'] as $testcase){
            echo "<tr>";
            echo "<td>";
            echo "--".$testcase['name'];
            echo "</td>";
            foreach($testcase['status'] as $site => $status){
                echo "<td>";
                echo $status;
                echo "</td>";
            }
            echo "</tr>";
        }
    }
     
            
    echo "</table>";
}



?>