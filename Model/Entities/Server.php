<?php
namespace Entities;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Server
 *
 * @author William Schaller
 * @Entity @Table(name="sb_server")
 */
class Server {
  /**
   *
   * @var type 
   * @Id @Column(type="integer")
   * @GeneratedValue
   */
  protected $id;
  
  /**
   *
   * @var type 
   * @Column(type="string")
   */
  protected $hash;
  
  /**
   *
   * @var type 
   * @Column(type="datetime")
   */
  protected $firstSeen;
  
  /**
   *
   * @var type 
   * @Column(type="datetime")
   */
  protected $lastUpdate;
  
  /**
   *
   * @var type 
   * @Column(type="string", nullable=true)
   */
  protected $IPv4Address;
  
  /**
   *
   * @var type 
   * @Column(type="string", nullable=true)
   */
  protected $IPv6Address;
  
  /**
   *
   * @var type 
   * @Column(type="integer")
   */
  protected $Port;
  
  /**
   *
   * @var type 
   * @Column(type="string")
   */
  protected $Name;
  
  /**
   *
   * @var \Doctrine\Common\Collections\ArrayCollection
   * @OneToMany(targetEntity="PlayerServer", mappedBy="server", cascade={"persist"}, orphanRemoval=true)
   */
  protected $players;
  
  public function __construct()
  {
    //$this->Snapshots = new ArrayCollection(); 
    $this->players = new \Doctrine\Common\Collections\ArrayCollection();
  }
  
  public function getId() {
    return $this->id;
  }

  public function setId($value) {
    $this->id = $value;
  }

  public function getHash() {
    return $this->hash;
  }

  public function setHash($value) {
    $this->hash = $value;
  }

  public function getFirstSeen() {
    return $this->firstSeen;
  }

  public function setFirstSeen($value) {
    if($value instanceof \DateTime)
      $this->firstSeen = $value;
    elseif(is_string($value))
      $this->firstSeen = \DateTime::createFromFormat('Y-m-d H:i:s', $value);
  }

  public function getLastUpdate() {
    return $this->lastUpdate;
  }

  public function setLastUpdate($value) {    
    if($value instanceof \DateTime)
      $this->lastUpdate = $value;
    elseif(is_string($value))
      $this->lastUpdate = \DateTime::createFromFormat('Y-m-d H:i:s', $value);
  }

  public function getIPv4Address() {
    return $this->IPv4Address;
  }

  public function setIPv4Address($value) {
    if(preg_match('/\b(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b/', $value) > 0)
      $this->IPv4Address = $value;
  }

  public function getIPv6Address() {
    return $this->IPv6Address;
  }

  public function setIPv6Address($value) {
    try {
      $v6 = new \v6tools\IPv6Address($value);
      $this->IPv6Address = $value;
    } catch(\InvalidArgumentException $e)
    {
      die('Invalid IPv6 Address specified.');
    }   
  }

  public function getPort() {
    return $this->Port;
  }

  public function setPort($value) {
    $this->Port = $value;
  }
  
  public function getName() {
    return $this->Name;
  }

  public function setName($value) {
    $this->Name = $value;
  }

  public function populate($server) {
    if(!isset($server['serverIPv4Address']) && !isset($server['serverIPv4Address']))
      throw new \InvalidArgumentException('Server record has no IP addresses.');
    if(isset($server['serverIPv4Address']))
      $this->setIPv4Address ($server['serverIPv4Address']);
    if(isset($server['serverIPv6Address']))
      $this->setIPv6Address ($server['serverIPv6Address']);
    if(isset($server['serverName']))
      $this->setName($server['serverName']);
    if(isset($server['serverPort']))
      $this->setPort($server['serverPort']);
    if(isset($server['firstSeen']))
      $this->setFirstSeen($server['firstSeen']);
    if(isset($server['lastUpdate']))
      $this->setLastUpdate ($server['lastUpdate']);
    if(isset($server['playerList']))
      $this->updatePlayers($server['playerList']);
    $this->genHash();
  }
  
  public function update($server) {
    //Update server name
    if(isset($server['serverName']) && $server['serverName'] != $this->getName())
      $this->setName($server['serverName']);
    //Update timestamp
    if(isset($server['lastUpdate']) 
      && \DateTime::createFromFormat(
        'Y-m-d H:i:s', $server['lastUpdate']) != $this->getLastUpdate())
      $this->setLastUpdate ($server['lastUpdate']);
    //Update current player list
    if(isset($server['playerList']))
      $this->updatePlayers($server['playerList']);
  }
  
  public function genHash() {
    $hash = '';
    $v4 = $this->getIPv4Address();
    $v6 = $this->getIPv6Address();
    if(!empty($v4))
      $hash .= $v4;
    if(!empty($v6))
      $hash .= $v6;
    $hash .= $this->getPort();
    $hash = md5($hash);
    $this->setHash($hash);
  }
  
  private function updatePlayers($playerList) {
    global $newPlayers, $updatedPlayers, $onlinePlayers;

      if(empty($playerList))
          return;
    $em = \ServerBrowser\EM::getInstance();
    //pre-load players
    $repo = $em->getRepository('Entities\Player');
    $repo->findBy(array('name' => $playerList));

    foreach($playerList as $playerName)
    {
      $onlinePlayers[] = strtolower($playerName);
      //find player record if it exists
      $player = $repo->findOneBy(array('name' => $playerName));
      
      if(!($player instanceof Player)) {
        //if it doesnt exist, create a new one
        $player = new Player();
        $player->setName($playerName);
        $player->updateFromAPI();
        $em->persist($player);
        $newPlayers++;
      } else 
        $updatedPlayers++;
      
      //Add the player to this server.
      $em->persist($this);
      $this->addPlayer($player);
      $em->persist($player);
      $em->persist($this);        
    }
  }
  
  public function addPlayer($player) {
    if($player instanceof Player) {
      //See if a player server record already exists for this server.
      $em = \ServerBrowser\EM::getInstance();
      erroff();
      $record = $em->getRepository('Entities\PlayerServer')->findOneBy(
        array('player' => $player, 'server' => $this));
      erron();
      if($record instanceof PlayerServer) {
        //Record already exists, increment the samples counter and persist.
        $record->setSamples($record->getSamples() + 1);
        $em->persist($record);
      } else {
        //Record doesn't exist, create a new one.
        $plr = new PlayerServer();
        $plr->setPlayer($player);
        $plr->setServer($this);
        $plr->setSamples(1);
        $this->players[] = $plr;
        $em->persist($plr);
      }
    } else if ($player instanceof PlayerServer) {
      $player->setSamples($player->getSamples() + 1);
      $this->players[] = $player;
    }
  }
  
  public function getGameLink() {
    if($this->getIPv6Address() != "")
      return gameLink($this->getIPv6Address(), $this->getPort());
    else 
      return gameLink($this->getIPv4Address(), $this->getPort());
  }
}

?>
