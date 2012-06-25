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
<?php
    echo "<h2>Setup your email SMTP:</h2>";
    
    echo "<p><a href='http://mail.google.com/support/bin/answer.py?hl=en&answer=13287'>How to use gmail click here</a></p>";

    echo $form->create('Config',array('action'=>'email'));
    echo "Enable email service:";
    echo $form->checkbox('email_enabled', array('label' => 'Enable email server:'));
    
    echo "<br /><br>";	
    echo $form->input('email_host', array('label' => 'Email host (eg. ssl://smtp.gmail.com)', 'value' => $email_host));
    echo "<br>";
    echo $form->input('email_port', array('label' => 'Email port (eg. 465)', 'value' => $email_port));
    echo "<br>";
    echo $form->input('email_username', array('label' => 'Email username', 'value' => $email_username));
    echo "<br>";
    echo '<div class="input text"><label for="ConfigEmailPassword">Email password</label>';
    echo $form->password('email_password', array('label' => 'Email password', 'value' => $email_password));
    echo "</div>";
    echo "<br>";
    echo $form->end('Submit');
    echo "<br />";
    echo "<h2>Test the email feature</h2>";
    
    if (!empty($status)){
        echo "<div class='notice'>$status</div>";
    }
    echo '<INPUT TYPE="BUTTON" VALUE="Test email" ONCLICK="window.location.href=\'/configs/email/sendemail\'" />';
?>
