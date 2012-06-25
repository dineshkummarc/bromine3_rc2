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
<?php

class ZipupdateController extends ZipupdateAppController {
    //TODO: checksum on zip
    //TODO: backup before update
    public $uses = array();
    public $errors = array();

    function index() {
        App::import('Core',  'Xml');
        $releases = Set::reverse(new Xml('http://brominefoundation.org/releases.xml'));
        $this->set('releases', $releases);
        $this->set('zip_module', extension_loaded('zip'));
        $this->set('writeable_tmp', is_writeable(TMP));
    }

    function initiateUpdate($id) {
        $url = "http://brominefoundation.org/releases/$id.zip";

        if (file_exists(TMP."$id.zip") || $this->getZip($url, TMP . "$id.zip")) { // Download relased zip
            if ($this->extractZip(TMP . "$id.zip", TMP.$id)) { // Extract zip*/

                if (file_exists(TMP . $id . DS . "deleteList.txt")) {
                    $deleteItems = file(TMP . $id . DS ."deleteList.txt"); // Get delete list

                    foreach($deleteItems as $key => &$deleteItem) { //Remove files from deleteList that does not exist (no reason trying to delete them again)
                        $deleteItem = trim($deleteItem);
                        if(file_exists('..'.DS.'..'.DS.$deleteItem) === false) {
                            unset($deleteItems[$key]);
                        }
                    }

                    $deleteItems = $this->checkPermissionsList($deleteItems);

                }
                $modItems = $this->getTree(TMP . $id);
                $modItems = $this->array_flatten($modItems);

                foreach ($modItems as &$modItem) {
                    $modItem = str_replace(TMP . $id, '', $modItem);
                }

                $modItems = $this->checkPermissionsList($modItems);
                $items = array_merge($deleteItems, $modItems);


                foreach($items as $file => $writeable) {
                    if($writeable === false) {
                        $this->errors['file'][] = "$file is not writeable";
                    }
                }
            }
        } else {
            $this->errors['download'] = "Could not download patch from '$url' to '".TMP."$id.zip'. Please check access to the internet. If the problem persists, please notify admin@brominefoundation.org";
        }

        $this->set('id', $id);
        $this->set('errors', $this->errors);
    }

    function doUpdate($id) {
        $this->recurse_copy(TMP.$id, '..'.DS.'..'.DS);
        $this->applySQL($id);
        if(file_exists(TMP.$id.DS.'deleteList.txt')){
            $this->delete(file(TMP.$id.DS.'deleteList.txt'));
        }
        
        if(file_exists(APP.'../postInstructions.php')){
            $this->set('postInstructions', $this->postInstructions(APP.'../postInstructions.php'));
        }

        $this->delete(array("app/tmp/$id.zip", "app/tmp/$id"));
        $this->update_version($id);
        $this->set('errors', $this->errors);
    }

    private function update_version($version) {
        $this->loadModel('Config');
        $data = $this->Config->findByName('version');
        $data['Config']['value'] = $version;
        if($this->Config->save($data) === false) {
            $this->errors['update_version'][] = 'Could not update your version number. If this is the only error the rest of the update went well, just open your "configs" table in your database and change "version" to '.$id;
        }
    }

    private function array_flatten($array) {
        $objTmp = (object) array('aFlat' => array());
        array_walk_recursive($array, create_function('&$v, $k, &$t', '$t->aFlat[] = $v;'), $objTmp); //Crazy stuff from php.net
        return $objTmp->aFlat;
    }

    private function checkPermissionsList($items) {
        $writeable = array();
        foreach ($items as $item) {
            $count = 0;
            while (!file_exists('..' . DS . '..' . DS . $item) && $count < 2000) {
                $item = substr($item, 0, strrpos($item, DS));
                $count++;
            }
            $writeable[$item] = is_writable('..' . DS . '..' . DS . $item);
        }
        return $writeable;
    }

