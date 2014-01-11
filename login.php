<?php
require_once 'bootstrap.php';

if(isset($_GET['stage']) && $_GET['stage'] == 'loginSuccess')
  validateSSOToken();

$validationErrors = array();

if($UserManager->isLoggedIn()) {
  header('Location: index.php');
  exit;
}

displayForm();

function displayForm($validationErrors = array()) {
  $title = "KAG Server Browser: Log in";
  $description = "Login page";

  $contentFile = 'templates/t_login.php';

  Common\Template\Template::helper('templates/' . getConfig('site_template'), array(
    'title' => $title,
    'siteTitle' => g('siteTitle'),
    'description' => $description,
    'contentFile' => $contentFile,
    'validationErrors' => $validationErrors
  ));
  exit;
}

function do_login($username) {
    g('UserManager')->doLogin($username);
  
  }

function validateSSOToken() {
  if(!isset($_GET['userToken']) || !isset($_GET['uname']))
    return false;
    
    $cli = new KAGClient\Client();
    $info = $cli->validateSSOToken($_GET['uname'], $_GET['userToken']);
    if($info === true)
      do_login($_GET['uname']);
}