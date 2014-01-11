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

$response->setStatus('success', "User verified.");
$response->username = $username;
echo $response->getJson();