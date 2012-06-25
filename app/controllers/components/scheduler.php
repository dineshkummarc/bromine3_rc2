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
class SchedulerComponent extends Object {

    function startup(&$controller) {
        $this->data = $controller->data;
    }

    function saveSchedule($controller, $identifier, $modify = false) {
        if($this->data[$controller]['cron'] == "") {
            $cron = '0 ';
            if($this->data[$controller]['min'] == 60) {
                $cron .='0 ';
            } else {
                $cron .= $this->data[$controller]['min'].' ';
            }
            if($this->data[$controller]['hours'] == 24) {
                $cron .='0 ';
            } else {
                $cron .= $this->data[$controller]['hours'].' ';
            }
            $cron .= $this->data[$controller]['dayOfMonth'].' '
                    .$this->data[$controller]['month'].' '
                    .$this->data[$controller]['dayOfWeek'];
        }
        else {
            $cron = $this->data[$controller]['cron'];
        }
        $url = $this->data[$controller]['url'];
        $id = $this->data[$controller]['id'];
        $site_id = $this->data[$controller]['site_id'];
        if(!$modify) {
            $saveAs = 'add';
        } else {
            $saveAs = 'modify';
        }

        $args = '"'.$saveAs.'" "'.$identifier.$id.'site'.$site_id.'" "'.$url.'" "'.$cron.'"';
        $dir = realpath(APP.'..'.DS.'scheduler');
        chdir($dir);
        $exec = 'java -jar BromineSchedulerClient.jar '.$args;
        exec($exec, $output);
        if(empty($output)) {
            $output[] = 'Schedule was saved successfully!';
        }

        $data['output'] = $output;
        $data['id'] = $id;
        $data['consolePath'] = $url;
        $data['site_id'] = $site_id;
        $data['cron'] = $this->getCron($identifier, $id, $site_id);
        return $data;
    }

    function deleteSchedule($identifier, $id, $site_id, $url=null) {
        $dir = realpath(APP.'..'.DS.'scheduler');
        chdir($dir);
        $args = '"remove" "'.$identifier.$id.'site'.$site_id.'"';
        $exec = 'java -jar BromineSchedulerClient.jar '.$args;
        exec($exec, $output);
        $cron = $this->getCron($identifier, $id, $site_id);
        if(empty($output)) {
            if($cron != '') {
                $output[] = 'Unknown error occured, schedule was not deleted!';
            }else {
                $output[] = 'Schedule was deleted successfully!';
            }
        }

        $data['output'] = $output;
        $data['id'] = $id;
        $data['consolePath'] = $url;
        $data['site_id'] = $site_id;
        $data['cron'] = $cron;
        return $data;
        
    }

    public function getCron($identifier, $id, $site_id) {
        $dir = realpath(APP.'..'.DS.'scheduler');
        chdir($dir);
        $args = '"cron" "'.$identifier.$id.'site'.$site_id.'"';
        $exec = 'java -jar BromineSchedulerClient.jar '.$args;
        $output = exec($exec);
        $data = '';
        if(stristr($output, 'exception')) {
            $data = '';
        } else {
            $data = $output;
        }
        return $data;
    }
}
?>
