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
class MenuComponent{
    
    function __construct(){
        App::import('Model', 'Plugin');
        $this->Plugin = new Plugin();
    }

    function createMenu($parent_id=null){
    
        $echelonmenu = array();
        $echelonmenu[] = $this->createMenuPoint('Set to: Off','configs','setEchelon/false');
        $echelonmenu[] = $this->createMenuPoint('Set to: On','configs','setEchelon/true');
        
        $accessmenu = array();
        $accessmenu[] = $this->createMenuPoint('Groups','groups','index');
        $accessmenu[] = $this->createMenuPoint('Access control','manage_acl','index');
        $accessmenu[] = $this->createMenuPoint('Users','users','index');
        $accessmenu[] = $this->createMenuPoint('Logs >>','echelons','index', $echelonmenu);

        $settingsmenu = array();
        $settingsmenu[] = $this->createMenuPoint('Browsers','browsers','index');
        $settingsmenu[] = $this->createMenuPoint('Cache','configs','cache');
        //$settingsmenu[] = $this->createMenuPoint('Email Settings','configs','email');
        $settingsmenu[] = $this->createMenuPoint('Operating systems','operatingsystems','index');
        $settingsmenu[] = $this->createMenuPoint('Scheduler','qrtz_job_details','index');
        $settingsmenu[] = $this->createMenuPoint('Server options','configs','server');
        $settingsmenu[] = $this->createMenuPoint('Types','Types','index');
        $settingsmenu[] = $this->createMenuPoint('User Statistics','configs','userStatistics');

        $newsmenu = array();
        $newsmenu[] = $this->createMenuPoint('News','news','index');
        $newsmenu[] = $this->createMenuPoint('State of the system','configs','stateOfTheSystem');
        $newsmenu[] = $this->createMenuPoint('About Bromine','pages','about');

        $adminmenu = array();
        $adminmenu[] = $this->createMenuPoint('Nodes','nodes','index');
        $adminmenu[] = $this->createMenuPoint('Projects','projects','index');
        $adminmenu[] = $this->createMenuPoint('Users and access','','',$accessmenu);
        $adminmenu[] = $this->createMenuPoint('Settings','','',$settingsmenu);
        $adminmenu[] = $this->createMenuPoint('Help','','',$newsmenu);
        
        $testlabmenu = array();
        $testlabmenu[] = $this->createMenuPoint('Latest suites','testlabs#/suites/index','');
        $testlabmenu[] = $this->createMenuPoint('Latest tests','testlabs#/tests/index','');
        $testlabmenu[] = $this->createMenuPoint('Latest tests failed','testlabs#/tests/index/failed','');
        $testlabmenu[] = $this->createMenuPoint('Latest tests passed','testlabs#/tests/index/passed','');
        
        $planning = array();
        $planning[] = $this->createMenuPoint('Add testcase','requirements#/testcases','add');
        $planning[] = $this->createMenuPoint('Add requirement','requirements#/requirements','add');
        $planning[] = $this->createMenuPoint('Import from CSV','requirements','importFromCSV');
        
        $mainmenu[] = $this->createMenuPoint('Planning','requirements#/activities','',$planning);
        $mainmenu[] = $this->createMenuPoint('Test Lab','testlabs#/projects/testlabview','', $testlabmenu);

        $menus[] = $this->createMenuPoint('Main menu','','', $mainmenu, -1);
        $menus[] = $this->createMenuPoint('Admin menu','','', $adminmenu, -2); 

        $children = array();
        $children[] = array(
                            'Menu' => array(
                                'title' => 'Manage plugins',
                                'controller' => 'plugins',
                                'action' => 'index'
                                 
                            )
                        );
        //$menus = $this->Menu->find('threaded',array('order'=>'odr'));
        
        foreach($menus as $key => $menu){
            if($menu['Menu']['id']==$parent_id){
                $Menu = $menu['children'];
                $plugins = $this->Plugin->findAllByActivated(1);
                if(!empty($plugins)){
                    foreach($plugins as $plugin){
                        $name = $plugin['Plugin']['name'];
                        $children[] = array(
                            'Menu' => array(
                                'title' => $name,
                                'controller' => $name,
                                'action' => $name
                                 
                            )
                        ); 
                    }
                    
                    $Menu[] = array(
                        'Menu' => array(
                            'title' => 'Plugins',
                            'controller' => '',
                            'action' => ''
                        ),
                        'children' => $children
                        
                    );
                    
                }
                return $Menu;
            }
        }
    }
    
    function createMenuPoint($title, $controller, $action, $children = null, $id = 0){
        return array('Menu' => array(
            'id' => $id,
            'title' => $title,
            'controller' => $controller,
            'action' => $action,
            ),
            'children' => $children
        );
    }
}
?>
