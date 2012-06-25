<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">    
  <head>        
    <?php echo $html->charset(); ?>        
    <title>            
      <?php echo $title_for_layout; ?>        
    </title>        
    <?php
		echo $html->meta('icon');
		echo $html->css('green/style');
        echo $html->css('green/color');
		echo $html->css('green/content');
		echo $html->css('green/prettify');
		echo $html->css('green/menu');
		echo $scripts_for_layout;
		echo $javascript->link('prototype');
		echo $javascript->link('scriptaculous');
		echo $javascript->link('popup');
		echo $javascript->link('prettify/prettify');
        echo $javascript->link('sortable_tree');
        echo $javascript->link('urlparser');      
    ?>
            <script type='text/javascript'>
        Ajax.Responders.register({
        	onCreate: function(request) {
        		if($('notification') && Ajax.activeRequestCount > 0){
            		    if(request.container.success == 'Main'){
                            $('notification').title = request.url;
                		    $('helpbutton').href = 'help/show' + request.url;
                		    //alert($('helpbutton').href);
                            //console.log(request);
                            changeUrl(request.url);
                            if(request.options.noscroll != true){
                                Effect.ScrollTo('main',{duration: 0.25});
                            }
                            Effect.Appear('notification',{duration: 0.25, queue: 'end'});
            		    }
        			}
        	},
        	onComplete: function(request) {
                //console.log(request);
        		if($('notification') && Ajax.activeRequestCount == 0){
                    $('notification').title = '';
        			Effect.Fade('notification',{duration: 0.25, queue: 'end'});
        		}
        	}
        });
        observeUrl(0);
        function observeUrl(location){
            if(location != window.location.toString() && Ajax.activeRequestCount == 0){
                var anchor = getAnchor();
                if(anchor!=false){
                    new Ajax.Updater('Main',anchor,{evalScripts: true});
                }
            }
            oldlocation = window.location.toString();
            window.setTimeout("observeUrl(oldlocation)",200);
        }
        
    </script>
    
    <script type='text/javascript'>
        FF = new Ajax.PeriodicalUpdater('jobs', "<?php echo $html->url(array('controller'=>'jobs', 'action'=>'index')); ?>", {
          method: 'get', frequency: 2, decay: 1, evalScripts: true
        });
        FF.stop();
        FF.running = 0;
        
        function toggleJobs(FF){
            Effect.toggle("jobs-container","blind",{ duration: 0.5 });
            
            if(FF.running == 1){
                FF.stop();
                FF.running = 0;
            }else{
                FF.start();
                FF.running = 1;
            }
            return true;
            
        }
    </script>
             
  </head>    
  <body>  
    <div id="main" class="wrapper">   
      <div id="links_container">      
        <div id="links">
          <!-- #links is bad naming-->        
