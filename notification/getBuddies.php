<?php

require_once '../bootstrap.php';
require 'sbapi.php';

$response = new Response();
if(empty($_SESSION['loggedInUser'])) {
  $response->setStatus('error', 'User is not logged in.');
  $response->sendJson();
}

$username = $_SESSION['loggedInUser']->getUsername();

$em = ServerBrowser\EM::getInstance();
$user = $em->getRepository('Entities\User')->findOneBy(array('username' => $username));

if(!$user) {
  $response->setStatus('error', "User not found: " . $username);
  $response->sendJson();
}

$buddies = $user->getBuddies();
$buddylist = array();
foreach($buddies as $buddy) {
  $player = $buddy->getPlayer();
  $name = $player->getName();
  $buddylist[] = $name;
}
$response->buddylist = $buddylist;

$response->setStatus('success', "Successfully retrieved buddy list.");
echo $response->getJson();