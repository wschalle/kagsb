<?php
namespace ServerBrowser;

const KAGREGSTAGE_ERROR = 1;
const KAGREGSTAGE_NONAME = 2;
const KAGREGSTAGE_NOKEY = 3;
const KAGREGSTAGE_WAIT_KEY = 4;
const KAGREGSTAGE_REGISTERED = 5;

/**
 * Description of UserManager
 *
 * @author William Schaller
 */
class UserManager {
  protected $user;
  protected $loggedInUser;
  protected $initialized = false;
  protected $loggedIn = false;
  
  public function __construct() {
    if(session_id() == "")
      throw new \RuntimeException("User session load failed. Session does not exist.");
    
    if(isset($_SESSION['loggedInUser']) && $_SESSION['loggedInUser'] instanceof \Entities\User){
      $this->loggedInUser = $_SESSION['loggedInUser'];
      if($this->loggedInUser->getId() != null) {
        $this->loggedIn = true;
        $this->initialized = true;
        $this->loggedInUser = EM::getInstance()->merge($this->loggedInUser);
        $this->loggedInUser->seen();
        EM::getInstance()->persist($this->loggedInUser);
        EM::getInstance()->flush();
      }
    } 
    
    if($this->isLoggedIn() 
      && $this->loggedInUser->getEmail() == null 
      && strpos($_SERVER['PHP_SELF'], 'registerEmail.php') === false
      && strpos($_SERVER['PHP_SELF'], 'scripts') === false)
      header('Location: ' . absoluteUrl('registerEmail.php'));
  }
  
  public function shutdown() {
    if($this->loggedIn && $this->initialized && $this->loggedInUser instanceof \Entities\User)
    {
      $_SESSION['loggedInUser'] = $this->loggedInUser;
      EM::getInstance()->detach($_SESSION['loggedInUser']);
    }
  }
  
  public function isLoggedIn() {
    if($this->initialized && $this->loggedIn)
      return true;
    return false;
  }
  
  public function doLogin($username) {
    $user = EM::getInstance()->getRepository('Entities\User')->findOneBy(array('username' => $username));
    
    if(!$user || !($user instanceof \Entities\User))
    {
      $user = $this->createUser($username); 
    }
    
    $this->loggedInUser = $user;
    $this->loggedIn = true;
    $this->initialized = true;
    $_SESSION['loggedInUser'] = $user;
    return true;
    
  }
  
  public function createUser($username) {
    $user = new \Entities\User();
    $user->setUsername($username);
    $user->setPlayerKagName($username);
    $user->setPlayerVerified(true);
    \ServerBrowser\EM::getInstance()->persist($user);
    \ServerBrowser\EM::getInstance()->flush();
    return $user;
  }
  
  public function doLogout() {
    if(!$this->initialized)
      return;
    $this->loggedInUser = null;
    unset($_SESSION['loggedInUser']);
    $this->loggedIn = false;
  }
  
  /**
   * 
   * @return \Entities\User
   */
  public function getUser() {
    return $this->loggedInUser;
  }
  
  public function getKagRegStage() {
    if(!$this->loggedIn)
      return KAGREGSTAGE_ERROR;
    if($this->getUser()->getPlayerKagName() == null)
      return KAGREGSTAGE_NONAME;
    if($this->getUser()->getPlayerKagName() != null 
      && !$this->getUser()->getPlayerVerified() 
      && $this->getUser()->getPlayerVerificationToken() == null)
      return KAGREGSTAGE_NOKEY;
    if(!$this->getUser()->getPlayerVerified())
      return KAGREGSTAGE_WAIT_KEY;
    else
      return KAGREGSTAGE_REGISTERED;
  }
  
  public function registerKagName($name) {
    //already registered, GTFO
    if($this->getUser()->getPlayerKagName() != null)
      return;
      //throw new \InvalidArgumentException('KAG username already registered.');
      
    //Set the kag name
    $this->getUser()->setPlayerKagName($name);
    EM::getInstance()->persist($this->getUser());
    EM::getInstance()->flush();
  }
  
  public function checkKagRegKey() {
    return $this->getUser()->checkKagForumToken();
  }
}


?>
