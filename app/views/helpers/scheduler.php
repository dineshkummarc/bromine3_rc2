<?php

class SchedulerHelper extends AppHelper {

    var $helpers = array('Form', 'Ajax');
    /**
     * Function to populate array
     * $min integer determining what the lowest value should be
     * $max integer determining what the highest value should be
     * $useThis the array to use, if not set creates a new one
     * $opt array contain the value to use instead of an integer             
     */      
    function fillOption($min, $max, $useThis=NULL, $opt = NULL){
        if(!is_null($useThis)){
            $arr = $useThis;
        }
        $x=0;
        for($i=$min; $i<=$max; $i++) {
            if(is_null($opt)){
                $arr[$i] = $i;
            }
            else{
                $arr[$i] = $opt[$x];
            }
            $x++;
        }
        return $arr;
    }
    
    /**
     * Creates the scheduler table
     * $id testcase or requirement id
     * $cronExpression saved cron expression
     * $consolePath the path to execute the test, must be consolepath where a blank view is used
     * $type must either be testcase or requirement, determins the model and controller to use
     * $schedOutput the output generated after saving, modifying or deleting a testcase.                         
     */         
    function makeSchedule($id, $cronExpression, $consolePath, $type, $site_id, $schedOutput = null){
        $cronAdvanced = false;
        if($cronExpression != ""){
            $cron = $cronExpression;
            $chars = array('/', ',', '-', 'L', 'W', '#');
            
            foreach($chars as $char){
                if(stripos($cron, $char) !== false){
                    $cronAdvanced = true;
                    break;
                }
            }
            if(!$cronAdvanced){
                $cron = explode(' ', $cron);
            }
        }
        
        if(!isset($cron)){
                $saveOption = 'saveSchedule';
        } else{
            $saveOption = 'modifySchedule';
        }
        if($type == 'testcase'){
            $model = 'Testcase';
            $controller = 'testcases';
        } elseif($type == 'requirement'){
            $model = 'Requirement';
            $controller = 'requirements';
        }
        $output = $this->Ajax->form(array('type' => 'post',
            'options' => array(
                'model'=> $model,
                'update'=>'schedule',
                'url' => array(
                    'controller' => $controller,
                    'action' => $saveOption
                )
            )
        ));
        $display = $cronAdvanced ? 'style="display: none;"' : '';
        $output .= "<div id='normalcron' $display>";
        $output .= '<table>
                <tr>
                    <th>Minute</th>
                    <th>Hour</th>
                    <th>Day of month</th>
                    <th>Month</th>
                    <th>Day of week</th>
                </tr>
                <tr>
                    <td>';
        $extraOptions = array('*' => 'All values');
        $extraOptions2 = array('*' => 'All values', '?' => 'No value (?)');
        $output .= $this->Form->hidden('url', array('default'=> $consolePath));  
        $output .= $this->Form->hidden('id', array('default'=> $id));
        $output .= $this->Form->hidden('site_id', array('default'=> $site_id));               
//        pr($cron);
        if(isset($cron) && !$cronAdvanced){
            if($cron[1] == '0'){
                $cron[1] = '60';
            }
            if($cron[2] == '0'){
                $cron[2] = '24';
            }
            $output .= $this->Form->select('min', $this->fillOption(1,60,$extraOptions), $cron[1], null, false).'</td>';
            $output .= '<td>'.$this->Form->select('hours', $this->fillOption(1, 24,$extraOptions), $cron[2], null,false).'</td>';
            $output .= '<td>'.$this->Form->select('dayOfMonth', $this->fillOption(1, 31,$extraOptions2), $cron[3], null, false);
            $output .= '<td>'.$this->Form->select('month', $this->fillOption(1, 12,$extraOptions,array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec")), $cron[4], null, false);
            $output .= '<td>'.$this->Form->select('dayOfWeek', $this->fillOption(1, 7,$extraOptions2, array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat")), $cron[5], null, false);
        } else{
            $output .= $this->Form->select('min', $this->fillOption(1,60,$extraOptions), null, null, false).'</td>';
            $output .= '<td>'.$this->Form->select('hours', $this->fillOption(1, 24,$extraOptions), null, null,false).'</td>';
            $output .= '<td>'.$this->Form->select('dayOfMonth', $this->fillOption(1, 31,$extraOptions2), null, null, false);
            $output .= '<td>'.$this->Form->select('month', $this->fillOption(1, 12,$extraOptions,array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec")), null, null, false);
            $output .= '<td>'.$this->Form->select('dayOfWeek', $this->fillOption(1, 7,$extraOptions2, array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat")), null, null, false);
        }                                  
        $output .= '</tr>';
        $output .= '</table>';
        $output .= '</div>';
        $display = $cronAdvanced ? '' : 'style="display: none;"';
        
        $output .= "<a style='cursor: pointer;' onclick='Effect.toggle(".'"customcron", "blind"'.");Effect.toggle(".'"normalcron", "blind"'.");'> Toggle between normal and custom cron expression</a>";
        $output .= "<div id='customcron' $display>";    
                 
        if($cronAdvanced){
            $output .= $this->Form->text("cron", array('default' => $cron, 'size' => 60));
        }else{
            $output .= $this->Form->text("cron", array('default' => '', 'size' => 60));
        }
        $output .= '</div>';
        $output .= '<div>'; 
        $output .= '<input type="submit" value="';
        $output .= (isset($cron) ? "Modify" : "Save").'" />';
        $output .= '</div>';
        $output .= '</form>';
                            
        if(isset($cron)){
            $output .= $this->Ajax->link( 
            'Delete schedule', 
            array( 'controller' => $controller, 'action' => 'deleteSchedule', $id, $site_id, $consolePath), 
            array( 'update' => 'schedule' ), 'Do you want to delete this schedule?'
            );
        } 

        $output .= '<div id="schedStatus">'.$schedOutput.'</div>';

        return $output;
    }
}

?>
