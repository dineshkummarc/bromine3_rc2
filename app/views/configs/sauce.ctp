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

    echo $form->create('Config',array('action'=>'sauce'));

    echo "<p>Sauce Labs OnDemand is a service that allows you to run your tests against nodes in the cloud. You can read more and sign up at ".$html->link('http://saucelabs.com', 'http://saucelabs.com').'</p>';
    echo "Enable Sauce Labs OnDemand integration:";
    $checked = '';    
    $sauce_enabled == 1 ? $checked = 'checked' : '';
    echo $form->checkbox('sauce_enabled', array('checked' => $checked));
    echo "<br /><br />";    
    if($sauce_enabled == 1){
        echo "<div> You have enabled Sauce Labs OnDemand integration.
        <br /> A number of special Sauce Labs browser and operatingsystems, as based on this ".$html->link('list,', 'https://saucelabs.com/products/docs/sauce-ondemand/browsers')." have been added. 
        <a onclick='$(".'"browserformat"'.").toggle();' style='cursor: pointer;'>You list is outdated, I want to add my own browsers/OS's</a>. <div id='browserformat' style='display: none;'>Ok. go to ".$html->link('browsers/add', array('controller' => 'browsers', 'action' => 'add'))." and ". $html->link('operatingsystems/add', array('controller' => 'operatingsystems', 'action' => 'add'))." to add them. The browsers and OS's both needs to start with the string 'SauceLabs ' (notice the space) followed by the browser/OS name. In the case of browsers, Sauce Labs needs both a browser name and a browser version. To do this Bromine splits the browser name on a hyphen into name and version. Path is redundant. The format is '[name]-[version]', eg. 'firefox-3.0.' will split into [name] = firefox and [version] = 3.0.</div>
        <br /> Two Sauce Labs nodes that are preconfigured to use these OS and browsers have been added. 
        <br /> You will need to setup your requirements to be run on the Sauce Labs OS/browser combinations under ".$html->link('Planning', array('controller' => 'requirements', 'action' => 'index'))."
        <br /> You will need to register an account with Sauce Labs and provide the details below.
        <br /> If you disable Sauce Labs OnDemand integration the added nodes, browsers and OS's will be removed.
        </div>";
        echo "<br />";
        echo $form->input('sauce_username', array('label' => 'Sauce Labs username:', 'value' => $sauce_username));
        echo "<br />";
    	echo $form->input('sauce_apikey', array('label' => 'Sauce Labs API Access Key:', 'value' => $sauce_apikey));
        echo "<a onclick='$(".'"advanced_sauce"'.").toggle();' style='cursor: pointer;'>Advanced settings</a>"; 
        echo "<div id='advanced_sauce'  style='display: none;'>";
        echo "The Sauce node IP is used internally to figure out which tests are being run against Sauce Labs. You would only need to change this in case Sauce Labs changes their IP for their OnDemand service. You will still need to change the Nodes IP's seperately as well.";
        echo $form->input('sauce_nodepath', array('label' => 'Sauce node IP:', 'value' => $sauce_nodepath));
        echo "</div>";
    }
    
    echo $form->end('Submit');
?>
