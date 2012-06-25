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
class InstallController extends PizzaAppController {
    
    function install(){
        //This is the place to dreate and populate whatever tables you need. Be carefull!
        //Bromine will create the relevant ACL entries for the plugin
        $sql1 = "
        CREATE TABLE 'table_name'
        ('column 1' 'data_type_for_column_1',
        'column 2' 'data_type_for_column_2')
        ";
        $sql2 = "
        INSERT INTO table_name (column1, column2, column3,...)
        VALUES ('value1', 'value2', 'value3')
        ";
        /*
        $this->Install->query($sql1);
        $this->Install->query($sql2);
        if(allwentwell){
            return true;
        }else{
            return error;
        }
        */
        return true; 
    }
    
    function uninstall(){
        //this is the place to remove whatever you inserted into the DB. Be carefull! 
        //Bromine will delete the folder containing the plugin and remove the ACL entries
        $sql = "DROP TABLE table_name1, table_name2";
        /*
        $this->Install->query($sql);
        if(allwentwell){
            return true;
        }else{
            return error;
        }
        */
        return true;
    }
     
}
?>