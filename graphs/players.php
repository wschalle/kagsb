<?php

include '../bootstrap.php';

$prebodyscriptfile = array('//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js',
  'http://www.google.com/jsapi', 
  absoluteUrl('scripts/dygraph-combined.js'),
  absoluteUrl('scripts/swfobject.js'),
  absoluteUrl('scripts/graph_players.js'));
$title = "KAG Server Browser: Charts & Graphs!";
$description = "A chart showing various data about KAG servers and the playerbase";

$contentFile = 'templates/graphs/t_players.php';

Common\Template\Template::helper('templates/' . getConfig('site_template'), array(
  'title' => $title,
  'siteTitle' => g('siteTitle'),
  'description' => $description,
  'contentFile' => $contentFile,
  'validationErrors' => $validationErrors,
  'preBodyScriptFile' => $prebodyscriptfile,
  'graphPage' => true
));
?>