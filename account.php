<?php

require_once 'bootstrap.php';

mustBeLoggedIn();

$title = "KAG Server Browser: My Account";
$description = "Account page";

$contentFile = 'templates/t_account.php';

Common\Template\Template::helper('templates/' . getConfig('site_template'), array(
  'title' => $title,
  'siteTitle' => g('siteTitle'),
  'description' => $description,
  'contentFile' => $contentFile,
  'validationErrors' => $validationErrors
));