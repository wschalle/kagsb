<?php
namespace Entities;
/**
 * Description of Buddy
 *
 * @author William Schaller
 * @Entity @Table(name="sb_buddy")
 * @HasLifecycleCallbacks
 */
class Buddy {
  /**
   *
   * @var type 
   * @Id @Column(type="integer")
   * @GeneratedValue
   */
  protected $id;
  
  /**
   *
   * @var User 
   * @ManyToOne(targetEntity="User", inversedBy="buddies")
   */
  protected $user;
  
  /**
   *
   * @var Player
   * @ManyToOne(targetEntity="Player", inversedBy="buddies")
   */
  protected $player;
  
  /**
   *
   * @var type 
   * @Column(type="boolean")
   */
  protected $confirmed = false;
  
  /**
   *
   * @var type 
   * @Column(type="datetime")
   */
  protected $createDate;
  
  /**
   *
   * @var type 
   * @Column(type="datetime", nullable=true)
   */
  protected $updateDate;
  
  public function __construct()
  {
    
  }
  
  public function getId() {
    return $this->id;
  }

  public function setId($value) {
    $this->id = $value;
  }

  public function getUser() {
    return $this->user;
  }

  public function setUser(User $user) {
    $this->user = $user;
    $user->addBuddyBuddy($this);
  }

  public function getPlayer() {
    return $this->player;
  }

  public function setPlayer(Player $player) {
    $this->player = $player;
    $player->addBuddyBuddy($this);
  }

  public function getConfirmed() {
    return $this->confirmed;
  }

  public function setConfirmed($value) {
    $this->confirmed = $value;
  }

  public function getCreateDate() {
    return $this->createDate;
  }

  public function setCreateDate($value) {
    $this->createDate = $value;
  }

  public function getUpdateDate() {
    return $this->updateDate;
  }

  public function setUpdateDate($value) {
    $this->updateDate = $value;
  }
  
  /**
   * @PrePersist
   */
  public function prePersistSetCreateDate() {
    $this->createDate = new \DateTime();
    $this->updateDate = $this->createDate;
  }
  
  /**
   * @PreUpdate
   */
  public function preUpdateSetUpdateDate() {
    $this->updateDate = new \DateTime();
  }
  
  public function removed($caller) {
    if($caller instanceof Player)
      $this->user->removeBuddyBuddy($this);
    
    if($caller instanceof User)
      $this->player->removeBuddyBuddy ($this);
    
    \ServerBrowser\EM::getInstance()->remove($this);
  }
  
  public function isOnline() {
    $onlineLimit = new \DateTime();
    $buddyTime = getConfig('buddyTime');
    $onlineLimit->modify('-' . $buddyTime . ' minutes');
    $em = \ServerBrowser\EM::getInstance();
    $q = $em->createQuery("select ps 
      from Entities\PlayerServer ps 
      where ps.updateDate > :onlineLimit 
      and ps.player = :player");
    $q->setParameter('onlineLimit', $onlineLimit);
    $q->setParameter('player', $this->player);
    
    $result = $q->getResult();
    if(!empty($result) && is_array($result) && $result[0] instanceof PlayerServer)
    {
      return $result[0];
    }
    return false;
  }
  
  public function getGameLink() {
    $playerserver = $this->isOnline();
    if(!$playerserver)
      return null;
    $server = $playerserver->getServer();
    return $server->getGameLink();
  }
  
  public function redirectToGame() {
    echo "<script>window.onload = function() {window.location = '" . $this->getGameLink() . "';};</script>";
  }
}

?>
