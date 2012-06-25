<?php
/*
Copyright 2007, 2008, 2009 Rasmus Berg Palm, Visti Kl�ft and Jeppe Poss Pedersen 

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
<div class='warning'>
    <h1>HERE BE DRAGONS!</h1>
    <p style='font-weight: bold;'>
        The update plugin is BETA. It has power to alter databases and files on your system. Please take a backup of Bromine including the database and store it somewhere safe before updating.
        <br />
        We offer ABSOLUTELY NO WARRANTIES. Any usage is at your own risk.     
    </p>
</div>
<div style='margin-top: 30px;'>
    <?php
        if($writeable_tmp):
        
        if($zip_module):

        if($version == '3.0b') $version = 472;

        if(isset($version)){
            
            echo "<h1>Available updates</h1>";
            echo "You version was detected to be $version"; 
            echo "<div class='index' id='revisions'>";
            echo '<table cellpadding="0" cellspacing="0">';
            echo '<tr>';
            echo '<th>Release name</th>';
            echo '<th>Description</th>';
            echo '<th>Released</th>';
            echo '<th>Updates from</th>';
            echo '<th>Updates to</th>';
            echo '<th class="actions">Actions</th>';
            echo "</tr>";
                
            $i = 0;
            $found_updates = false;
            if(!empty($releases)){
                if(isset($releases['Releases']['Release']['name'])){
                    $release = $releases['Releases']['Release'];
                    $releases = array();
                    $releases['Releases']['Release'][0] = $release;
                } 
                foreach($releases['Releases']['Release'] as $release){
                    if ($version < $release['revision']){
                        $found_updates = true;
                        $class = null;
                        if ($i++ % 2 == 0) {
                            $class = ' class="altrow"';
                        }
                
                        echo "<tr$class>";
                        echo '<td style="width: 100px;">'.$release['name'].'</td>';
                        echo '<td>'.nl2br($release['description']).'</td>';
                        echo '<td style="width: 100px;">'.$release['released'].'</td>';
                        
                        echo '<td style="width: 100px;">'.$release['required'].'</td>';
                        echo '<td style="width: 100px;">'.$release['revision'].'</td>';
                        echo '<td style="width: 100px;">';
                        
                        if ($version == $release['required']) {
                            echo $ajax->link( 
                                'Download update', 
                                array( 'controller' => 'zipupdate', 'action' => 'initiateUpdate', $release['revision']), 
                                array( 'update' => 'initiate_update', 'onclick' => '$("initiate_update").toggle();')
                            ); 
                        } else {
                            echo 'Not available for your version';
                        }
                        
                        echo "</td>";
                        echo "</tr>";
                    }
                }
                if($found_updates === false){
                    echo '<tr><td colspan="6"><div class="success">No updates available, your system is up-to-date.</div></td></tr>';
                }
            }else{
                echo '<tr><td colspan="6"><div class="error">Could not retrieve list of updates. If problem persists please notify admin@brominefoundation.org</div></td></tr>';
            }
            echo '</table>';
            echo "</div>";
        }else{
            echo "<div class='error'>ERROR: Sorry we couldn't determine your current version. You can force your version by going to ".__FILE__." and adding ";
            echo '<pre>&lt;?'.'php $version = 678; ?'.'></pre>';
            echo "At the very start of the file. Replace 678 with the desired version. Remember to remove it again after a successfull update";
            echo "</div>"; 
        }

    ?>
    <div id='initiate_update' style='display: none; margin-top: 20px;'>
        <?php 
            echo $html->image('/zipupdate/img/ajax-loader.gif');
            echo "<br />";
            echo 'Downloading... <br />';
            echo 'Extracting... <br />';
            echo 'Checking permissions... <br />';         
        ?>
    </div>
    <div id='do_update' style='display: none; margin-top: 20px;'>
        <?php 
            echo $html->image('/zipupdate/img/ajax-loader.gif');
            echo "<br />";
            echo 'Copying new files... <br />';
            echo 'Deleting files... <br />';
            echo 'Updating database... <br />';
            echo 'Cleaning up install files... <br />';
            else:
                echo '<div class="error">ERROR: This plugin requires the '.$html->link('Zip module', 'http://www.php.net/manual/en/book.zip.php', array('target' => 'blank')) . '. You need to enable this module before you can use this plugin</div>';
            endif;
            else:
                echo '<div class="error">ERROR: This plugin requires that '.TMP.' is writeable.</div>';
            endif;
        ?>
    </div>
</div>