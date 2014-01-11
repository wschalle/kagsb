<?php
namespace Entities;

/**
 * Description of appStatus
 *
 * @author William Schaller
 * @Entity @Table(name="sb_appstatus")
 * @HasLifecycleCallbacks
 */
class AppStatus {
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
  protected $date;
  
  /**
   *
   * @var type 
   * @Column(type="integer")
   */
  protected $updatedServers;
  
  /**
   *
   * @var type 
   * @Column(type="integer")
   */
  protected $newServers;
  
  /**
   *
   * @var type 
   * @Column(type="integer")
   */
  protected $updatedPlayers;
  
  /**
   *
   * @var type 
   * @Column(type="integer")
   */
  protected $newPlayers;
  
  public function getId() {
    return $this->id;
  }
  
  public function getDate() {
    return $this->date;
  }

  public function setDate($value) {
    $this->date = $value;
  }
    
  /**
   * @PrePersist
   */
  public function prePersistSetDate() {
    $this->setDate(new \DateTime());
  }

  public function getUpdatedServers() {
    return $this->updatedServers;
  }

  public function setUpdatedServers($value) {
    $this->updatedServers = $value;
  }

  public function getNewServers() {
    return $this->newServers;
  }

  public function setNewServers($value) {
    $this->newServers = $value;
  }

  public function getUpdatedPlayers() {
    return $this->updatedPlayers;
  }

  public function setUpdatedPlayers($value) {
    $this->updatedPlayers = $value;
  }

  public function getNewPlayers() {
    return $this->newPlayers;
  }

  public function setNewPlayers($value) {
    $this->newPlayers = $value;
  }
}

?>
