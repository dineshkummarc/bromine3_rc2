<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php echo $html->charset(); ?>
        <title>
            Bromine updater | <?php echo $title_for_layout; ?>
        </title>
        <?php
    		echo $html->css('/zipupdate/css/style');
            echo $html->css('/zipupdate/css/color');
    		echo $html->css('/zipupdate/css/content');
    		echo $scripts_for_layout;
    		echo $javascript->link('/zipupdate/js/prototype');
            echo $javascript->link('/zipupdate/js/scriptaculous');
        ?>

    </head>
    <body>
  <div id="main">
  <div id="links_container">
      <div id="links">
      </div>
      <div id="logo"><h1>Bromine updater</h1></div>
      <br style='clear:both;'/>
    </div>
    <div id="content">
      <div id="column2">
            <?php
                echo $html->link( 
                	$html->image("tango/32x32/actions/go-previous.png", array('title'=>'Back to Bromine')).'Back to Bromine', 
                	array('controller' => 'configs','action' => 'stateOfTheSystem', 'plugin'=>null),
                	array('escape' => false)
                );
            ?>
            <?php $session->flash('auth'); ?>
            <?php $session->flash(); ?>
        <?php echo $content_for_layout; ?>
      </div>
    </div>
    <div id="footer">
        Copyright &copy; 2007-2010 Bromine Team
        |
        <a href="http://bromine.seleniumhq.org">Bromine</a>
        | 
        <a href="http://forum.brominefoundation.org">Bromine Forum</a>
        | 
        <a href="http://www.dcarter.co.uk?ref=http://brominefoundation.org">Design by dcarter</a>
    </div>
    <div id="debug">
    <?php echo $cakeDebug; ?>
    </div>
  </div>
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
