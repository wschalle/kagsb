<?php
namespace ServerBrowser;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EM
 *
 * @author William Schaller
 */
class EM {
  /**
   *
   * @var \Doctrine\ORM\EntityManager
   */
  private static $entityManagerInstance;
  
  /**
   * 
   * @global array $connectionOptions
   * @global array $config
   * @return \Doctrine\ORM\EntityManager
   */
  public static function getInstance()
  {
    global $connectionOptions, $config;
    if(!(self::$entityManagerInstance instanceof \Doctrine\ORM\EntityManager))
      self::$entityManagerInstance = $GLOBALS['EntityManager'];
    //\Doctrine\ORM\EntityManager::create($connectionOptions, $config);
    return self::$entityManagerInstance;
  }  
}

?>
