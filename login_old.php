<?php
require_once 'bootstrap.php';

$validationErrors = array();

if(isset($_POST['submit'])) {
  do_login($validationErrors);
}
else
  displayForm();

if(empty($validationErrors) && $UserManager->isLoggedIn())
  header('Location: index.php');
else
  displayForm($validationErrors);

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

function do_login(&$validationErrors) {
  if(empty($_POST['username']))
    $validationErrors['username'] = "You must enter your username.";
  if(empty($_POST['password']))
    $validationErrors['password'] = "You must enter your password.";
  
  if(!empty($_POST['username']) && preg_match('/^[a-zA-Z0-9\-_]{3,20}$/', $_POST['username']) == 0 )
    $validationErrors['username'] = "You must enter a username between 3 and 20 characters, containing only a-z, A-Z, 0-9, - and _.";
  
  if(empty($validationErrors) && !g('UserManager')->doLogin($_POST['username'], $_POST['password']))
    $validationErrors['general'] = "You have entered an incorrect username or password. Please check the inputs and try again.";
}