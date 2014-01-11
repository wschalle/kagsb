<?php

require_once 'bootstrap.php';

mustBeLoggedIn();
if(isset($_POST['register_kagname'])) {
  try {
    register_kagname($_POST['kagname']);
  } catch (Exception $e) {
    echo $e->getMessage();
    die;
  }
}
if($UserManager->getKagRegStage() == \ServerBrowser\KAGREGSTAGE_NOKEY)
  $UserManager->getUser()->generateToken();

$title = "KAG Server Browser: Register KAG Account";
$description = "KAG account registration page";

$contentFile = 'templates/t_kagreg.php';

Common\Template\Template::helper('templates/' . getConfig('site_template'), array(
  'title' => $title,
  'siteTitle' => g('siteTitle'),
  'description' => $description,
  'contentFile' => $contentFile,
  'validationErrors' => $validationErrors,
  'um' => $UserManager
));

function register_kagname($kagname) {
  global $validationErrors;
  if(!preg_match('/^[a-zA-Z0-9\-_]{1,20}$/', $kagname)) {
    $validationErrors['kagname'] = "You have entered an invalid KAG username. KAG usernames consist of the characters a-z, A-Z, 0-9, -, _, and are no more than 20 characters long.";
    return;
  }
  
  //check to see if the player is in the DB
  $em = \ServerBrowser\EM::getInstance();
  $player = $em->getRepository('Entities\Player')->findOneBy(array('name' => $kagname));
  
  if(!($player instanceof Entities\Player)) {
    //Check if the player exists
    $cli = new KAGClient\Client();
    $info = $cli->getPlayerInfo($kagname);
    if(isset($info['http_code']) && $info['http_code'] == 404) {
      $validationErrors['kagname'] = "That KAG username does not exist. Please check that you entered it correctly.";
      return;
    }
    //Create a new player record
    $player = new Entities\Player();
    $player->setName($kagname);
    $player->updateFromAPI();
    $em->persist($player);
    $em->flush();
  }

  g('UserManager')->registerKagName($kagname);
}