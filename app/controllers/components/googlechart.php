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
class GooglechartComponent extends Object
{
    //var $someVar = null;
    //var $controller = true;
 
    function startup(&$controller)
    {
        // This method takes a reference to the controller which is loading it.
        // Perform controller initialization here.
        App::import('Model', 'Requirement');
        $this->Requirement = new Requirement();
    }
 
    function accumulatedTestruns($params = null)
    {
        $startdate   = @$params['startdate'];
        $enddate     = @$params['enddate'];
        $testcase_id = @$params['testcases'];
        $days        = @$params['days'];
        $browser_id  = @$params['browsers'];
        $os_id       = @$params['operatingsystems'];
        $project_id  = @$params['project_id'];
        if (!isset($days)){
            $days = -6;    
        }else{
            $dates = $this->getDates($days);
            $startdate = $dates['startdate'];
            $enddate = $dates['enddate'];
        }
        //(SELECT count(*) FROM tests WHERE tests.status = 'notdone' AND date_format(tests.timestamp,'%Y%m%d') = thedate) as notdone
                
        $sql = "SELECT 
                date_format(timestamp, '%Y%m%d') as thedate,
                date_format(timestamp, '%d.%m') as printabledate,
                (SELECT count(*) FROM tests WHERE tests.status = 'failed' AND date_format(tests.timestamp,'%Y%m%d') = thedate) as failed,
                (SELECT count(*) FROM tests WHERE tests.status = 'passed' AND date_format(tests.timestamp,'%Y%m%d') = thedate) as passed
                FROM tests
                LEFT JOIN suites ON tests.suite_id = suites.id";
                $sql .= " 
                            WHERE suites.project_id = $project_id";    
                if (is_numeric($testcase_id)){
                    $sql .= " 
                            AND tests.testcase_id = $testcase_id";
                }else{
                    $sql .= " 
                            AND tests.testcase_id != 0";
                }
                if (isset($startdate) && isset($enddate) && is_numeric($startdate) && is_numeric($enddate)){
                   $sql .= " 
                            AND date_format(timestamp, '%Y%m%d') >= '$startdate' AND date_format(timestamp, '%Y%m%d') <= '$enddate'"; 
                }
                if(isset($browser_id) && is_numeric($browser_id)){
                   $sql .= " 
                            AND tests.browser_id = " . $browser_id; 
                }
                if(isset($os_id) && is_numeric($os_id)){
                   $sql .= " 
                            AND tests.operatingsystem_id = " . $os_id; 
                }                
        $sql .= " GROUP BY date_format(timestamp, '%Y%m%d')";
        //pr($sql);
        $data = $this->Requirement->query($sql);
        
        //pr($data);
        $count = 0;
        $failedx = '';
        $passedx = '';
        $notdonex = '';
        $y = '';
        $axis_label = '';
        $hiscore = 0;
        foreach ($data as $value) {
            $count++;
            $date = $value[0]['thedate'];
            $printabledate = $value[0]['printabledate'];

            $passed = $value[0]['passed'];
            $failed = $value[0]['failed'];
            //$notdone = $value[0]['notdone'];
            $failedx .= $failed;
            $passedx .= $passed;
            //$notdonex .= $notdone;
            if ($passed > $hiscore){$hiscore = $passed;}
            if ($failed > $hiscore){$hiscore = $failed;}
            //if ($failed > $hiscore){$hiscore = $notdone;}
            $axis_label .= "|$printabledate";
            $y .= $count;
            if ($count != count($data)){
                $failedx .= ',';
                $passedx .= ',';
                //$notdonex .= ',';    
                $y .= ','; 
            }        	
        }
        $hiscore += 2;
        if ($hiscore < 10){$hiscore = 10;}
        //$chart = "http://chart.apis.google.com/chart?cht=lxy&chd=t:-1|$failedx|-1|$passedx|-1|$notdonex&chs=500x250&chco=FF0000,00FF00,C0C0C0&chdl=Failed|Passed|Not done&chxt=x,y,r&chxr=1,0,$hiscore|2,0,$hiscore&chds=0,$hiscore&chxl=0:$axis_label";
        $chart = "http://chart.apis.google.com/chart?cht=lxy&chd=t:-1|$failedx|-1|$passedx&chs=500x250&chco=FF0000,00FF00&chdl=Failed|Passed&chxt=x,y,r&chxr=1,0,$hiscore|2,0,$hiscore&chds=0,$hiscore&chxl=0:$axis_label";
        //echo $chart;
        //$this->set('chart',$chart);
        return $chart;
    }
    
