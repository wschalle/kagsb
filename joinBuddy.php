<?php

require_once 'bootstrap.php';


if(!$UserManager->isLoggedIn())
  die('You must be logged in to use this feature.');

if(empty($_GET['kagname'])) {
  die('You must specify a KAG player name.');
}

$player = ServerBrowser\EM::getInstance()->getRepository('Entities\Player')->findOneBy(array('name' => $_GET['kagname']));

if(empty($player))
  die('Player not found.');

$buddy = $UserManager->getUser()->getBuddy($player);

if(empty($buddy))
  die('That player is not your buddy.');

if(!$buddy->isOnline())
  die('That player is no longer online.');

$buddy->redirectToGame();
