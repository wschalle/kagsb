<div class="container">
  <center>
    <h1>Log In</h1>
    <form action="login.php" method="POST">
      <?php if(isset($this->validationErrors['general'])):?>
      <div class="row">
        <div class="span4 offset4">
          <div class="alert alert-error">
            <?php echo $this->validationErrors['general']?>
          </div>
        </div>
      </div>
      <?php endif;?>
      <div class="row">
        <div class="span4 offset4">
          <label for="username">Username: </label>
          <input type="text" id="username" name="username"/>
        </div>
        <?php if(isset($this->validationErrors['username'])):?>
        <div class="span4">
          <div class="alert alert-error">
            <?php echo $this->validationErrors['username']?>
          </div>
        </div>
        <?php endif;?>
      </div>
      <div class="row">
        <div class="span4 offset4">
          <label for="password">Password: </label>
          <input type="password" id="password" name="password"/>
        </div>
        <?php if(isset($this->validationErrors['password'])):?>
        <div class="span4">
          <div class="alert alert-error">
            <?php echo $this->validationErrors['password']?>
          </div>
        </div>
        <?php endif;?>
      </div>
      <div class="row">
        <div class="span4 offset4">
          <input class="btn btn-primary" type="submit" name="submit" value="Log in!" />
        </div>
      </div>
    </form>
  </center>
</div>
  