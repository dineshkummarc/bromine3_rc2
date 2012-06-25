<?php
$failures = 0;
$errors = 0;
foreach ($suite['Test'] as $test) {
    $error = false;
    if ($test['status'] != 'passed'){
        foreach ($test['Command'] as $command) {
            if (strpos($command['var2'] , '| ERROR:') !== false){
                $error = true;   
            }	
        }
        if ($error == true){
            $errors++; 
        }else{
            $failures++;
        }
    }	
}



//echo $xml->header();
echo "<h3>Bromine test report</h3>";
echo 'Date:'.date("d-m-Y") . '<br />';
echo 'No. of tests: '.count($suite['Test']) . '<br />';
echo 'No. of passed:' . (count($suite['Test']) - ($failures + $errors)) . '<br />';
echo 'No. of errors: '.$errors . '<br />';
echo 'No. of failures: '.$failures . '<br />';

foreach ($suite['Test'] as $test) {
    echo "<br />";
    echo '<b>Testcase:</b> ' .$test['name'] . ' @ ' . $test['Browser']['name']. ' on '.$test['Operatingsystem']['name'].'<br />';
    
    
    if ($test['status'] != 'passed'){
        echo "Status:<span style='color:red;'>" . $test['status']. "</span><br />";
        echo "<b>Error:</b><br />";
        echo 'Commands:'."<br />";
            foreach ($test['Command'] as $command) {
                
                $cmd = '('.$command['status'].') '.$command['action'] . '(' . $command['var1'] . ', ' . $command['var2'] . ')'.'<br />';	
                if ($command['status'] == 'failed'){
                    echo "<span style='color: red;'>" . $cmd . "</span>";
                }elseif($command['status'] == 'passed'){
                    echo "<span style='color: green;'>" . $cmd . "</span>";
                }else{
                    echo "<span style='color: grey;'>" . $cmd . "</span>"; 
                }
            }   
        echo "</p>";
    }else{
        echo "Status:<span style='color:green;'>" . $test['status']. "</span><br />";
    }
}
?>