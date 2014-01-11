<?php $um = $this->um //UserManager ?>
<?php if($um->getKagRegStage() == \ServerBrowser\KAGREGSTAGE_NONAME): ?>

<div class="container">
  <center>
  <h2>Register your KAG account</h2>
  <div class="row">
    <div class="span4 offset4"><p>The registration process is quick and easy. To begin, enter your KAG username below and click submit.</p></div>
  </div>  
  <div class="row">
    <div class="span4 offset4">
      <form class="form-inline" action="kagreg.php" method="POST">
        <label for="kagname">Kag2d.com Username: </label>
        <input type="text" name="kagname" id="kagname" />
        <input type="submit" name="register_kagname" value="Submit" />
      </form>
    </div>
    <?php if(isset($this->validationErrors['kagname'])):?>
    <div class="span4 alert alert-error">
      <?php echo $this->validationErrors['kagname']?>
    </div>
    <?php endif;?>
  </div>
  <p><span style="font-size:8px">* Warning: you can only register one KAG username with this account, ever.</span></p>
  </center>
</div>
<?php elseif($um->getKagRegStage() == \ServerBrowser\KAGREGSTAGE_WAIT_KEY && $um->checkKagRegKey() === false):?>
<h2>Complete your KAG account registration.</h2>
<div class="container">
  <div class="row">
    <div class="span4">
      1. Go to <a href="http://www.kag2d.com">Kag2d.com</a> and log in.
    </div>
    <div class="span8">
      <img src="images/kagreg0.png"/>
    </div>
  </div>
  <div class="row">
    <div class="span4">
      2. Copy and paste this code as your status: 
      <pre><?php echo $um->getUser()->getPlayerVerificationToken()?></pre>
    </div>
    <div class="span8">
      <img src="images/kagreg1.png"/>
    </div>
  </div>
  <div class="row">
    <div class="span4">
      3. <a href="kagreg.php">Click here.</a> If everything worked, you will be registered!
    </div>
  </div>
</div>
<?php elseif($um->getKagRegStage() == \ServerBrowser\KAGREGSTAGE_REGISTERED):?>
<h2>Your KAG account name is registered as <?php echo $um->getUser()->getPlayerKagName()?>.</h2>
<?php endif; ?>

