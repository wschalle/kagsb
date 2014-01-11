<?php $um = g('UserManager');?>
<div class="container">
  <h1>My Account</h1>
  <div class="row">
    <div class="span10 offset1">
      <p class="lead">You are logged in as <?php echo $um->getUser()->getUsername()?>. <a href="logout.php">Log Out</a></p>
    </div>
  </div>
  <div class="row">
    <div class="span3 offset1">
      <h2>Account Functions</h2>
      <ul class="nav nav-list">
        <li>
          <a href="kagreg.php">Register KAG account</a>
          <a href="buddies.php">Manage Buddy List</a>
          <a href="deleteaccount.php">Delete Account</a>
        </li>
      </ul>
    </div>
  </div>  
</div>