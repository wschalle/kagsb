<?php
/** Site Template **/
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo $this->title?></title>
    <meta name="description" content="<?php echo $this->description?>">
    <link rel="stylesheet" href="<?php echo absoluteUrl('assets/css/bootstrap.min.css')?>" />
    <style>
    </style>
    <!--<link href="<?php echo absoluteUrl('assets/css/bootstrap-responsive.min.css')?>" rel="stylesheet">-->
    <link rel="stylesheet" href="<?php echo absoluteUrl('css/site.css')?>" />
    <link rel="stylesheet" href="<?php echo absoluteUrl('css/servers.css')?>" />
    <link rel="stylesheet" href="<?php echo absoluteUrl('css/buddylist.css')?>" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo absoluteUrl('scripts/config.js.php')?>"></script>
    <?php if(!empty($this->preBodyScript)):?><script type="text/javascript">
      <?php echo $this->preBodyScript?>
    </script><?php endif;?>
    <?php if(!empty($this->preBodyScriptFile)):?>
    <?php if(is_string($this->preBodyScriptFile)):?>
    <script type="text/javascript" src="<?php echo $this->preBodyScriptFile?>"></script>
    <?php elseif(is_array($this->preBodyScriptFile)):?>
    <?php foreach($this->preBodyScriptFile as $scriptFile):?>
    <script type="text/javascript" src="<?php echo $scriptFile?>"></script>
    <?php endforeach;?>
    <?php endif;?>
    <?php endif;?>
    <script type="text/javascript">
  
      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-32903428-1']);
      _gaq.push(['_trackPageview']);
  
      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();
  
    </script></head>
  <body>    
    <div class="container">
      <center>
        <a href="<?php echo absoluteUrl('')?>">
          <img border="0" src="<?php echo absoluteUrl('images/maintenance.png')?>" />
        </a>
      </center>
    </div>
    
    <script src="<?php echo absoluteUrl('assets/js/bootstrap.min.js')?>"></script>
    <script src="<?php echo absoluteUrl('scripts/util.js')?>"></script>
    <script src="<?php echo absoluteUrl('scripts/site.js')?>"></script>
    <script src="<?php echo absoluteUrl('scripts/socket.io.js')?>"></script>
    <script src="<?php echo absoluteUrl('scripts/buddylist.js')?>"></script>
    <?php if(!empty($this->postBodyScript)):?><script type="text/javascript">
      <?php echo $this->postBodyScript?>
    </script><?php endif;?>
    <?php if(!empty($this->postBodyScriptFile)):?>
    <?php if(is_string($this->postBodyScriptFile)):?>
    <script type="text/javascript" src="<?php echo $this->postBodyScriptFile?>"></script>
    <?php elseif(is_array($this->postBodyScriptFile)):?>
    <?php foreach($this->postBodyScriptFile as $scriptFile):?>
    <script type="text/javascript" src="<?php echo $scriptFile?>"></script>
    <?php endforeach;?>
    <?php endif;?>
    <?php endif;?>
  </body>
</html>