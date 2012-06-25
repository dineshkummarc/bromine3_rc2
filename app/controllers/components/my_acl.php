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
class MyAclComponent{
    
    function __construct(){
        App::import('Model', 'Myaco');
        App::import('Model', 'Myaro');
        App::import('Model', 'User');
        $this->Myaco = new Myaco();
        $this->Myaro = new Myaro();
        $this->User = new User();
    }
    
	function hasAccess($user_id, $aco_alias){	
        $this->Myaco->recursive = 1;
        $this->Myaro->recursive = 1;
        
        $user = $this->User->findById($user_id);
        $group_alias = '/'.$user['Group']['name']; // /admin 
        $user_alias = $group_alias . '/' . $user['User']['name']; // /admin/Ralle
        
        $aco=split('/',$aco_alias);
        $aco[0]='everything'; // array(everything, projects, view, 3)
        
        //Create the permutations. $aco_permutations: array(/everything, /everything/projects, /everything/projects/view, /everything/projects/view/3) 
        for($i=1;$i<count($aco)+1;$i++){
            $permutation = '';
            for($u=0;$u<$i;$u++){
                $permutation .= '/'.$aco[$u];
            }
            $aco_permutations[] = $permutation;
        }
        
        //Start with denying access
        $access = false;
        
        //Find if group has access
        //Loop through aco in following order: /everything, then /everything/projects, then /everything/projects/view, then /everything/projects/view/3
        //Each loop set access, so latests most specific aco overwrites less specific        
        foreach($aco_permutations as $aco_permutation){
            if(($list=$this->Myaco->findByAlias($aco_permutation))!==false && !empty($list['Myaro'])){ //The permutation (resource) exsists and has requesters
                foreach($list['Myaro'] as $thisaro){ //Loop through each requester related to this resource
                    if($thisaro['alias'] == $group_alias){ //If one of them is the group
                        if($thisaro['MyarosMyaco']['access'] == 1){
                            $access = true;
                            //echo "group true on $aco_permutation<br />";
                        }elseif($thisaro['MyarosMyaco']['access'] == 0){
                            $access = false;
                            //echo "group false on $aco_permutation<br />";
                        }
                    }
                }
            }
        }
        
        //Find if user has access
        //Loop through aco in following order: /everything, then /everything/projects, then /everything/projects/view, then /everything/projects/view/3
        //Each loop set access, so latests most specific aco overwrites less specific
        foreach($aco_permutations as $aco_permutation){
            if(($list=$this->Myaco->findByAlias($aco_permutation))!==false && !empty($list['Myaro'])){ //The permutation (resource) exsists and has requesters
                foreach($list['Myaro'] as $thisaro){ //Loop through each requester related to this resource
                    if($thisaro['alias'] == $user_alias){ //If one of them is the user
                        if($thisaro['MyarosMyaco']['access'] == 1){
                            $access = true;
                            //echo "user true on $aco_permutation<br />";
                        }elseif($thisaro['MyarosMyaco']['access'] == 0){
                            $access = false;
                            //echo "user false on $aco_permutation<br />";
                        }
                    }
                }
            }
        }
        
        //return access (true/false)
        return $access;

    }
   
}
?>
