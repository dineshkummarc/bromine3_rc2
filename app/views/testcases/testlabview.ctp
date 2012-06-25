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
<div id = 'testcase' class="testcases view">
    <h1><?php echo $testcase['Testcase']['name']; ?></h1>

    <dl>
        <dt><?php __('Testcase owner'); ?></dt>
        <dd><?php echo $requirement['User']['firstname'] . ' ' . $requirement['User']['lastname'] . ' - ' .$requirement['User']['name'] . '(' . $testcase['User']['email'] . ')'; ?></dd>

        <dt><?php __('Description'); ?></dt>
        <dd>
            <?php echo nl2br($testcase['Testcase']['description']); ?>
            &nbsp;
        </dd>
        <?php if (isset($modified)) {?>
        <dt>
                <?php __('Script modified'); ?></dt>
        <dd><?php

                if (!is_array($modified)) {
                    echo $time->timeAgoInWords($modified);
                }elseif(is_array($modified)) {
                    foreach($modified as $file => $timestamp) {
                        echo $file . ' modified: ' . $time->timeAgoInWords($timestamp) . "<br />";
                    }
                }else {
                    echo $html->img('\tango\32x32\emblems\emblem-important.png') . " No script uploaded";
                }
                ?></dd>
            <?php } ?>
        <dt><?php __('Add to que'); ?></dt>
        <dd>
            <?php

            $onlineCombinations = array();
            foreach($onlineNodes as $onlineNode) {
                foreach($onlineNode['Browser'] as $browser) {
                    $onlineCombinations[] = $onlineNode['Operatingsystem']['id'].','.$browser['id'];
                }
            }
            $offlineNeeds =  array();
            foreach($combinations as $combination) {
                $idCombination = $combination['Operatingsystem']['id'].','.$combination['Browser']['id'];
                if(!in_array($idCombination,$onlineCombinations)) {
                    $offlineNeeds[] = $combination['Browser']['name'].' on '.$combination['Operatingsystem']['name'];
                }
            }
            if(isset($stateOfTheSystemErrors)){
                    echo '<p class="error">Error: '.$html->link('stateOfTheSystem','/configs/stateOfTheSystem').' reports errors:';
                        echo "<br /><br />";
                        foreach($stateOfTheSystemErrors as $stateOfTheSystemError){
                            echo $stateOfTheSystemError['result']."<br />";
                        }                  
                    echo '</p>';
                                        
                }
            elseif(isset($noScript)) {
                echo '<p class="error">Error: No script uploaded. Please upload one.</p>';
                //echo $html->image('tango/32x32/emblems/emblem-important.png') . " No script uploaded";
            }
            elseif(empty($nodes)) {
                echo "<p class='error'>Error: There are no nodes defined. Please ".$html->link('add','/Requirements#/Nodes/add/').' some</p>';
            }
            elseif(empty($onlineNodes)) {
                echo "<p class='error'>Error: There are no nodes online. Please start the Selenium Remote Control servers at:<br />";
                foreach($nodes as $node) {
                    echo $node['Node']['nodepath']."<br />";
                }
                echo "Or try to ";
                echo $ajax->link( 
                    'Clear the node cache', 
                    array( 'controller' => 'nodes', 'action' => 'clearCache'), 
                    array( 'complete' => ' javascript:location.reload(true)' )
                );
                echo "</p>";
            }
            elseif(empty($combinations)) {
                echo "<p class='error'>Error: There are no OS/browser combinations defined. Please ".$html->link('define','/Requirements#/Requirements/edit/'.$requirement['Requirement']['id'])." some</p>";
            }elseif(empty($onlineCombinations)) {
                echo "<p class='error'>Error: The online nodes have no browsers. Please ".$html->link('define','/Requirements#/Nodes')." some</p>";
            }elseif(count($offlineNeeds)>=count($combinations)) {
                echo "<p class='error'>Error: No online nodes meet the OS/browser combinations required. Please ".$html->link('define','/Requirements#/Nodes')." some that does</p>";
            }
            else {
                echo $html->link($html->image("tango/32x32/actions/go-next.png").'', '/runrctests/runAndViewTestcase/'.$testcase['Testcase']['id'].'/'.$requirement['Requirement']['id'], array('onclick'=>'return Popup.open({url:this.href});'), null, false);

            }

            if(!empty($offlineNeeds) && !(count($offlineNeeds)>=count($combinations)) && !isset($noScript)) {
                echo "<p class='warning'>Warning: The following combinations will not be tested as there are no online nodes with that combination:<br />";
                foreach($offlineNeeds as $offlineNeed) {
                    echo $offlineNeed."<br />";
                }
                echo "</p>";
            }

            if(count($onlineNodes)<count($nodes) && !empty($onlineNodes) && !empty($nodes) && !isset($noScript)) {
                echo "<p class='notice'>Notice: The following nodes are defined but not running. Starting them will increase performance:<br />";
                $onlineNodePaths = array();
                foreach($onlineNodes as $onlineNode) {
                    $onlineNodePaths[]=$onlineNode['Node']['nodepath'];
                }
                foreach($nodes as $node) {
                    if(!in_array($node['Node']['nodepath'],$onlineNodePaths)) {
                        echo $node['Node']['nodepath']."<br />";
                    }
                }
                echo "</p>";
            }

            $path = "http://$servername:$port/runrctests/runAndViewTestcase/".$testcase['Testcase']['id'].'/'.$requirement['Requirement']['id']."/user:".$session->read('Auth.User.name').'/password:'.$user_password.'/project:'.$session->read('project_id').'/site_id:'.$session->read('site_id');
            $consolePath = "http://$servername:$port/runrctests/runNoViewTestcase/".$testcase['Testcase']['id'].'/'.$requirement['Requirement']['id']."/user:".$session->read('Auth.User.name').'/password:'.$user_password.'/project:'.$session->read('project_id').'/site_id:'.$session->read('site_id');
            ?>
        </dd>

        <dt><?php __('Direct link'); ?></dt>
        <dd>
            <a onclick="Effect.toggle('directlink','blind');" style='cursor: pointer;'>Show</a>
            <div id='directlink' style='display: none;'><?php echo 'With viewer: '.$path.'<br />'.'Without viewer: '.$consolePath ?></div>
        </dd>
        <dt><?php if(isset($stateOfTheSystemErrors)) : __('Schedule'); ?></dt>
        <dd>
            <a onclick="Effect.toggle('schedule','blind');" style='cursor: pointer;'>Schedule test run</a>
            <div id='schedule' style='display: none;'>     
                <?php echo $scheduler->makeSchedule($testcase['Testcase']['id'], $cron, $consolePath, 'testcase', $session->read('site_id'), ''); ?>
            </div>
        </dd>

        <dt><?php endif; __('Status'); ?></dt>
        <dd>
            <?php
            //pr($combinations);
            ?>
            <table class="combinations">
                <thead>
                    <tr>
                        <?php foreach($combinations as $combination): ?>
                        <th><?php echo $combination['Browser']['name'] . '<br />' . $combination['Operatingsystem']['name']; ?> </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>

                        <?php foreach($combinations as $combination): ?>
                            <?php
                            if(!empty($combination['Result'])) {
                                $status = $combination['Result']['Test']['status'];
                            }else {
                                $status = 'notdone';
                            }
                            ?>
                        <td class="<?php echo $status ?>">
                                <?php
                                //if($status != 'notdone'){
                                switch($status) {
                                    case 'passed':
                                        echo $html->link($html->image('passed.png'),'#/Tests/view/'.$combination['Result']['Test']['id'],null,null,false);
                                        break;
                                    case 'failed':
                                        echo $html->link($html->image('failed.png'),'#/Tests/view/'.$combination['Result']['Test']['id'],null,null,false);
                                        break;
                                    case 'notdone':
                                        echo $html->image('48px-Media-playback-pause.svg.png');
                                        break;
                                    case 'running':
                                        echo $html->link($html->image('tango/32x32/categories/applications-other.png'),'#/Tests/view/'.$combination['Result']['Test']['id'],null,null,false);
                                        echo "<br /><small>Running</small>";
                                        break;
                                    default:
                                        echo $html->link($html->image('tango/32x32/categories/applications-other.png'),'#/Tests/view/'.$combination['Result']['Test']['id'],null,null,false);
                                }
                                //}
                                if (!empty($combination['Result']['Test']['timestamp']) && $combination['Result']['Test']['timestamp'] != 0) {
                                    echo "<br /><small>" . $time->timeAgoInWords($combination['Result']['Test']['timestamp']) . "</small>";
                                }else {
                                    echo "<br /><small> Not run </small>";
                                }
                                ?>
                        </td>
                        <?php endforeach; ?>
                    </tr>
                </tbody>
                <caption>Click Pass/Fail icons to view detailed results</caption>
            </table>
        </dd>
        <br />
        <dt>Steps:</dt>
        <dd>
            <?php if(!empty($testcasesteps)): ?>
            <table>
                <tr>
                    <th style='width: 50%;'>
                        Action
                    </th>
                    <th style='width: 50%;'>
                        Reaction
                    </th>
                </tr>
                    <?php foreach($testcasesteps as $testcasestep): ?>
                <tr style='height: 40px; vertical-align: top;'>
                    <td style='width: 250px; border: 1px solid lightgrey; padding: 5px;'>
                                <?php echo $testcasestep['TestcaseStep']['action']; ?>
                    </td>
                    <td style='width: 250px; border: 1px solid lightgrey; padding: 5px;'>
                                <?php echo $testcasestep['TestcaseStep']['reaction']; ?>
                    </td>
                </tr>
                    <?php endforeach; ?>
            </table>
            <?php endif ?>
        </dd>
        <br />
        <?php if(!empty($history)): ?>
            <dt>History</dt>
            <dd>
                <table class="combinations">
                    <tr>
                        <?php foreach($combinations as $combination): ?>
                        <th><?php echo $combination['Browser']['name'] . '<br />' . $combination['Operatingsystem']['name']; ?> </th>
                        <?php endforeach; ?>
                    </tr>
                    <?php
                    for($i=0; $i< count($history);$i++) {
                        $results[$history[$i]['Test']['suite_id']][$history[$i]['Test']['id']]['operatingsystem'] = $history[$i]['Operatingsystem']['name'];
                        $results[$history[$i]['Test']['suite_id']][$history[$i]['Test']['id']]['browser'] = $history[$i]['Browser']['name'];
                        $results[$history[$i]['Test']['suite_id']][$history[$i]['Test']['id']]['status'] = $history[$i]['Test']['status'];
                        $results[$history[$i]['Test']['suite_id']][$history[$i]['Test']['id']]['timestamp'] = $history[$i]['Test']['timestamp'];
                    }
                    foreach($results as $suite){
                        echo '<tr>';
                        foreach($combinations as $combination) {
                                $gotResult = false;
                                foreach($suite as $test_id => $test) {
                                    if($combination['Operatingsystem']['name'] == $test['operatingsystem'] && $combination['Browser']['name'] == $test['browser']) {
                                            /* A fix for when there is no status on the test run.
                                             * Should never occure because by default the test should be running unless
                                             * proven otherwise. Issue currently present that test can have no status
                                            */
                                            if($test['status'] == ''){
                                                $test['status'] = 'failed';
                                            }
                                            echo '<td class="'.$test['status'].'">'.$html->link($html->image($test['status'].'.png'),'#/Tests/view/'.$test_id,null,null,false);
                                            echo "<br /><small>".$time->timeAgoInWords($test['timestamp'])."</small></td>";
                                            $gotResult = true;
                                    }
                                }
                                if(!$gotResult){
                                     echo '<td class="notdone">'.$html->image('48px-Media-playback-pause.svg.png');
                                     echo "<br /><small>Not run</small></td>";
                                }    
                        }
                         echo '</tr>';
                    }
                        
                    ?>
                </table>
                
            </dd>
        <?php endif; ?>
    </dl>
</div>
