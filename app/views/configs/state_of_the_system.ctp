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
<h1>State of the System</h1>
<div class="index">

<?php
echo "The results below are cached. ";
echo $form->button('Clear cache', array('type'=>'button','onclick' => "window.location.href='/configs/clearCache'"));
?>
<br />
<table class='br-state-of-the-system' cellpadding="0" cellspacing="0">
    <tr class='br-state-of-the-system-heading'>
        <th>Feature</th>
        <th>Expected</th>
        <th>Actual result</th>
        <th>Ignore this?</th>
    </tr>
<?php
$i = 0;
foreach ($states as $key=>$state) {
    
    if ( $state['status'] === 'ignored' ){
        $status = 'warning';
    } elseif ($state['status']) {
        $status = 'passed';
    } else {
        $status = 'failed';
    }

    $rowClass = ($i++ % 2 == 0) ? ' class="altrow"' : '';
    echo "<tr $rowClass>";
        
    //echo "<td class='br-config-name'>" .$html->image($img, array('style'=>'height: 28px;')). " $key</td>";
    echo "<td class='br-config-name br-statuses-$status'>$key</td>";
    echo "<td>".$state['expected']."</td>";
    echo "<td>".$state['result']."</td>"; 
    echo "<td>";
    
    echo $form->create(array('action' => 'ignore'));
    echo $form->hidden('key',array('value' => $key));
    if ($state['status'] === 'ignored'){
         echo $form->hidden('state',array('value' => 0));
         echo "<input type='submit' value='No' />";
    }else{
        echo $form->hidden('state',array('value' => 1));
        echo "<input type='submit' value='Yes' onclick='"."return confirm(".'"'."Are you sure you wish to ignore this? I hope you know what you are doing...".'"'.");' />";
    }
    
    echo $form->end(); 

    echo "</td>";
    echo "</tr>";
}
?>

</table>
<button onclick='$("debugvars").toggle();'>Show debug information</button>
<div id='debugvars' style='display: none;'>
<?php
$i = 0;
echo "<h1>PHP Server vars used for debugging:</h1>";
$servervars = array('argv','argc','GATEWAY_INTERFACE','SERVER_ADDR','SERVER_NAME','SERVER_SOFTWARE','SERVER_PROTOCOL','REQUEST_METHOD','REQUEST_TIME',
                    'QUERY_STRING','DOCUMENT_ROOT','HTTP_ACCEPT','HTTP_ACCEPT_CHARSET','HTTP_ACCEPT_ENCODING','HTTP_ACCEPT_ENCODING','HTTP_ACCEPT_LANGUAGE',
                    'HTTP_CONNECTION','HTTP_HOST','HTTP_REFERER','HTTP_USER_AGENT','HTTPS','REMOTE_ADDR','REMOTE_HOST','REMOTE_PORT','SCRIPT_FILENAME',
                    'SERVER_ADMIN','SERVER_PORT','SERVER_SIGNATURE','PATH_TRANSLATED','SCRIPT_NAME','REQUEST_URI','PHP_AUTH_DIGEST','PHP_AUTH_USER',
                    'PHP_AUTH_PW','AUTH_TYPE');
echo "<table>";
    echo "<tr class='br-state-of-the-system-heading'>";
        echo "<th>Value</th>";
        echo "<th>Server var</th>";
    echo "</tr>";
foreach ($servervars as $servervar){
    if (isset($_SERVER[$servervar])){
        $rowClass = ($i++ % 2 == 0) ? ' class="altrow"' : '';
        echo "<tr $rowClass>";
        if (is_array($_SERVER[$servervar])){
            echo "<td>" .print_r($_SERVER[$servervar],true) ."</td><td>$servervar</td></tr>";
        }else{
            echo "<td>" .$_SERVER[$servervar] ."</td><td>$servervar</td></tr>";
        }
    }
}
echo "</table>";
?>
</div>
</div>
