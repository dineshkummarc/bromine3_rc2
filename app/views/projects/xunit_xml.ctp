<?php

/*


*/
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



echo $xml->header();
echo '<testsuite name="Bromine suite'.date("d-m-Y").'" tests="'.count($suite['Test']).'" errors="'.$errors.'" failures="'.$failures.'" skip="0">';

foreach ($suite['Test'] as $test) {
    echo '<testcase name="'.$test['name']. ' @ '. $test['Browser']['name']. ' on '.$test['Operatingsystem']['name']. '" result="'.$test['status'].'">';
    if ($test['status'] != 'passed'){
        echo '<error type="failed">';
            foreach ($test['Command'] as $command) {
                echo '('.$command['status'].') '.$command['action'] . '(' . $command['var1'] . ', ' . $command['var2'] . ')'. "\n";	
            }
        echo '</error>';    
    }
    echo '</testcase>';
}
echo '</testsuite>';
//pr($suite);
?>