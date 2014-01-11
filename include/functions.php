<?php

function g($var) {
  if(array_key_exists($var, $GLOBALS))
    return $GLOBALS[$var];
  return null;
}

function mustBeLoggedIn() {
  if(!g('UserManager')->isLoggedIn())
    header('Location: login.php');
}

function gameLink($ip, $port) {
  return "kag://$ip:$port/";
}

$display_errors = 1;

function erroff() {
  ini_set('display_errors', 0);
}

function erron($force) {
  global $display_errors;
  if($force)
    ini_set('display_errors', 1);
  else
    ini_set('display_errors', $display_errors);
}

function KAGUserExists($kagname) {
    $cli = new KAGClient\Client();
    $info = $cli->getPlayerInfo($kagname);
    if(isset($info['http_code']) && $info['http_code'] == 404)
      return false;
    return true;
}

function getConfig($value) {
  global $config;
  if(isset($config[$value]))
    return $config[$value];
  return null;
}

function siteRoot() {
  return getConfig('siteRoot');
}

function absoluteUrl($path) {
  if(substr($path, 0, 1) == '/')
    return siteRoot() . $path;
  return siteRoot() . '/' . $path;
}