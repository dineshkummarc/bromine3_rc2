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
<div class="projects view">
<h1><?php echo $project['Project']['name']; ?></h1>
	<dl><?php $i = 0; $class = ' class="altrow"';?>                                                                
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Description'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>                                                                  
			<?php echo nl2br($project['Project']['description']); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Run entire project'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
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
                if($error === true){
                    echo "&nbsp";
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
                elseif(empty($nestedCombinations)){
                    echo "<p class='error'>Error: There are no OS/browser combinations defined for the nested requirements. Please define some</p>";
                }elseif(empty($nestedTestcases)){
                    echo "<p class='error'>Error: There are no testcases assigned to the nested requirements. Please ".$html->link('assign','/Requirements')." some</p>";
                }elseif(count($nestedOfflineNeeds)>=count($nestedCombinations)){
                    echo "<p class='error'>Error: No online nodes meet the OS/browser combinations required. Please ".$html->link('define','/Requirements#/Nodes')." some that does</p>";
                }elseif(isset($noScriptsAll) && $noScriptsAll == true){
                    echo "<p class='error'>Error: There are no testscripts to run. Please upload some.</p>";    
                }
                else{
                    echo $html->link($html->image("tango/32x32/actions/go-next.png"), '/runrctests/runAndViewProject/'.$session->read('project_id'), array('onclick'=>'return Popup.open({url:this.href});'), null, false);
                    $path = 'http://'.$servername.':'.$port.'/runrctests/runAndViewProject/'.$session->read('project_id')."/user:".$session->read('Auth.User.name').'/password:'.$user_password.'/project:'.$session->read('project_id').'/site_id:'.$session->read('site_id');    
                    
                }
                if(isset($nestedNoScripts)){
                    $scriptString = '<div class="warning">Test scripts are missing in the following test cases:<br /><ul>';
                    foreach($nestedNoScripts as $script){
                        $scriptString .= '<li>'.$script.'</li>';
                    }
                    $scriptString .= '</ul></div>';
                    echo $scriptString;
                }
                if(!empty($nestedOfflineNeeds) && !(count($nestedOfflineNeeds)>=count($nestedCombinations)) && $error != true){
                    echo "<p class='warning'>Warning: The following combinations will not be tested as there are no online nodes with that combination:<br />";
                    foreach($nestedOfflineNeeds as $nestedOfflineNeed){
                        echo $nestedOfflineNeed."<br />";
                    }
                    echo "</p>";
                }
                
                if(count($onlineNodes)<count($nodes) 
                && !empty($onlineNodes) 
                && !empty($nodes)){
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

            ?>
            
            
            
            
            
            
            
            
            <?php //echo $html->link($html->image("tango/32x32/actions/go-next.png"), '/runrctests/runAndViewProject/'.$session->read('project_id'), array('onclick'=>'return Popup.open({url:this.href});'), null, false);
                 ?>
			&nbsp;                                                                                     
		</dd>
		
		                                                                                                                                          
	</dl>
	<br />
	<?php
        if (isset($chart1)){
            echo "<h2>Project overview</h2>";
            echo "<img src='$chart1' /><br />";
        }
        if (isset($chart2)){
            echo "<br /><h2>Percentage</h2>";
            echo "<img src='$chart2' /><br />";
        }
         
        if (isset($chart3)){
            echo "<br /><h2>Accumulated test runs</h2>";
            echo "<img src='$chart3' />";
        }
        /*
        echo $form->create('Filter',array('url' => '/testlabs#/projects/testlabview')); 
        //echo $form->input('testcases',array('label' => '','selected' => $this->data['testcases']));
        //echo $form->input('browsers',array('label' => '','selected' => $this->data['browsers']));
        //echo $form->input('operatingsystems',array('label' => '','selected' => $this->data['operatingsystems']));
        echo $form->input('days',array('label' => '','selected' => $this->data['Filter']['days']));
        echo $form->submit();
        */
    ?>
    
</div>
