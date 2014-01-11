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
    <div class="navbar  navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="<?php echo absoluteUrl('')?>"><span style="color: #496">&#9819;</span> <?php echo $this->siteTitle?></a>
          <div class="nav-collapse">
            <ul class="nav">
              <?php if($this->serverListActive):?>
              <li class="active"><a href="#" onclick="resetOptions();return false;">All Servers</a></li>
              <li class="dropdown">
                <a href="#"
                   class="dropdown-toggle"
                   data-toggle="dropdown">
                  Sort
                  <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                  <li><a href="#" onclick="setSort(this);return false;" id="playersDesc"># Players - Desc</a></li>
                  <li><a href="#" onclick="setSort(this);return false;" id="playersAsc"># Players - Asc</a></li>
                </ul>   
              </li>
              <li id="options-toggle"><a href="#" data-toggle="collapse" data-target="#options" onclick="return false;">Options <i class="icon-plus icon-white icon-transparent"></i></a></li>
              <li><a href="#" onclick="loadServerList();return false;"><i class="icon-refresh icon-white"></i> Reload</a>
              <?php else:?>
              <li><a href="<?php echo absoluteUrl('')?>">Server List</a></li>
              <?php endif;?>
                <?php if(g('UserManager')->isLoggedIn()):?>
              <li><a href="account.php"><?php echo g('UserManager')->getUser()->getUsername()?></a></li>
              
              <li><a href="buddies.php">Buddies</a>
              <li<?php if($this->graphPage) echo ' class="active"';?>><a href="<?php echo absoluteUrl('graphs/')?>">KAG Stats</a></li>
                <li class="buddylist-link" style="margin-right:0">
                  <a data-toggle="collapse" data-target="#buddylist">
                    <i class="icon-white icon-user"></i>
                    <span id="navbar-online-buddy-count"></span>
                  </a>
                </li>
                <?php else:?>
              <li><a href="login.php">Log In</a></li><li><a href="register.php">Register</a></li>
                <?php endif;?>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
    <div id="options" class="collapse navbar-inverse navbar-fixed-top">
      <div class="navbar">
        <div class="navbar-inner">
          <div class="container">
            <a class="brand" href="#">Options: </a>
            <ul class="nav">
              <li><input id="option-gold" name="options[gold]" type="checkbox" onchange="setOption(this)"/><label for="option-gold">Gold Only</label></li>
              <li><input id="option-password" name="options[password]" type="checkbox" onchange="setOption(this)"/><label for="option-password">Passworded</label></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div id="buddylist" class="collapse" style="height: 0px;">
      <ul>
        <li>
          <a href="#" data-toggle="collapse" data-target="#online-buddies">Online</a>
          - <span id="online-buddy-count">0</span>
        </li>
        <ul id="online-buddies">
        </ul>
        <li>
          <a href="#" data-toggle="collapse" data-target="#offline-buddies">Offline</a>
          - <span id="offline-buddy-count">0</span>
        </li>
        <ul class="collapse" id="offline-buddies">
        </ul>        
      </ul>
    </div>
    <?php if(!empty($this->content)):?><?php echo $this->content?><?php endif;?>
    <?php if(!empty($this->contentFile)):?><?php include $this->contentFile?><?php endif;?>
    
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