    function percentTestruns($data = null)
    {
        $startdate   = @$params['startdate'];
        $enddate     = @$params['enddate'];
        $testcase_id = @$params['testcases'];
        $days        = @$params['days'];
        $browser_id  = @$params['browsers'];
        $os_id       = @$params['operatingsystems'];
        $project_id  = @$params['project_id'];
        if (!isset($days)){
            $days = -6;    
        }else{
            $dates = $this->getDates($days);
            $startdate = $dates['startdate'];
            $enddate = $dates['enddate'];
        }
        $count = 0;
        $failedx = '';
        $passedx = '';
        $notdonex = '';
        $y = '';
        $axis_label = '';
        $hiscore = 0;
        foreach ($data as $key=>$value) {
            $count++;
            
            //stuff to make the date look nice
            $year = substr($key, 0, 4);
            $month = substr($key, 4, 2);
            $day = substr($key, 6, 2);
            $stamp = mktime(0,0,0,$month,$day,$year);
            $date = date('d.m',$stamp);
            
            $passed = @$value['passed'];
            $failed = @$value['failed'];
            $notdone = @$value['notdone'];
            $total = $failed + $passed + $notdone;
            $failedx .= $failed / $total * 100;
            $passedx .= $passed / $total * 100;
            $notdonex .= $notdone / $total * 100;
            if ($passed > $hiscore){$hiscore = $passed;}
            if ($failed > $hiscore){$hiscore = $failed;}
            if ($failed > $hiscore){$hiscore = $notdone;}
            $axis_label .= "|$date";
            
            $y .= $count;
            if ($count != count($data)){
                $failedx .= ',';
                $passedx .= ',';
                $notdonex .= ',';    
                $y .= ','; 
            }        	
        }
        $chart = "http://chart.apis.google.com/chart?cht=lxy&chd=t:-1|$failedx|-1|$passedx|-1|$notdonex&chs=500x250&chco=FF0000,00FF00,C0C0C0&chdl=Failed|Passed|Not done&chxt=x,y,r&chxl=0:$axis_label";
        return $chart;
    }
    
    function progressTestrun(){
    
    }
    
    function pie($passed,$failed,$notdone){
        $chart = "http://chart.apis.google.com/chart?cht=p3&chd=t:$passed,$failed,$notdone&chs=300x150&chco=FF0000,00FF00,C0C0C0&chdl=Failed|Passed|Not done";
        return $chart;
    }
    
    function getDates($days){
        // Some magic function found on the internet :)
        if ($days > 0){$days = $days *-1;}
        $mydate = date("Y-m-d");
        $datearr = split("-",$mydate);
        $timestamp = mktime(0,0,0,$datearr[1],$datearr[2],$datearr[0]); 
        $newtimestamp = strtotime("$days days",$timestamp);
        $startdate = strftime("%Y%m%d",$newtimestamp);
        $enddate = date("Ymd");
        return array('startdate' => $startdate, 'enddate' => $enddate);
    }
    
    function getAllDates($days){
        for ($i = 0;$i < $days; $i++) {
            $mydate = date("Y-m-d");
            $datearr = split("-",$mydate);
            $timestamp = mktime(0,0,0,$datearr[1],$datearr[2] - $i,$datearr[0]); 
            $dates[] = strftime("%Y%m%d",$timestamp);            	
            
        }
        $dates = array_reverse($dates);
        return $dates;
    }
    
}
?>