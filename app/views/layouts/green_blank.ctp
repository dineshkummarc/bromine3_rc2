<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php echo $html->charset(); ?>
        <title>
            Bromine: an open source QA tool | <?php echo $title_for_layout; ?>
        </title>
        <?php
    		echo $html->meta('icon');
    		echo $html->css('green/style');
            echo $html->css('green/color');
    		echo $html->css('green/content');
    		echo $html->css('green/prettify');
    		echo $scripts_for_layout;
    		echo $javascript->link('prototype');
    		echo $javascript->link('popup');
    		echo $javascript->link('prettify/prettify');
            echo $javascript->link('scriptaculous');
            //echo $javascript->link('sortable_tree');
        ?>

    </head>
    <body>
  
  <div id="main" class="wrapper"> 
  <div id="links_container">
      <div id="links">
      </div>
      <div id="logo"><h1>Bromine 3 RC2</h1><?php echo $html->image('ajax-loader.gif',array('id'=>'notification', 'style'=>'display: none;'));?></div>
      <br style='clear:both;'/>
    </div>
    <div id="content">
      <div id="column2">    
            <?php
                //pr($session);
                //pr($_SESSION);
            ?>
            <?php $session->flash('auth'); ?>
            <?php $session->flash(); ?>
        <?php echo $content_for_layout; ?>
      </div>
    </div>
    <div class="push"></div>
    </div> <!-- wrapper end -->  
    <div id="footer">
        Copyright &copy; 2007-2010 Bromine Team
        |
        <a href="http://brominefoundation.org/?utm_source=Bromine&utm_medium=footer&utm_campaign=link">Bromine Website</a>
        |
        <a href="http://bromine.seleniumhq.org">Bromine @ SeleniumHQ</a>
        | 
        <a href="http://forum.brominefoundation.org?utm_source=Bromine&utm_medium=footer&utm_campaign=link">Bromine Forum</a>
        |
        <a href="http://wiki.brominefoundation.org?utm_source=Bromine&utm_medium=footer&utm_campaign=link" target='_blank'>Bromine Wiki</a>          
        | 
        <a href="http://www.dcarter.co.uk?ref=http://brominefoundation.org">Design by dcarter</a>
    </div>
    <div id="debug">
    <?php echo $cakeDebug; ?>
    </div>
  
    <?php if ($enableGA == true):?>
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
    
    <script type='text/javascript'>

      Ajax.Responders.register({
      	onCreate: function(request) {
      		if($('notification') && Ajax.activeRequestCount > 0){
          		    $('notification').title = request.url;
          			Effect.Appear('notification',{duration: 0.25, queue: 'end'});
      			}
      	},
      	onComplete: function() {
      		if($('notification') && Ajax.activeRequestCount == 0){
                  $('notification').title = '';
      			Effect.Fade('notification',{duration: 0.25, queue: 'end'});
      		}
      	}
      });
      </script>    
</body>
</html>
