<?php

require_once 'bootstrap.php';

mustBeLoggedIn();

$title = "KAG Server Browser: Delete Account";
$description = "Account deletion page";

$contentFile = 'templates/t_deleteaccount.php';

Common\Template\Template::helper('templates/' . getConfig('site_template'), array(
  'title' => $title,
  'siteTitle' => g('siteTitle'),
  'description' => $description,
  'contentFile' => $contentFile,
  'validationErrors' => $validationErrors
));