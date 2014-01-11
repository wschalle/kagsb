<?php
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>KAG Server List 0.2 by Garanis</title>
    <meta name="description" content="KAG Server List">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="assets/css/bootstrap-responsive.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/servers.css" />
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
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#"><span style="color: #496">&#9819;</span> KAG Server Browser 0.2 by Garanis</a>
          <div class="nav-collapse">
            <ul class="nav">
              <li class="active"><a href="servers.php">All Servers</a></li>
              <li class="dropdown">
                <a href="#"
                   class="dropdown-toggle"
                   data-toggle="dropdown">
                  Sort
                  <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                  <li><a href="servers.php?sort=playersDesc"># Players - Desc</a></li>
                  <li><a href="servers.php?sort=playersAsc"># Players - Asc</a></li>
                </ul>   
              </li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
    <div class="container">
      <center><h1>An error occurred!</h1></center>
      <center><p class="lead"><?php echo $errorMessage ?></p></center>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
  </body>
</html>