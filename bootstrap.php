<?php
ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__);
require 'config.php';
require_once 'include/functions.php';
require_once 'bootstrap_doctrine.php';
require_once 'KAGClient/bootstrap.php';
require_once 'v6tools/autoload.php';

//Load dem classes yo
$classLoader = new \Doctrine\Common\ClassLoader('ServerBrowser', $basedir.'/classes');
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Common', $basedir.'/classes');
$classLoader->register();

if(php_sapi_name() == 'cli') //If we're on the CLI, we've already done all that is needed.
  return;

//Start dat sesh bitch
session_name('SESSID_KAGSB');
session_start();
$UserManager = new ServerBrowser\UserManager();

register_shutdown_function('sb_shutdown');

$siteTitle = "KAG Server Browser 0.5b";
if(!isset($config['site_template']))
  $config['site_template'] = 'site.php';
function sb_shutdown() {
  global $UserManager;
  
  $UserManager->shutdown();
}
