<?php
require_once 'bootstrap.php';

$validInput = false;
$validationErrors = array();
$postVars = getPostVars();

if(isset($_POST['submit']))
  $validInput = validateForm($postVars, $validationErrors);
else {
  displayForm();
  exit;
}

$validUserData = false;
if($validInput)
  $validUserData = checkUserData($postVars, $validationErrors);

if($validUserData)
  updateEmail($postVars);
else
  displayForm($validationErrors);

function displayForm($validationErrors = null) {  
  $title = "KAG Server Browser: Update Email Address";
  $description = "Create an account";

  $contentFile = 'templates/t_registerEmail.php';

  Common\Template\Template::helper('templates/' . getConfig('site_template'), array(
    'title' => $title,
    'siteTitle' => g('siteTitle'),
    'description' => $description,
    'contentFile' => $contentFile,
    'validationErrors' => $validationErrors
  ));
}

function updateEmail($postVars) {
  $user = g('UserManager')->getUser();
  $user->setEmail($postVars['email']);
  \ServerBrowser\EM::getInstance()->persist($user);
  \ServerBrowser\EM::getInstance()->flush();
  
  $title = "KAG Server Browser: User Registration Complete!";
  $description = "You have successfully registered your user account.";
  $contentFile = 'templates/t_registerSuccess.php';
  
  Common\Template\Template::helper('templates/' . getConfig('site_template'), array(
    'title' => $title,
    'siteTitle' => g('siteTitle'),
    'description' => $description,
    'contentFile' => $contentFile
  ));  
}

function validateForm($postVars, &$validationErrors)
{
  if(empty($postVars['email']))
    $validationErrors['email'] = "You must enter a valid email address.";
  if(empty($postVars['email-confirm']))
    $validationErrors['email-confirm'] = "You must re-enter your email address to confirm.";
  
  if(!empty($postVars['email']) && !empty($postVars['email-confirm']) 
    && $postVars['email'] !== $postVars['email-confirm']) {
    $validationErrors['email-confirm'] = "The email addresses you entered do not match.";
  }
    
  if(empty($validationErrors))
    return true;
  return false;
}

function checkUserData($postVars, &$validationErrors)
{
  //Check username
  $q = ServerBrowser\EM::getInstance()->createQueryBuilder();
  $q->select('u')->from('Entities\User', 'u')->where('u.username = :username')
    ->setParameter('username', $postVars['username']);
  
  $existingUsers = $q->getQuery()->getArrayResult();
  if(!empty($existingUsers))
    $validationErrors['username'] = "There is already a user with that username.";
  
  //Check email
  $q = ServerBrowser\EM::getInstance()->createQueryBuilder();
  $q->select('u')->from('Entities\User', 'u')->where('u.email = :email')
    ->setParameter('email', $postVars['email']);
  
  $existingUsers = $q->getQuery()->getArrayResult();
  if(!empty($existingUsers))
    $validationErrors['email'] = "There is already a user with that email address.";  
  
  if(empty($validationErrors))
    return true;
  return false;
}

function getPostVars() {
  $args = array(
    'username'         => array('filter' => FILTER_VALIDATE_REGEXP,
                                'options' => array(
                                  'regexp' => '/^[a-zA-Z0-9\-_]{3,20}$/')),
    'email'            => FILTER_VALIDATE_EMAIL,
    'email-confirm'    => FILTER_VALIDATE_EMAIL,
    'password'         => FILTER_SANITIZE_STRING,
    'password-confirm' => FILTER_SANITIZE_STRING
  );
  //length - 3-20, a-zA-Z0-9-_
  $postvars = filter_input_array(INPUT_POST, $args);
  
  return $postvars;
}
