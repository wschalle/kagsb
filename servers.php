<?php

$title = $siteTitle;
$description = "KAG Server List";

$contentFile = 'templates/t_servers.php';
$postBodyScriptFile = 'scripts/servers.js';

\Common\Template\Template::helper('templates/' . getConfig('site_template'), array(
  'title' => $title,
  'siteTitle' => $siteTitle,
  'description' => $description,
  'contentFile' => $contentFile,
  'postBodyScriptFile' => $postBodyScriptFile,
  'serverListActive' => true
));