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
class PluginsController extends AppController {

	public $helpers = array('Html', 'Form', 'Time');
	public $main_menu_id = -2;

	function index(){
		$this->Plugin->recursive = 0;
		
		$dbPluginList = $this->Plugin->find('list');
		
		$dirPluginList = array();
        if ($handle = opendir(ROOT.DS.'app'.DS.'plugins')) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && $file != '.svn') {
                    $dirPluginList[] = $file;
                }
            }
            closedir($handle);
        }
        $notInstalled = array_diff($dirPluginList, $dbPluginList);
        
        $noFiles = array_diff($dbPluginList, $dirPluginList);
        /*
        $this->Plugin->deleteAll(array( //Clean up DB entries with no plugin folder
                'Plugin.id' => array_keys($noFiles)
            ));
        */
        $plugins = $this->paginate();
        App::import('Xml');
        foreach($plugins as &$plugin){
            $xml = new Xml(file_get_contents(ROOT.DS.'app'.DS.'plugins'.DS.$plugin['Plugin']['name'].DS.'meta.xml'));
            $plugin['Plugin']['author'] = $xml->child('plugin')->child('author')->children[0]->value;
            $plugin['Plugin']['email'] = $xml->child('plugin')->child('email')->children[0]->value;
            $plugin['Plugin']['website'] = $xml->child('plugin')->child('website')->children[0]->value;
            $plugin['Plugin']['description'] = $xml->child('plugin')->child('description')->children[0]->value;
        }
        $this->set('plugins', $plugins);
        
        foreach($notInstalled as $k => &$plugin){
            $plugin_name = $plugin;
            $plugin = array();
            $meta = ROOT.DS.'app'.DS.'plugins'.DS.$plugin_name.DS.'meta.xml';
            if(file_exists($meta)){
                $xml = new Xml(file_get_contents($meta));
                $plugin['Plugin']['name'] = $plugin_name;
                $plugin['Plugin']['author'] = $xml->child('plugin')->child('author')->children[0]->value;
                $plugin['Plugin']['email'] = $xml->child('plugin')->child('email')->children[0]->value;
                $plugin['Plugin']['website'] = $xml->child('plugin')->child('website')->children[0]->value;
                $plugin['Plugin']['description'] = $xml->child('plugin')->child('description')->children[0]->value;
            }else{
                 unset($notInstalled[$k]);
            }
        }
        $this->set('notInstalled', $notInstalled);
        
        $available = Set::reverse(new Xml('http://forum.brominefoundation.org/feed.php?f=9'));
        $this->set('available', $available);
	}
    
    function install($plugin){
        if(($output = $this->requestAction("$plugin/install/install")) === true ){
            $data['Plugin']['id'] = null;
            $data['Plugin']['name'] = $plugin;
            $data['Plugin']['activated'] = 1;
            if(!$this->Plugin->save($data)){
                $output = 'The plugin installed correctly, but Bromine couldn\'t insert the plugin in the database.';
            }
            //Set relevant ACL entries
        }
        $this->set('output', $output);
    }
    
    function uninstall($plugin_id){
        $plugin = $this->Plugin->findById($plugin_id);
        $plugin = $plugin['Plugin']['name'];
        
        if(($output = $this->requestAction("$plugin/install/uninstall")) === true ){
            //!$this->deleteDirectory(ROOT.DS.'app'.DS.'plugins'.DS.$plugin) || 
            if(!$this->Plugin->del($plugin_id)){
                $output = 'Bromine couldn\'t remove the plugin';
            }    
            //Remove relevant ACL entries
        }
        $this->set('output', $output);
    }
    
    function activate($plugin_id){
        $data['Plugin']['id'] = $plugin_id;
        $data['Plugin']['activated'] = 1;
        if(!$this->Plugin->save($data)){
            $this->Session->setFlash('There was an error, try again');
        }
        $this->redirect(array('action'=>'index'));        
    }
    
    function deactivate($plugin_id){
        $data['Plugin']['id'] = $plugin_id;
        $data['Plugin']['activated'] = 0;
        if(!$this->Plugin->save($data)){
            $this->Session->setFlash('There was an error, try again');
        }
        $this->redirect(array('action'=>'index'));        
    }
    
    private function deleteDirectory($dir) {
        if (!file_exists($dir)) return true;
        if (!is_dir($dir)) return unlink($dir);
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') continue;
            if (!$this->deleteDirectory($dir.DIRECTORY_SEPARATOR.$item)) return false;
        }
        return rmdir($dir);
    }   

}
