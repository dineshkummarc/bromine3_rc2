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
<style>

    #tree, #tree ul { 
    	padding-left: 4px; 
    	list-style-type: none;
    	line-height: 20px;
	margin-top: 0;
    }
    #tree {
        padding: 0px;
        margin-left: -30px;
        margin-top: -8px;
    }
    #tree div{
        display: list-item;
    }
    
    #tree li, #tree div, #testcases li {
        list-style-type: none;
    }
    
    li.req a, div.tc a, div.tcs a, a.project {
        padding: 2px 0 0 18px;
    }
    
    a.project{
    	background: url(img/tango/16x16/mimetypes/text-html.png) no-repeat left top !important;
    }
    li.req a {
        white-space: nowrap;
    	background: url(img/tango/16x16/mimetypes/x-office-presentation.png) no-repeat left top !important;
    }
    div.tc{
        white-space: nowrap;
    }

    div.tc a {

    	background: url(img/tango/16x16/mimetypes/text-x-generic.png) no-repeat left top !important;
    	border: none;
    }
    div.tcs a {
    	background: url(img/tango/16x16/mimetypes/text-x-script.png) no-repeat left top !important;
    	border: none;
    }
    div a.del{
        background: url(img/tango/16x16/mimetypes/text-x-script.png) no-repeat left top !important;
        padding: 0px;
    }
    
    .dragspacer{
        
        display: block;
        float: left;
        width: 15px;
        height: 15px;
        
    }
    
    .dragspacer.draghandle{
    background: url(/img/side.png);
        border: 1px solid lightgrey;
    	cursor: move;
    }

    #tree li span.spacer, .pminus {
        display: block;
        float: left;
    	width: 15px;
    	height: 12px;
    	margin: 6px 3px 0 0;
    	cursor: pointer;
    }
    
    
    #tree li span.spacer.handle{
    	background: url(img/nolines_minus.gif) no-repeat left -4px;
    }

    #tree li.closed span.handle {
    	background: url(img/nolines_plus.gif) no-repeat left -4px;
    }
    #tree li.closed div, #tree li.closed li {
    	display: none;
    }

    .drop_hover{
        background: url(img/drag.png) no-repeat bottom left;
        background-position: 32px 16px;
        border: 1px solid lightgrey;
    }
    .hide{
        display: none !important;
    }
    .del{
        display: none;
    }
    ul .tc:hover > .del{
        display: inline;
    }
    #tree ul{
        border-left: 1px dashed lightgrey;
    }
    #tree > ul{
        border-left: none;
    }


  </style>
<div class="index">
<table>
<thead>
    <tr>
        <th class="left_col">
            Project structure
        </th>
        <th class="tctd" style="width: 100px; display: none;">
            Testcases
        </th>
        <th>
            Details
        </th>
    </tr>
    </thead>
    <tbody>
    <tr style='vertical-align: top;'>
    
        <td class="left_col" style='border-right: 1px solid lightgrey; padding-right: 30px;'>


            <ul>
                <li id="tree">
                <span class='pminus'></span>
                    <?php echo 
                        $ajax->link( 
                            $session->read('project_name'), 
                            array( 'controller' => 'projects', 'action' => 'testlabview', $session->read('project_id')), 
                            array( 'update' => 'Main', 'class'=>'project' ));
                    ?>
                    <ul>
                        <?php echo $tree->testlabshow('Requirement/name', $data); ?>
                    </ul>
                </li>
            </ul>
        </td>
        
        <td>
            <div id='Main'></div>
        </td>
    </tr>
    </tbody>
</table>
</div>
<div id="log"></div>
<script>

  //tree.setSortable();

  function toggle_folder(event) {
    var element = event.element().ancestors().first();
    if(element.hasClassName('closed')) {
      element.removeClassName('closed');
    } else {
      element.addClassName('closed');
    }
  }
  
  Event.observe(window, "load", function(){
    $$('.handle').each(function(element){
      Event.observe(element, 'click', toggle_folder);
    });
  });
</script>
