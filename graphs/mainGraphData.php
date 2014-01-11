<?php
include '../bootstrap.php';

$em = ServerBrowser\EM::getInstance();
$conn = $em->getConnection();

$conn->query("SET @nstot := 0");
$conn->query("SET @nptot := 0");

$sql = "SELECT
q1.date, 
q1.newServers,
(@nstot := @nstot + q1.newServers) as newServersCum,
q1.onlineServers,
q1.newPlayers,
(@nptot := @nptot + q1.newPlayers) as newPlayersCum,
q1.onlinePlayers
FROM
(SELECT as1.date, SUM(as1.newServers) as newServers,
AVG(as1.updatedServers + as1.newServers) as onlineServers, 
SUM(as1.newPlayers) as newPlayers,
AVG(as1.updatedPlayers + as1.newPlayers) as onlinePlayers
FROM sb_appstatus as1
GROUP BY floor(unix_timestamp(date)/3600)) as q1";

$sth = $conn->prepare($sql);
$sth->execute();
$res = $sth->fetchAll();
$result = array();

foreach($res as $sample)
{
  $date = DateTime::createFromFormat('Y-m-d H:i:s', $sample['date']);
  $result[] = array(
    'date' => $date->getTimeStamp() * 1000,
    'newServers' => $sample['newServers'],
    'onlineServers' => $sample['onlineServers'],
    'newPlayers' => $sample['newPlayers'],
    'onlinePlayers' => $sample['onlinePlayers'],
    'newServersCum' => $sample['newServersCum'],
    'newPlayersCum' => $sample['newPlayersCum']
  );
}

echo json_encode($result);