    private function getTree($path) {
        $dir_handle = @opendir($path) or die($this->errors['file'][] = "Could not open $path. Please ensure correct file permissions are set and file exists.");

        if (substr($path, strlen($path) - 1) != DS) {
            $path .= DS;
        }
        while (false !== ($file = readdir($dir_handle))) {
            if ($file != "." && $file != "..") {

                if (is_dir($path . DS . $file)) {
                    //Adds subfolders to array
                    $list[] = $this->getTree($path . $file);
                } else {
                    //Adds file to array
                    $list[] = $path . $file;
                }
            }
        }
        //closing the handle
        closedir($dir_handle);
        if (!isset($list)) {
            $list[] = $path;
        }
        return $list;
    }

    private function applySQL($id) {
        $path = TMP.$id.DS.'app'.DS.'config'.DS.'sql'.DS.'versions';
        if (file_exists($path)) {
            $files = scandir($path);
            unset($files[0]);
            unset($files[1]);
            natsort($files);
            $this->loadModel('Config');
            foreach ($files as $file) {
                $query = trim(file_get_contents($path.DS.$file));
                $this->multiple_query($query);
            }
        }
    }

    private function multiple_query($q) {
        $tok = strtok($q, ";");
        while ($tok) {
            if($this->Config->query($tok) === false) {
                $this->errors['mysql'][] = mysql_error();
            }
            $tok = strtok(";");
        }
    }
    
    

    private function getZip($url, $dest) {
        return copy($url, $dest);
    }

    private function extractZip($file, $dest) {
        $zip = new ZipArchive;

        if ($zip->open($file) == false) {
            $this->errors['extract'][] = "Could not open zip file $file. Please verify file integrity. Delete if corrupted and retry update, a new will be downloaded for you";
            return false;
        }
        if (file_exists($dest)) {
            if(is_dir($dest)){
                @$this->rrmdir($dest);
            }else{
                @unlink($dest);
            }	
        }
        $this->delete(array($dest));
        if(@mkdir($dest) == false) {
            $this->errors['extract'][] = "Could not create destination folder: $dest. Most likely the directory already exists. Please delete $dest, ensure that file permissions are set correctly, reload this page and retry.";
            return false;
        }
        if($zip->extractTo($dest) == false) {
            $this->errors['extract'][] = "Could not extract $file to $dest. Most likely the directory already exists. Please delete $dest, ensure that file permissions are set correctly, reload this page and retry.";
            return false;
        }

        $zip->close();

        return true;
    }

    private function recurse_copy($src, $dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ( $file = readdir($dir))) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if (is_dir($src . DS . $file)) {
                    $this->recurse_copy($src . DS . $file, $dst . DS . $file);
                } else {
                    if(copy($src . DS . $file, $dst . DS . $file) === false) {
                        $this->errors['copy'][] = 'Could not copy '.$src . DS . $file.' to '.$dst . DS . $file;
                    }
                }
            }
        }
        closedir($dir);
    }

    private function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . DS . $object) == "dir")
                        $this->rrmdir($dir . DS . $object); else
                    if(unlink($dir . DS . $object) === false) {
                        $this->errors['delete'][] = "Could not delete ".$dir . DS . $object;
                    }
                }
            }
            reset($objects);
            if(rmdir($dir) === false) {
                $this->errors['delete'][] = "Could not delete $dir";
            }

        }
    }

    private function delete($items) {
        foreach ($items as $item) {
            if (stripos($item, '..') !== false) {
                $this->Session->setFlash('Delete list contains ".." this is not permitted. Breaking');
                break;
            }
            $item = '..' . DS . '..' . DS . trim($item);
            if(file_exists($item)) {
                if (is_dir($item)) {
                    $this->rrmdir($item);
                } else {
                    if(unlink($item) === false) {
                        $this->errors['delete'][] = "Could not delete $item";
                    }
                }
            }
        }
    }
    
    private function postInstructions($filename) {
        if(file_exists($filename)) {
            exec('php "'.$filename.'"', $output);
            unlink($filename);
            return $output;
            
        }
        return false;
    }

}

