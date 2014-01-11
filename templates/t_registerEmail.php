<?php ?>
<style type="text/css">
  .row {
    text-align: center;
  }
</style>
<div class="container">
  <form action="registerEmail.php" method="POST">
    <div class="row">
      <div class="span4 offset4 control-group<?php if(isset($this->validationErrors['email'])) echo ' error'?>">
        <label for="email">Email Address:</label>
        <input type="text" name="email" id="email" 
               value="<?php echo $_POST['email']?>" />
      </div>   
      <?php if(isset($this->validationErrors['email'])):?>
      <div class="span4">
        <div class="alert alert-error">
          <?php echo $this->validationErrors['email']?>
        </div>
      </div>
      <?php endif;?>     
    </div>
    <div class="row">
      <div class="span4 offset4 control-group<?php if(isset($this->validationErrors['email-confirm'])) echo ' error'?>">
        <label for="email-confirm">Confirm Email:</label>
        <input type="text" name="email-confirm" id="email-confirm"  
               value="<?php echo $_POST['email-confirm']?>"/>
      </div>   
      <?php if(isset($this->validationErrors['email-confirm'])):?>
      <div class="span4">
        <div class="alert alert-error">
          <?php echo $this->validationErrors['email-confirm']?>
        </div>
      </div>
      <?php endif;?>     
    </div>
    
    <div class="row">
      <div class="span4 offset4 control-group<?php if(isset($this->validationErrors['email-confirm'])) echo ' error'?>">
        <input class="btn btn-primary" name="submit" type="submit" value="Done!" />
      </div>
    </div>
    

  </form>
</div>
