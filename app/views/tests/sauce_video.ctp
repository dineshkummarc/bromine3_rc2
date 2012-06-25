<?php
    echo "<html><body>";   
    echo '<script type="text/javascript" src="http://saucelabs.com/video-embed/'.$session_id.'.js?username='.$sauce_username.'&access_key='.$sauce_apikey.'"/>';
    echo "</body></html>";
        //<script src="http://saucelabs.com/video-embed/<job id>.js?username=<username>&access_key=<access key>"/>
?>