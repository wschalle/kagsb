<?php

require_once 'bootstrap.php';

mustBeLoggedIn();

$title = "KAG Server Browser: Change Password";
$description = "Change password page";

$contentFile = 'templates/t_passwordchange.php';

Common\Template\Template::helper('templates/' . getConfig('site_template'), array(
  'title' => $title,
  'siteTitle' => g('siteTitle'),
  'description' => $description,
  'contentFile' => $contentFile,
  'validationErrors' => $validationErrors
));