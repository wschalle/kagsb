<?php
namespace Entities;
/**
 * Description of PlayerServer
 *
 * @author William Schaller
 * @Entity @Table(name="sb_player_server")
 * @HasLifecycleCallbacks
 */
class PlayerServer {
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
   * @Column(type="datetime")
   */
  protected $createDate;
  
  /**
   *
   * @var type 
   * @Column(type="datetime", nullable=true)
   */
  protected $updateDate;
   
  /**
   *
   * @var type 
   * @Column(type="integer", nullable=true)
   */
  protected $samples = 0;
    
  /**
   *
   * @var type 
   * @ManyToOne(targetEntity="Server", inversedBy="players")
   */
  protected $server;
  
  /**
   *
   * @var type 
   * @ManyToOne(targetEntity="Player", inversedBy="servers")
   */
  protected $player;
  
  public function __construct() {
    
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

  public function getId() {
    return $this->id;
  }

  public function setId($value) {
    $this->id = $value;
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
  
  public function getSamples() {
    return $this->samples;
  }

  public function setSamples($value) {
    $this->samples = $value;
  }
    
  public function getPlayer() {
    return $this->player;
  }

  public function setPlayer($value) {
    $this->player = $value;
    $this->player->addServer($this);
  }

  public function getServer() {
    return $this->server;
  }

  public function setServer($value) {
    $this->server = $value;
    $this->server->addPlayer($this);
  }
  
  public function removed($caller) {
    if($caller instanceof Server)
      $this->player->removeServer($this);
    
    if($caller instanceof Player)
      $this->server->removePlayer($this);
    
    \ServerBrowser\EM::getInstance()->remove($this);
  }
}

?>
