<?php $buddies = $this->buddies?>
<?php if(empty($buddies)):?>
<h2>You have no buddies.</h2>
<?php else:?>
<ul>
<?php foreach($buddies as $buddy):?>
<?php if(!($buddy instanceof Entities\Buddy)) continue;?>
  <li><a href="#" onclick="removeBuddy('<?php echo $buddy->getPlayer()->getName()?>');return false;"><i class="icon-remove"></i></a>
<?php echo $buddy->getPlayer()->getName()?>
  <?php if($buddy->isOnline()):?> - 
    <span style="color: #2a1;">Online</span> 
    <a href="<?php echo $buddy->getGameLink()?>">Join their server</a>
  <?php else:?> - 
    <span style="color: #F12;">Offline</span>
  <?php endif;?></li>
<?php endforeach;?>
</ul>
<?php endif; ?>
<script>
  function removeBuddy(name) {
    post_to_url('buddies.php', {removebuddy:name});
  }
</script>
<form action="buddies.php" method="POST">
  <input type="text" name="playername" />
  <?php if(isset($this->validationErrors['playername'])):?>
  <div class="error"><?php echo $this->validationErrors['playername']?></div>
  <?php endif;?>
  <input type="submit" value="Add Buddy" />
</form>