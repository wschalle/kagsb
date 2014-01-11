<?php
namespace Entities;
use KAGClient\Client as Client;

/**
 * Description of Player
 *
 * @author William Schaller
 * @Entity @Table(name="sb_player")
 * @HasLifecycleCallbacks
 */
class Player {
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
   * @Column(type="string", nullable=true)
   */
  protected $name;
  
  /**
   *
   * @var type 
   * @Column(type="datetime")
   */
  protected $updateDate;
  
  /**
   *
   * @var type 
   * @Column(type="datetime")
   */
  protected $createDate;
  
  /**
   *
   * @var bool
   * @Column(type="boolean")
   */
  protected $active = true;
  
  /**
   *
   * @var type 
   * @Column(type="boolean")
   */
  protected $banned = true;
  
  /**
   *
   * @var type 
   * @Column(type="boolean")
   */
  protected $gold = false;
  
  /**
   *
   * @var type 
   * @Column(type="integer")
   */
  protected $role = 0;
  
  
  /**
   *
   * @var type 
   * @OneToOne(targetEntity="User", inversedBy="player")
   */
  protected $user;
  
  /**
   *
   * @var \Doctrine\Common\Collections\ArrayCollection
   * @OneToMany(targetEntity="Buddy", mappedBy="player", orphanRemoval=true)
   */
  protected $buddies;
  
  /**
   *
   * @var \Doctrine\Common\Collections\ArrayCollection
   * @OneToMany(targetEntity="PlayerServer", mappedBy="player", cascade={"persist"}, orphanRemoval=true)
   */
  protected $servers;
  
  public function __construct() 
  {
    $this->buddies = new \Doctrine\Common\Collections\ArrayCollection();
    $this->servers = new \Doctrine\Common\Collections\ArrayCollection();
  }
  
  public function getId() {
    return $this->id;
  }

  public function setId($value) {
    $this->id = $value;
  }

  public function getName() {
    return $this->name;
  }

  public function setName($value) {
    $this->name = $value;
  }

  public function getUpdateDate() {
    return $this->updateDate;
  }

  public function setUpdateDate($value) {
    $this->updateDate = $value;
  }
  
  /**
   * @PreUpdate
   */
  public function preUpdateSetUpdateDate() {
    $this->updateDate = new \DateTime();
  }

  public function getCreateDate() {
    return $this->createDate;
  }

  public function setCreateDate($value) {
    $this->createDate = $value;
  }
  
  /**
   * @PrePersist
   */
  public function prePersistSetCreateDate() {
    $this->createDate = new \DateTime();
    $this->updateDate = $this->createDate;
  }

  public function getActive() {
    return $this->active;
  }

  public function setActive($value) {
    $this->active = $value;
  }

  public function getBanned() {
    return $this->banned;
  }

  public function setBanned($value) {
    $this->banned = $value;
  }

  public function getGold() {
    return $this->gold;
  }

  public function setGold($value) {
    $this->gold = $value;
  }

  public function getRole() {
    return $this->role;
  }

  public function setRole($value) {
    $this->role = $value;
  }

  public function getUser() {
    return $this->user;
  }

  public function setUser($value) {
    if($this->user == $value)
      return;
    if($this->user instanceof User)
      $this->user->setPlayer(null);
    $this->user = $value;
    $this->user->setPlayer(this);
  }

  public function addBuddy($user) {
    if($this->buddyExists($user))
      return;
    $new = new Buddy();
    $new->setPlayer($this);
    $new->setUser($user);  
  }
  
  /**
   * Only to be used if the buddy is being added from the other side
   * @param \Buddy $buddy
   */
  public function addBuddyBuddy($buddy) {
    $this->buddies[] = $buddy;
  }
  
  public function buddyExists($user) {
    foreach($this->buddies as $buddy)
    {
      if($buddy->getUser() == $user)
        return true;
    }
    return false;
  }
  
  public function getBuddy($user) {
    foreach($this->buddies as $buddy) {
      if($buddy->getUser() == $user)
        return $buddy;
    }
    return null;
  }
    
  public function removeBuddy($user) {
    $buddy = $this->getBuddy($user);
    if($buddy == null)
      return;
    
    $this->buddies->removeElement($buddy);
    $buddy->removed($this);
  }  
  
  public function removeBuddyBuddy(Buddy $buddy) {
    $this->buddies->removeElement($buddy);
  }
  
  public function getBuddies() {
    return $this->buddies;
  }

  public function addServer($server) {
    if($server instanceof Server) {
      $svr = new PlayerServer();
      $svr->setPlayer($this);
      $svr->setServer($server);
      $this->servers[] = $svr;
      \ServerBrowser\EM::getInstance()->persist($svr);
    } else if ($server instanceof PlayerServer) {
      $this->servers[] = $server;
    }
  }

  public function updateFromAPI() {
    $cli = new Client();
    $info = $cli->getPlayerInfo($this->getName());
    if(isset($info['active']))
      $this->setActive($info['active']);
    if(isset($info['banned']))
      $this->setBanned($info['banned']);
    if(isset($info['gold']))
      $this->setGold($info['gold']);
    if(isset($info['role']))
      $this->setRole($info['role']);
  }
}

?>
