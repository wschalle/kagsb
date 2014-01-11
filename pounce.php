<?php

/**
 * Example - server info viewer component
 * Fetches status info for a given server and displays it.
 * Intended for use via AJAX in servers.php
 */
use KAGClient\Client;
include 'KAGClient/bootstrap.php';

if(!isset($_GET['ip']) || !isset($_GET['port']))
{
  header('HTTP/1.1 400 Bad Request');
  die('400 Bad Request');
}
$ip = $_GET['ip'];
$port = $_GET['port'];

$cli = new Client();
$server = $cli->getServerStatus(array('ip' => $ip, 'port' => $port));
if(isset($server['http_code']) && $server['http_code'] != 200)
{
  var_dump($server);
  die('An error occurred.');
}
$server = $server['serverStatus'];
$pounce = ($server['currentPlayers'] < $server['maxPlayers'])?1:0;
echo json_encode(array('open_slot' => $pounce));