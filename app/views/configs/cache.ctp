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

    echo $form->create('Config',array('action'=>'cache'));
    echo $form->hidden('clearCache',array('value'=>'1'));

    echo "<h1>Cache</h1>";
    echo "<p>To improve performance in Bromine we have enabled caching. The following are cached:</p>";
    echo "<ul>
        <li>Testcase and Requirement results</li>
        <li>State of the system</li>
        <li>Online nodes</li>
        <li>Selfcontact check</li>
    </ul>";
    
    echo "<b>But there is a downside:</b><br />";
    echo "<ul>
        <li>If one of you nodes go offline Bromine will still think the node is online. <b>Solution: Clear cache and try again</b></li>
        <li>If state of the system reports an error (eg. The scheduler goes offline) Bromine still think the scheduler is online, but you won't be able to run test. <b>Solution: Clear cache and try again</b></li>
        <li>If you think you test results are incorrect (eg. you ran a test and it passed, but the state in the tree is still failed/not done). <b>Solution: Clear cache and try again</b> (if you can provoke this type of error reliably, we'd very much like to hear from you)
        </ul>
        ";
    
    echo "<br /><h3>Disable cache</h3><br />
    If you want to disable caching for good follow these steps:
    <ol>
        <li>Open ".APP."config".DS."core.php</li>
        <li>Goto line 73 and change <pre>//Configure::write('Cache.disable', true);</pre> to <pre>Configure::write('Cache.disable', true);</pre></li>
        <li>Goto line 83 and change <pre>Configure::write('Cache.check', true);</pre> to <pre>//Configure::write('Cache.check', true);</pre></li>
        <li><b>CLEAR YOUR CACHE USING THIS BUTTON:</b></li>
        
    </ol>
    
    ";
        
    echo $form->end('Clear all cache');
    echo "<br />";
?>