<?php
            if(isset($realname) && isset($username)){               
                echo "<div class='br-logged-in-as'>" . __("Logged in as", true) . " $realname ($username)</div>";
            }
            
            if(!empty($userprojects) && $main_menu_id != -2){
                echo '<div class="br-projects-form-box br-form-box">';
                echo $form->create('Project', array('url'=>array('controller'=>'projects','action' => 'select', 'plugin' => null)));
                foreach($userprojects as $project){
                    $options[$project['id']] = $project['name']; 
                }
                echo $form->input('project_id',array('options' => $options, 'selected'=>$session->read('project_id'), 'onchange'=>'submit()'));
                echo $form->end();
                echo '</div>';
            }
            if(!empty($sites)) {
                echo '<div class="br-sites-form-box br-form-box">';
                echo $form->create('Site', array('action' => 'select'));
                echo $form->hidden('anchor',array('id'=>'selectanchor'));
                echo $form->input('site_id',array('selected'=>$session->read('site_id'), 'onchange'=>'
                $("selectanchor").value = getAnchor();
                submit();
                '));
                echo $form->end();
                echo '</div>';
            }
            //echo $Auth->user('id');
                  ?>               
        </div>
        <!-- / #links -->      
        <div id="logo"><h1>Bromine 3 RC2</h1>            
          <?php echo $html->image('ajax-loader.gif',array('id'=>'notification', 'style'=>'display: none;'));?>      
        </div>      <br style='clear:both;'/>    
      </div>    
      <div id="menu">        
        <!-- **** INSERT NAVIGATION ITEMS HERE (use id="selected" to identify the page you're on **** -->                 
<?php
            if(!empty($Menu)){
                echo $tree->menustart($Menu);
            } 
                ?> 
        <div id="action_buttons">
<?php
          
            echo $html->link( 
            	$html->image("tango/32x32/actions/system-log-out.png", array('title'=>'Log out')), 
            	array('controller' => 'users','action' => 'logout','plugin'=>null), 
            	array('escape' => false,'style'=>'float: right;'),
            	"Are you sure you want to log out?"
            	
            );
            if($main_menu_id == -1){
                echo $html->aclLink( 
                	$html->image("tango/32x32/categories/preferences-system.png", array('title'=>'Control panel')), 
                	array('controller' => 'news','action' => 'index','plugin'=>null), 
                	array('escape' => false,'style'=>'float: right;')
                );
            }elseif($main_menu_id == -2){
                echo $html->aclLink( 
                	$html->image("tango/32x32/places/user-desktop.png", array('title'=>'Workspace')), 
                	array('controller' => 'testlabs#/projects/testlabview','plugin'=>null), 
                	array('escape' => false,'style'=>'float: right;')
                );
            }
            if(isset($helptxt)){
                echo $html->link( 
                	$html->image("tango/32x32/apps/help-browser.png", array('title'=>'Click for help')), 
                	'/help/show/' . $helptxt, 
                	array('escape' => false,'style'=>'float: right;','onclick'=>'return Popup.open({url:this.href});', 'id' => 'helpbutton'),
                	null
                	
                );
            }
            echo "<a style='cursor: pointer; float: right;' onclick='toggleJobs(FF);'>";
            echo $html->image("tango/32x32/actions/edit-paste.png", array('title'=>'Click to view the test que'));
            echo "</a>";


            /*
            if(count(split('/', $this->here))>2){
                $url = $this->here;
            }else{
                $url = $this->here.'/'.$this->action;
            }
            $path = 'http://'.$_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'].$url."/user:".$session->read('Auth.User.name').'/password:'.$user_password;
            $path .= ($session->read('project_id') ? '/project:'.$session->read('project_id') : '');
            */    
            
                ?>                 
        <!--a style='float: right; cursor: pointer;' onclick="$('directlink').update('<?php //echo $path; ?>'+(getAnchor() ? '#'+getAnchor() : '')); Effect.toggle('directlink','blind');" >
                <?php 
                    //echo $html->image("tango/32x32/places/start-here.png", array('title'=>'Direct link'));
                ?>
                </a-->    
         </div> 
      </div>
      
      <!--div id='directlink' style='float: right; display: none; padding-right: 20px;'></div-->
        <div id='messages'>
                            
            <?php $session->flash('auth'); ?>                
            <?php $session->flash(); ?>           
          </div>
      <div id="content" style='clear: both;'>
              
        <div id="column1">
        </div>        
        <div id="column2"> 
        <div id='jobs-container' style='display: none; width: 450px; border: 1px solid black; z-index: 10; position: absolute; right: 30px; top: 120px; background: white;'>
            <div id='output' style=' padding: 10px;'>
            </div>
            <div id='jobs' style=' padding: 10px;'>
            </div>
        <script type='text/javascript'>
            new Ajax.Updater('jobs', "<?php echo $html->url(array('controller'=>'jobs', 'action'=>'index')); ?>");
        </script>
      </div>           
                      
          <?php echo $content_for_layout; ?>       
        </div>    
      </div>  
      <div class="push"></div>
      </div> <!-- wrapper end -->  
      
      <div id="footer">        Copyright &copy; 2007-2010 Bromine Team         |        
        <a href="http://brominefoundation.org/?utm_source=Bromine&utm_medium=footer&utm_campaign=link">Bromine Website</a>
        |
        <a href="http://bromine.seleniumhq.org">Bromine @ SeleniumHQ</a>
        |           
        <a href="http://forum.brominefoundation.org?utm_source=Bromine&utm_medium=footer&utm_campaign=link" target='_blank'>Bromine Forum</a>        |
        <a href="http://wiki.brominefoundation.org?utm_source=Bromine&utm_medium=footer&utm_campaign=link" target='_blank'>Bromine Wiki</a>        |          
        <a href="http://www.dcarter.co.uk?ref=http://brominefoundation.org">Design by dcarter</a>        |          
<?php 
        if (!isset($register)){
            echo $html->link("Please Register", array('controller' => 'configs','action' => 'register'), array('class' => 'redlink'));
        }else{
            echo $register;
        } 
        ?> |         
        <?php
            if (isset($enableGA) && $enableGA  == false){
                echo $html->link("Please enable statistics", array('controller' => 'configs','action' => 'userStatistics'), array('class' => 'redlink')).' | ';
            }
            
            if(isset($version)){
                echo "revision: $version";
            } 
        ?>         |         
        <?php echo $html->link("Got bugs?", "http://brominefoundation.org/pages/bugs", array('target' => '_blank')); ?>     
      </div>    
      <div id="debug">    
        <?php echo $cakeDebug; ?>    
      </div>  
    
    <?php if (isset($enableGA) && $enableGA == true):?>        
<script type="text/javascript">
        var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
        document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
        </script>        
<script type="text/javascript">
        try {
        var pageTracker = _gat._getTracker("UA-12938086-1");
        pageTracker._trackPageview();
        } catch(err) {}</script>    
    <?php endif; ?>
    
    <script type="text/javascript">
      var uservoiceOptions = {
        key: 'bromine',
        host: 'bromine.uservoice.com', 
        forum: '31018',
        alignment: 'right',
        background_color:'#95C359', 
        text_color: 'white',
        hover_color: '#06c',
        lang: 'en',
        showTab: true
      };
      function _loadUserVoice() {
        var s = document.createElement('script');
        s.src = ("https:" == document.location.protocol ? "https://" : "http://") + "uservoice.com/javascripts/widgets/tab.js";
        document.getElementsByTagName('head')[0].appendChild(s);
      }
      _loadSuper = window.onload;
      window.onload = (typeof window.onload != 'function') ? _loadUserVoice : function() { _loadSuper(); _loadUserVoice(); };
    </script>
  </body>
</html>