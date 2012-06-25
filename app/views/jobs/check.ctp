<?php if ($nojobs != true){ ?>
<div class="jobs index">
<h1>Jobs started</h1>
<table cellpadding="0" cellspacing="0">
    <tr>
    	<th>Testcase</th>
    	<th>OS</th>
    	<th>Browser</th>
    	<th>Started</th>
    	<th class="actions">Action</th>
    </tr>
<?php
    echo $output;
?>
</table>
</div>
<?php 
}else{
    echo $output;
    }

?>