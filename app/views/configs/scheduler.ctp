<h1>Bromine scheduler</h1>
<?php

if(empty($error)){
  echo "<div class='success'>The scheduler is running.</div>";
} else{
  echo "<div class='warning'>The scheduler is not running.<br />";
  echo '<a onclick="Effect.toggle(\'scheduleroutput\',\'blind\');" style=\'cursor: pointer;\'>Show output from scheduler.</a>';
  echo "<div id='scheduleroutput' style='display: none;'>";
  foreach($error as $s){
    echo $s.'<br />';
  }
  echo '</div></div>';
}
echo '<br />';
if(empty($error)){
  echo '<INPUT TYPE="BUTTON" VALUE="Stop" ONCLICK="window.location.href=\'/configs/scheduler/stop\'" />';
}else{
  echo '<INPUT TYPE="BUTTON" VALUE="Start" ONCLICK="window.location.href=\'/configs/scheduler/start\'" />';
}

?>