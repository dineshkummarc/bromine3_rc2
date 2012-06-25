<div class="suites index">
<h2><?php __('Suites');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('id');?></th>
	<th>Status</th>
	<th>Error ratio</th>
	<th>Test passed</th>
	<th>Test failed</th>
	<th>Site</th>
	<th>User</th>
	
    <th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
//pr($suites);
foreach ($suites as $suite):
	$class = null;
    $testcase_count = count($suite['Test']);
    $testcase_passed_count = 0;
    $testcase_failed_count = 0;
    $testcase_running_count = 0;
    $testcase_remaining_count = count($suite['Job']);
    $total = 0;
    $overall_status = 'pending...';
    foreach ($suite['Test'] as $test) {
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
    
    if ($testcase_failed_count + $testcase_passed_count != 0){
        $error_ratio = $testcase_failed_count / ($testcase_failed_count + $testcase_passed_count);
        $error_ratio = round(($error_ratio * 100),2);
    }else{
        $error_ratio = 0;
    }

    if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>

	<tr<?php echo $class;?>>
        <td><?php echo $suite['Suite']['id'];?></td>
        <?php   if($overall_status != 'pending...' && $overall_status != 'running...' ){
                                echo "<td style='text-align: center;'>". $html->image($overall_status.'.png',array('height' => '16px')). "</td>";
                            }else{
                                echo "<td>$overall_status</td>";
                            } 
                            ?>

		<td><?php echo $error_ratio;?>%</td>
		<td><?php echo $testcase_passed_count;?></td>
		<td><?php echo $testcase_failed_count;?></td>
        <td>
			<?php echo $suite['Site']['name']; ?>
		</td>
		<td>
			<?php echo $suite['User']['name']; ?>
		</td>
		<td class="actions">
			<?php echo $html->link('View', array('action' => 'view', $suite['Suite']['id']),array('target' => '_blank')); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>
<div class="paging">
	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class' => 'disabled'));?>
</div>