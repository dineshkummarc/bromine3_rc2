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
class NewsController extends AppController {

	public $helpers = array('Html', 'Form', 'Cache');
	public $main_menu_id = -2;
	public $uses = array();
	public $cacheAction = "1 day";

	function index(){
        $this->pageTitle = 'Bromine news';
        App::import('Core',  'Xml'); 
        $news = Set::reverse(new Xml('http://www.brominefoundation.org/news/rss/ee7b5696476c8a1f598c78952f886e22'));
        $this->set('news',$news);
    }

}
