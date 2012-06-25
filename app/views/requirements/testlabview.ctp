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
<div class="requirements view">
    <h1><?php  echo $requirement['Requirement']['name']; ?></h1>
	<dl>
    <dt><?php __('Requirement owner'); ?></dt>
		<dd><?php echo $requirement['User']['firstname'] . ' ' . $requirement['User']['lastname'] . ' - ' .$requirement['User']['name'] . '(' . $requirement['User']['email'] . ')'; ?></dd>
    <dt><?php __('Description'); ?></dt>
		<dd>
			<?php echo nl2br($requirement['Requirement']['description']); ?>
			&nbsp;
		</dd>		
		<dt><?php __('Add to que'); ?></dt>
		<dd>
			<?php
			    $error = false;
                if(isset($stateOfTheSystemErrors)){
                    echo '<p class="error">Error: '.$html->link('stateOfTheSystem','/configs/stateOfTheSystem').' reports errors:';
                        echo "<br /><br />";
                        foreach($stateOfTheSystemErrors as $stateOfTheSystemError){
                            echo $stateOfTheSystemError['result']."<br />";
                        }                  
                    echo '</p>';
                    $error = true;
                                        
                }
    			elseif(empty($nodes)){
                    echo "<p class='error'>Error: There are no nodes defined. Please ".$html->link('add','/Requirements#/Nodes/add/').' some</p>';
                    $error = true;
                }
    			elseif(empty($onlineNodes)){
                    echo "<p class='error'>Error: There are no nodes online. Please start the Selenium Remote Control servers at:<br />";
                    foreach($nodes as $node){
                        echo $node['Node']['nodepath']."<br />";
                    }
                    echo "Or try to ";
                    echo $ajax->link( 
                        'Clear the node cache', 
                        array( 'controller' => 'nodes', 'action' => 'clearCache'), 
                        array( 'complete' => ' javascript:location.reload(true)' )
                    );
                    echo "</p>"; 

                    $error = true;
                }   
                elseif(empty($testcases)){
                    echo "<p class='error'>Error: There are no testcases assigned to this requirement. Please ".$html->link('assign','/Requirements')." some</p>";
                }
                elseif(empty($combinations)){
                    echo "<p class='error'>Error: There are no OS/browser combinations defined. Please ".$html->link('define','/Requirements#/Requirements/edit/'.$requirement['Requirement']['id'])." some</p>";
                }elseif(empty($onlineCombinations)){
                    echo "<p class='error'>Error: The online nodes have no browsers. Please ".$html->link('define','/Requirements#/Nodes')." some</p>";                    
                }elseif(count($offlineNeeds)>=count($combinations)){
                    echo "<p class='error'>Error: No online nodes meet the OS/browser combinations required. Please ".$html->link('define','/Requirements#/Nodes')." some that does</p>";
                }elseif(isset($noScriptsAll) && $noScriptsAll == true){
                    echo "<p class='error'>Error: There are no testscripts to run. Please upload some.</p>";    
                }
                else{
                    echo $html->link($html->image("tango/32x32/actions/go-next.png"), '/runrctests/runAndViewRequirement/'.$requirement['Requirement']['id'], array('onclick'=>'return Popup.open({url:this.href});'), null, false);
                    $path = 'http://'.$_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'].'/runrctests/runAndViewRequirement/'.$requirement['Requirement']['id']."/user:".$session->read('Auth.User.name').'/password:'.$user_password.'/project:'.$session->read('project_id').'/site_id:'.$session->read('site_id');    
                    
                }
                if(isset($noScripts)){
                    $scriptString = '<div class="warning">Test scripts are missing in the following test cases:<br /><ul>';
                    foreach($noScripts as $script){
                        $scriptString .= '<li>'.$script.'</li>';
                    }
                    $scriptString .= '</ul></div>';
                    echo $scriptString;
                }
                if(!empty($offlineNeeds) && !(count($offlineNeeds)>=count($combinations)) && !isset($noScriptsAll)){
                    echo "<p class='warning'>Warning: The following combinations will not be tested as there are no online nodes with that combination:<br />";
                    foreach($offlineNeeds as $offlineNeed){
                        echo $offlineNeed."<br />";
                    }
                    echo "</p>";
                }
                
                if(count($onlineNodes)<count($nodes) 
                && !empty($onlineNodes) 
                && !empty($nodes) 
                && !isset($noScriptsAll)){
                    echo "<p class='notice'>Notice: The following nodes are defined but not running. Starting them will increase performance:<br />";
                    $onlineNodePaths = array();
                    foreach($onlineNodes as $onlineNode){
                        $onlineNodePaths[]=$onlineNode['Node']['nodepath'];
                    }
                    foreach($nodes as $node){
                        if(!in_array($node['Node']['nodepath'],$onlineNodePaths)){
                            echo $node['Node']['nodepath']."<br />";
                        }
                    }
                    echo "</p>";
                }
                $path = "http://$servername:$port/runrctests/runAndViewRequirement/".$requirement['Requirement']['id']."/user:".$session->read('Auth.User.name').'/password:'.$user_password.'/project:'.$session->read('project_id').'/site_id:'.$session->read('site_id');
                $consolePath = "http://$servername:$port/runrctests/runNoViewRequirement/".$requirement['Requirement']['id']."/user:".$session->read('Auth.User.name').'/password:'.$user_password.'/project:'.$session->read('project_id').'/site_id:'.$session->read('site_id');
            ?>
		</dd>
		<?php if(count($nestedRequirements)>1): ?>
		<dt><?php __('Add nested to que'); ?></dt>
		<dd>
			<?php
			    //pr($nestedRequirements);
                if($error === true){
                    echo "&nbsp";                
                }
                elseif(empty($nestedCombinations)){
                    echo "<p class='error'>Error: There are no OS/browser combinations defined for the nested requirements. Please ".$html->link('define','/Requirements#/Requirements/edit/'.$requirement['Requirement']['id'])." some</p>";
                }elseif(empty($nestedTestcases)){
                    echo "<p class='error'>Error: There are no testcases assigned to the nested requirements. Please ".$html->link('assign','/Requirements')." some</p>";
                }elseif(count($nestedOfflineNeeds)>=count($nestedCombinations)){
                    echo "<p class='error'>Error: No online nodes meet the OS/browser combinations required. Please ".$html->link('define','/Requirements#/Nodes')." some that does</p>";
                }elseif(isset($nestedNoScriptsAll) && $nestedNoScriptsAll == true){
                    echo "<p class='error'>Error: There are no testscripts to run. Please upload some.</p>";    
                }
                else{
                    echo $html->link($html->image("tango/32x32/actions/go-next.png"), '/runrctests/runAndViewNestedRequirement/'.$requirement['Requirement']['id'], array('onclick'=>'return Popup.open({url:this.href});'), null, false);
                    $path = 'http://'.$_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'].'/runrctests/runAndViewRequirement/'.$requirement['Requirement']['id']."/user:".$session->read('Auth.User.name').'/password:'.$user_password.'/project:'.$session->read('project_id').'/site_id:'.$session->read('site_id');    
                    
                }
                if(isset($nestedNoScripts)){
                    $scriptString = '<div class="warning">Test scripts are missing in the following test cases:<br /><ul>';
                    foreach($nestedNoScripts as $script){
                        $scriptString .= '<li>'.$script.'</li>';
                    }
                    $scriptString .= '</ul></div>';
                    echo $scriptString;
                }
                if(!empty($nestedOfflineNeeds) && !(count($nestedOfflineNeeds)>=count($nestedCombinations))){
                    echo "<p class='warning'>Warning: The following combinations will not be tested as there are no online nodes with that combination:<br />";
                    foreach($nestedOfflineNeeds as $nestedOfflineNeed){
                        echo $nestedOfflineNeed."<br />";
                    }
                    echo "</p>";
                }

                $nested_path = "http://$servername:$port/runrctests/runAndViewNestedRequirement/".$requirement['Requirement']['id']."/user:".$session->read('Auth.User.name').'/password:'.$user_password.'/project:'.$session->read('project_id').'/site_id:'.$session->read('site_id');
                $nested_consolePath = "http://$servername:$port/runrctests/runNoViewNestedRequirement/".$requirement['Requirement']['id']."/user:".$session->read('Auth.User.name').'/password:'.$user_password.'/project:'.$session->read('project_id').'/site_id:'.$session->read('site_id');
            ?>
		</dd>
		<?php endif; ?>
		<dt><?php __('Direct link'); ?></dt>
		<dd>
            <a onclick="Effect.toggle('directlink','blind');" style='cursor: pointer;'>Show</a>
            <div id='directlink' style='display: none;'><?php echo 'With viewer: '.$path.'<br />'.'Without viewer: '.$consolePath . '<br /> With viewer (nested):' .$nested_path . '<br />' . 'Without viewer(nested): '. $nested_consolePath; ?></div>
        </dd>
        <dt><?php if(isset($stateOfTheSystemErrors)) : __('Schedule'); ?></dt>
        <dd>
            <a onclick="Effect.toggle('schedule','blind');" style='cursor: pointer;'>Schedule test run</a>
            <div id='schedule' style='display: none;'>     
            <?php echo $scheduler->makeSchedule($requirement['Requirement']['id'], $cron, $consolePath, 'requirement',$session->read('site_id')); ?>
            </div>
        </dd>
		<dt><?php endif; __('Status'); ?></dt>
		<dd>
			
		    <table class="combinations">
			<thead>
			    <tr>
				<th>&nbsp;</th>
				<?php foreach($combinations as $combination): ?>
				<th><?php echo $combination['Browser']['name'] . '<br />' . $combination['Operatingsystem']['name']; ?> </th>
				<?php endforeach; ?>
			    </tr>
			</thead>
			<tbody>
			    <?php foreach($testcases as $testcase): ?>
			    <tr>
				<th><?php echo $testcase['name']; ?></th>
				<?php foreach($combinations as $combination): ?>
				<td class="<?php echo $combination['tc'.$testcase['id']]['status']; ?>">
				    <?php
				    //if($combination['tc'.$testcase['id']]['status'] != 'notdone'){
					switch($combination['tc'.$testcase['id']]['status'])
					{
					    case 'passed':
						echo $html->link($html->image('passed.png'),'#/Tests/view/'.$combination['tc'.$testcase['id']]['Test_id'],null,null,false);    
					    break;
					    case 'failed':
						echo $html->link($html->image('failed.png'),'#/Tests/view/'.$combination['tc'.$testcase['id']]['Test_id'],null,null,false);    
					    break;
					    case 'notdone':
					    echo $html->image('48px-Media-playback-pause.svg.png');
					    break;
					    default:
						echo $html->link($html->image('tango/32x32/categories/applications-other.png'),'#/Tests/view/'.$combination['tc'.$testcase['id']]['Test_id'],null,null,false);    
					}
				    //}
				    if (!empty($combination['tc'.$testcase['id']]['timestamp']) && $combination['tc'.$testcase['id']]['timestamp'] != 0){
					   echo "<br /><small>" . $time->timeAgoInWords($combination['tc'.$testcase['id']]['timestamp']) . "</small>"; 
				    }else{
                       echo "<br /><small> Not run </small>";
                    }  			    
				    ?>
				</td>
				<?php endforeach; ?>
			    </tr>
			    <?php endforeach; ?>
			</tbody>
			<caption>Click Pass/Fail icons to view detailed results</caption>
		    </table>
		</dd>
	</dl>
	
</div>


<div id="log"></div>
