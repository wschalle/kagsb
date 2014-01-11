<?php
namespace Entities;
/**
 * Description of User
 *
 * @author William Schaller
 * @Entity @Table(name="sb_user")
 * @HasLifecycleCallbacks
 */
class User {
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
   * @Column(type="string", length=60)
   */
  protected $username;
  
  /**
   *
   * @var type 
   * @Column(type="datetime")
   */
  protected $createDate;
  
  /**
   *
   * @var type 
   * @Column(type="datetime")
   */
  protected $updateDate;
  
  /**
   *
   * @var type 
   * @Column(type="string", length=254, nullable=true)
   */
  protected $email;
  
  /**
   *
   * @var type 
   * @Column(type="string", length=255, nullable=true)
   */
  protected $playerKagName;
  
  /**
   *
   * @var type 
   * @Column(type="integer", nullable=true)
   */
  protected $playerForumId;
  
  /**
   *
   * @var type 
   * @Column(type="boolean")
   */
  protected $playerVerified = false;
  
  /**
   *
   * @var type 
   * @Column(type="string", length=40, nullable=true)
   */
  protected $playerVerificationToken;
  
  /**
   *
   * @var type 
   * @Column(type="datetime", nullable=true)
   */
  protected $lastSeen;  
  
  /**
   *
   * @var type 
   * @OneToOne(targetEntity="Player", mappedBy="user")
   */
  protected $player;
  
  /**
   *
   * @var \Doctrine\Common\Collections\ArrayCollection
   * @OneToMany(targetEntity="Buddy", mappedBy="user", orphanRemoval=true)
   */
  protected $buddies;
  
  
  public function __construct()
  {
    $this->buddies = new \Doctrine\Common\Collections\ArrayCollection();
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
  
  /**
   * @PrePersist
   */
  public function prePersistSetCreateDate() {
    $this->createDate = new \DateTime();
    $this->updateDate = $this->createDate;
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

  public function getUsername() {
    return $this->username;
  }

  public function setUsername($value) {
    $this->username = $value;
  }

  public function getEmail() {
    return $this->email;
  }

  public function setEmail($value) {
    $this->email = $value;
  }

  public function getPlayer() {
    return $this->player;
  }

  public function setPlayer($value) {
    if($this->player == $value)
      return;
    if($this->player instanceof Player)
      $this->player->setUser(null);
    $this->player = $value;
    $this->player->setUser(this);
  }

  public function getPlayerForumId() {
    return $this->playerForumId;
  }

  public function setPlayerForumId($value) {
    $this->playerForumId = $value;
  }

  public function getPlayerKagName() {
    return $this->playerKagName;
  }

  public function setPlayerKagName($value) {
    $this->playerKagName = $value;    
  }

  public function getPlayerVerificationToken() {
    return $this->playerVerificationToken;
  }

  public function setPlayerVerificationToken($value) {
    $this->playerVerificationToken = $value;
  }

  public function generateToken() {
    $url = "https://forum.kag2d.com/members/";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array('username' => $this->getPlayerKagName()));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    $response = curl_exec($ch);
    $info = curl_getinfo($ch);
    //var_dump($info);
    //var_dump($response);
    //var_dump($this->getPlayerKagName());    
    curl_close($ch);
    
    if(!is_array($info) || !isset($info['url']))
      throw new \Exception('Could not get user ID when fetching KAG forum data.');
    
    $redir = $info['url'];
    $id = strlen($this->getPlayerKagName()) + strpos($redir, $this->getPlayerKagName()) + 1;
    $id = substr($redir, $id);
    $id = (int)substr($id, 0, strlen($id) - 1);
    
    if($id <= 0)
      throw new \InvalidArgumentException('Invalid user ID retrieved when fetching KAG forum data.');
    
    $this->setPlayerForumId($id);    
    $this->setPlayerVerificationToken(sha1(microtime(true) + $id));
    \ServerBrowser\EM::getInstance()->persist($this);
    \ServerBrowser\EM::getInstance()->flush();    
  }
  
  public function checkKagForumToken() {    
    $url = "https://forum.kag2d.com/members/" . $this->getPlayerKagName() . '.' . $this->getPlayerForumId() . '/?card=1&_xfResponseType=json';
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    $info = curl_getinfo($ch);
    
    if(strpos($response, $this->getPlayerVerificationToken()) !== false) {
      $this->setPlayerVerified(true);
      \ServerBrowser\EM::getInstance()->persist($this);
      \ServerBrowser\EM::getInstance()->flush();
      return true;
      
    } 
    return false;
  }
  
  public function getPlayerVerified() {
    return $this->playerVerified;
  }

  public function setPlayerVerified($value) {
    $this->playerVerified = $value;
  }
  
  public function getLastSeen() {
    return $this->lastSeen;
  }

  public function setLastSeen($value) {
    $this->lastSeen = $value;
  }

  public function seen() {
    $this->lastSeen = new \DateTime();
  }

  public function addBuddy($player) {
    if($this->buddyExists($player))
      return;
    $new = new Buddy();
    $new->setUser($this);
    $new->setPlayer($player);   
  }
  
  /**
   * Only to be used if the buddy is being added from the other side
   * @param \Buddy $buddy
   */
  public function addBuddyBuddy($buddy) {
    $this->buddies[] = $buddy;
  }
  
  public function buddyExists($player) {
    foreach($this->buddies as $buddy)
    {
      if($buddy->getPlayer() == $player)
        return true;
    }
    return false;
  }
  
  public function getBuddy($player) {
    foreach($this->buddies as $buddy) {
      if($buddy->getPlayer() == $player)
        return $buddy;
    }
    return null;
  }
    
  public function removeBuddy($player) {
    $buddy = $this->getBuddy($player);
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
  
  public function getOnlineBuddyCount() {
    $onlineCount = 0;
    foreach($this->buddies as $buddy)
    {
      if($buddy->isOnline())
        $onlineCount++;
    }
    
    return $onlineCount;
  }






}

?>
