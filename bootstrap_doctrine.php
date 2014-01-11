<?php
require_once 'bootstrap.php';
$basedir = __DIR__;

// test.php
require $basedir . '/doctrine2/lib/Doctrine/ORM/Tools/Setup.php';

Doctrine\ORM\Tools\Setup::registerAutoloadGit($basedir . '/doctrine2');

//if ($Config->global->environment == 'dev')
  $applicationMode = $config['environment'];
//else
  //$applicationMode = "prod";

use Doctrine\ORM\EntityManager,
    Doctrine\ORM\Configuration;

if ($applicationMode == "development") {
    $cache = new \Doctrine\Common\Cache\ArrayCache;
} else {
    $cache = new \Doctrine\Common\Cache\XcacheCache;
}

$doctrineconfig = new Configuration;
$doctrineconfig->setMetadataCacheImpl($cache);
$doctrineconfig->setQueryCacheImpl($cache);
$doctrineconfig->setResultCacheImpl($cache);
$driverImpl = $doctrineconfig->newDefaultAnnotationDriver($basedir . '/Model/Entities');
$doctrineconfig->setMetadataDriverImpl($driverImpl);
$doctrineconfig->setProxyDir($basedir . '/Model/Proxies');
$doctrineconfig->setProxyNamespace('Proxies');

$classLoader = new \Doctrine\Common\ClassLoader('Entities', $basedir.'/Model');
$classLoader->register();

if ($applicationMode == "development") {
    $doctrineconfig->setAutoGenerateProxyClasses(false);
} else {
    $doctrineconfig->setAutoGenerateProxyClasses(false);
}

$dbtype = getConfig('dbtype');
require 'doctrine_config.php';

/**
 * @var Doctrine\ORM\EntityManager
 */
$EntityManager = EntityManager::create($connectionOptions, $doctrineconfig);