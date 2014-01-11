<?php

require_once 'bootstrap.php';

ini_set('display_errors', 1);

mustBeLoggedIn();

$validationErrors = array();

if(isset($_POST['playername']) && !empty($_POST['playername'])) {
  if(KAGUserExists($_POST['playername']))
    addBuddy($_POST['playername']);
  else
    $validationErrors['playername'] = "That KAG username does not exist. Please check that you entered it correctly.";    
}

if(isset($_POST['removebuddy']) && !empty($_POST['removebuddy']))
  removeBuddy($_POST['removebuddy']);

$buddies = getBuddies();

function getBuddies() {
  //$buddies = $EntityManager->getRepository('Entities\Buddy')->findBy(array('user' => $UserManager->getUser()));
  $qb = ServerBrowser\EM::getInstance()->createQueryBuilder();
  $qb->select('b, u, p');
  $qb->from('Entities\Buddy', 'b');
  $qb->join('b.user', 'u');
  $qb->join('b.player', 'p');
  $qb->where('b.user = :user');
  $qb->setParameter('user', g('UserManager')->getUser());
  $q = $qb->getQuery();
  $buddies = $q->getResult();
  return $buddies;
}

function addBuddy($playername) {
  $em = ServerBrowser\EM::getInstance();
  $player = $em->getRepository('Entities\Player')->findOneBy(array('name' => $playername));
  if(empty($player))
  {
    $player = new Entities\Player();
    $player->setName($playername);
    $em->persist($player);
  } else {
    $buddy = ServerBrowser\EM::getInstance()->getRepository('Entities\Buddy')->findOneBy(array(
      'user' => g('UserManager')->getUser(),
      'player' => $player));
    if(!empty($buddy))
      return;
  }
  
  $buddy = new Entities\Buddy();
  $buddy->setUser(g('UserManager')->getUser());
  $buddy->setPlayer($player);
  $em->persist($buddy);
  $em->flush();
}

function removeBuddy($playername) {
  $em = ServerBrowser\EM::getInstance();
  $player = $em->getRepository('Entities\Player')->findOneBy(array('name' => $playername));
  $buddy = ServerBrowser\EM::getInstance()->getRepository('Entities\Buddy')->findOneBy(array(
    'user' => g('UserManager')->getUser(),
    'player' => $player));
  if($buddy instanceof Entities\Buddy) {
    $em->remove($buddy);
    $em->flush();
  }
}

$title = "KAG Server Browser: Manage Buddies";
$description = "Buddy management page";

$contentFile = 'templates/t_buddies.php';

Common\Template\Template::helper('templates/' . getConfig('site_template'), array(
  'title' => $title,
  'siteTitle' => g('siteTitle'),
  'description' => $description,
  'contentFile' => $contentFile,
  'validationErrors' => $validationErrors,
  'buddies' => $buddies  
));