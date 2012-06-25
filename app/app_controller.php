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
class AppController extends Controller {

    public $components = array('Auth', 'RequestHandler','Menu', 'MyAcl');
    public $helpers = array('Html','Ajax','Javascript', 'Tree');
    public $layout = 'green';
    public $main_menu_id = -1;
    public $time;
    public $user_projects;  
    
    private function echelon($url){
        $user = "";
        if ($this->Auth->user('id')){
            $user = $this->Auth->user('id');
        }
        App::import('Model','Echelon');
        $echelon = new Echelon();
        $echelon->create();
        $data['Echelon']['url'] = $url;
        $data['Echelon']['user_id'] = $user;
        $data['Echelon']['time'] = time();
        $data['Echelon']['ip'] = $_SERVER["REMOTE_ADDR"];
        
        $echelon->save($data);

    }    
    
    public function beforeFilter() {
        
        //pr($this->Session);    
        $this->set('helptxt',$this->params['controller'] . '/' . $this->params['action']);
        
        $this->loadModel('Config');
        $this->set('enableGA',$this->Config->field('value', array('name' => 'enableGA')));
    
        $this->set('user_id',$this->Auth->user('id'));
        $this->set('username',$this->Auth->user('name'));
        $this->password = $this->Auth->user('password');
        $this->realname = $this->Auth->user('firstname') . ' ' .$this->Auth->user('lastname');
        $this->set('realname',$this->Auth->user('firstname') . ' ' .$this->Auth->user('lastname'));
        
        App::import('Model','Group');
        $this->Group = new Group();
        $group = $this->Group->read(null,$this->Auth->user('group_id'));
        $this->set('group', $group['Group']['name']);
        App::import('Model','Config');
        $config = new Config();
        $echelon = $config->findByName('echelon');
        $this->echelon = $echelon['Config']['value'];
        
        $e = $this->echelon ? 'On' : 'Off';
        
        $this->set('echelon', $e);
    
        if($this->echelon){
            if (isset($this->params['url']['url'])){
                $this->echelon(print_r($this->params['url']['url'],true));
            }
        }
        // Auth stuff
        $this->Auth->fields  = array(
            'username'=>'name',
            'password' =>'password'
        );
        
        // Remote login    
        if(!empty($this->passedArgs['user']) && !empty($this->passedArgs['password'])){
            $data['User']['name'] = $this->passedArgs['user'];
            $data['User']['password'] = $this->passedArgs['password'];
            $this->Auth->login($data);
            if(!empty($this->passedArgs['project'])){
                $this->requestAction('/projects/select/'.$this->passedArgs['project'].'/true');
                if(!empty($this->passedArgs['site_id'])) $this->Session->write('site_id',$this->passedArgs['site_id']);               
            }
        }
        
        $this->Auth->loginAction = array('controller' => 'users', 'action' => 'login','plugin'=>null);
        $this->Auth->logoutRedirect = array('controller' => 'users', 'action' => 'login');
        $this->Auth->loginRedirect = array('controller' => 'projects', 'action' => 'select');
        $this->Auth->authorize = 'controller';
        
        // Set userprojects
        App::import('Model','User');
        $this->User = new User();
        $this->User->recursive = 1;
        $user = $this->User->findById($this->Auth->user('id'));
        $this->set('user_password', $user['User']['password']);
        $this->user_password = $user['User']['password'];
        $this->userprojects = $user['Project']; 
        if(!empty($this->userprojects)){
            $this->set('userprojects',$this->userprojects);
        }
        
        if(isset($this->needsproject)){
            if((is_array($this->needsproject) && in_array($this->action, $this->needsproject)) || $this->needsproject===true){ //If the controller/action needs a project
                if($this->Session->check('project_id')===false){ //If project_id is NOT set in the session
                    $this->Session->setFlash('This location requires you have selected a project',true,array('class'=>'error_message'));
                    $this->redirect(array('controller' => 'projects', 'action' => 'select'));
                }
            }
        }

        $this->loadModel('Config');
        $this->set('version', $this->Config->field('value',array('name' => 'version')));
        
             
    }
    
    public function beforeRender(){
        
        $this->set('main_menu_id', $this->main_menu_id);
        $this->set('Menu',$this->Menu->createMenu($this->main_menu_id));
        
        // Check if bromine is registred
        App::import('Model','Config');
        $config = new Config();
        $reg = $config->findByName('registered');
        if ($reg['Config']['value'] == 1){
            $this->set('register','Registered version');
        }
        
    }     
    
    public function isAuthorized(){
        //return true;
        return $this->MyAcl->hasAccess($this->Auth->user('id'),$this->here);
    }

}
