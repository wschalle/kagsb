<?php
use ServerBrowser\EM as EM;
include 'bootstrap.php';

use KAGClient\Client;

require_once 'lib/dnode-php/vendor/autoload.php';

$loop = new React\EventLoop\StreamSelectLoop();

$dnode = new DNode\DNode($loop);
try {
$dnode->connect(82, function($remote, $connection) {
  $remote->startedUpdate(function() use ($connection) {
        echo "Socket.io connection successful.\n";
        $connection->end();
    });
});
$loop->run();
} catch (Exception $e) {
  echo "Could not connect to Notifier. Message: " . $e->getMessage() . "\n"; 
}

/** Init KAGClient **/
$cli = new Client();

$startTime = microtime(true);

echo "Fetching server list\n";
/** Fetch Servers **/
$servers = $cli->getServerList(array('current' => true));
$servers = $servers['serverList'];
echo "Got server list\n";

//Stats
$newServers = 0;
$updatedServers = 0;
$newPlayers = 0;
$updatedPlayers = 0;
$onlinePlayers = array();
  
$em = EM::getInstance();
$em->getRepository('Entities\Server')->findAll();
foreach($servers as $server)
{
  //Build hash for unique server identitys
  $hash = '';
  if(isset($server['serverIPv4Address']))
    $hash .= $server['serverIPv4Address'];
  if(isset($server['serverIPv6Address']))
    $hash .= $server['serverIPv6Address'];
  $hash .= $server['serverPort'];
  $hash = md5($hash);
   
  //Find the existing server record
  $serverEntity = $em->getRepository('Entities\Server')->findOneBy(array('hash' => $hash));
  if(!($serverEntity instanceof Entities\Server)) {
    //If there is none existing, create it
    echo "New Server:      " . $server['serverName'] . "\n";
    insertServer($server);
  } else {
    //Otherwise, update the record.
    echo "Updating Server: " . $server ['serverName'] . "\n";
    updateServer($server, $serverEntity);
  }
}

$totalTime = round(microtime(true) - $startTime,2);

echo "Updated " . count($servers) . " servers in $totalTime seconds.\n";
echo "API Calls executed: " . Client::getCallCount() . "\n";
echo "Players online: " . ($newPlayers + $updatedPlayers) . "\n";

$statusUpdate = new Entities\AppStatus();
$statusUpdate->setNewServers($newServers);
$statusUpdate->setUpdatedServers($updatedServers);
$statusUpdate->setNewPlayers($newPlayers);
$statusUpdate->setUpdatedPlayers($updatedPlayers);
$em->persist($statusUpdate);
$em->flush();

echo "Sending online player list to Notifier...\n";
//$dnode = new DNode\DNode($loop);
try {
$dnode->connect(82, function($remote, $connection) {
  global $onlinePlayers;
  $remote->onlinePlayers($onlinePlayers, function() use ($connection) {
        echo "Online player list sent successfully.";
    });
  $remote->finishedUpdate(function() use ($connection) {
    $connection->end();
  });
});
$loop->run();
} catch (Exception $e) {
  echo "Could not connect to Notifier. Message: " . $e->getMessage() . "\n"; 
}

function insertServer($server)
{
  global $newServers;
  //Add a new server to the DB.
  $serverEntity = new Entities\Server();
  $serverEntity->populate($server);
  EM::getInstance()->persist($serverEntity);
  EM::getInstance()->flush();
  $newServers++;
  
}

function updateServer($server, $serverEntity)
{
  global $updatedServers;
  //Update a server
  $serverEntity->update($server);
  EM::getInstance()->persist($serverEntity);
  EM::getInstance()->flush();
  $updatedServers++;
}