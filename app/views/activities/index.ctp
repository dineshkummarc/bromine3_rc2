<h1>Activity stream</h1>
<?php if (!empty($activities)){?>
<table cellpadding="0" cellspacing="0">
<?php
$i = 0;
foreach ($activities as $activity):
    //pr($activity);
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo "<a href='/users/view/".$activity['User']['id']."'>" .$activity['User']['name'] . '</a> ' . $activity['Activity']['content']; ?>
		</td>
        <td>
			<?php echo $time->timeAgoInWords($activity['Activity']['timestamp']); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
<?php } else{
    echo "No recent activities";
}
?>