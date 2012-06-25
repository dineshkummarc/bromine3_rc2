<html>
    <head>
        <style type='text/css'>
            body
            { font-family: verdana, sans-serif;
              padding: 0px;
              margin: 0px;
              font-size: 10px;
            }
            
            td.passed, tr.passed td{
            background: #AAFFAA !important;
            
            }
            td.failed, tr.failed td{
                background: #FF7B7B !important;
            }
            
        </style>
    </head>
<body>
<h1><?php echo $project_name;?></h1>
<h2><?php echo $site_name;?></h2>
<table>
  <tr>
    <th>Time</th>        
    <th>TC</th>               
    <th>OS</th>        
    <th>Browser</th>
    <th>Status</th>
    <th>Link</th>
   
  </tr>
<?php
$data = $data[0];
foreach ($data['Test'] as $test) {
    $link = "http://$servername:$port/testlabs#/Tests/view/".$test['id']."/user:".$username."/password:".$user_password."/project:".$project_id;
    echo "              
        <tr class='".$test['status']."'>
            <td>".$test['timestamp']."</td>
            <td>".$test['Testcase']['name']."</td>
            <td>".$test['Operatingsystem']['name']."</td>
            <td>".$test['Browser']['name']."</td>
            <td>".$test['status']."</td>
            <td><a href='$link'>Link</a></td>
        </tr>";     
}

?>

</table>
<br /><br />
<p>----------------------------------------------------------------------------------------------------------------------------------------------------------------------</p>
<p>This email was sendt automatic from Bromine, if you don't want to recieve any more mails contact the administrator.</p>
<div id="footer">
        Copyright © 2007-2010 Bromine Team
        |
        <a href="http://bromine.seleniumhq.org">Bromine</a>
        | 
        <a href="http://forum.brominefoundation.org">Bromine Forum</a>
</body>
</html>