<?php
/**
 * KAG REST API Client V0.2
 *
 * author: William Schaller
 * contact: wschalle@gmail.com
 * Copyright (c) 2012, William Schaller
 * License: http://www.gnu.org/copyleft/gpl.html GNU GENERAL PUBLIC LICENSE (GPLv3)
 */

namespace KAGClient;

/**
 * KAG REST API Client V0.2
 *
 */
class Client {
  private $baseUrl = 'https://api.kag2d.com';
  private static $curlHandle;
  private $returnType = 'array';
  private static $calls = 0;
  private static $shutdown_registered = false;
  
  public function __construct ()
  {
    if(empty(self::$curlHandle)) {
      self::$curlHandle = curl_init();
      curl_setopt(self::$curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
      curl_setopt(self::$curlHandle, CURLOPT_SSL_VERIFYPEER, true);
      curl_setopt(self::$curlHandle, CURLOPT_CAINFO, __DIR__ . '/ca-bundle.crt');
      curl_setopt(self::$curlHandle, CURLOPT_RETURNTRANSFER, true);
      curl_setopt(self::$curlHandle, CURLOPT_TIMEOUT, 30);
      curl_setopt(self::$curlHandle, CURLOPT_CONNECTTIMEOUT, 30);
    }
    
    self::register_shutdown(); 
  }
  
  public function setReturnType($type)
  {
    switch($type)
    {
      case 'json':
        $this->returnType = 'json';
      case 'array':
        $this->returnType = 'array';
      default: 
        throw new ClientException("Return type not recognized.");
    }
  }
  
  private function buildParameterString($parameters, $command)
  {
    $queryString = '';
    
    $def = ServiceDefinition::getCommandDefinition($command);
    
    foreach($parameters as $key => $value)
    {
      if(isset($def['params'][$key]['urlEncode']) && $def['params'][$key]['urlEncode'] == true)
        $value = rawurlencode($value);
      
      if(isset($def['params'][$key]['noParameterKey']) && $def['params'][$key]['noParameterKey'] == true)
        $queryString .= "/$value";
      else
        $queryString .= "/$key/$value";
    }
    return $queryString;
  }
  
  private function executeRequest($prepend, $queryString, $append) {
    if(substr($this->baseUrl, strlen($this->baseUrl) + 1, 1) == '/')
      $this->baseUrl = substr($this->baseUrl, 0, strlen($this->baseUrl) - 1);
    
    if(strlen($append) !== 0 && substr($append, 0, 1) !== '/')
      $append = '/' . $append;
    
    if(strlen($prepend) !== 0 && substr($prepend, 0, 1) !== '/')
      $prepend = '/' . $prepend;
    
    $url = $this->baseUrl . $prepend . $queryString . $append;
    //$curl = curl_copy_handle(self::$curlHandle);
    $curl = self::$curlHandle;
    curl_setopt($curl, CURLOPT_URL, $url);    
      
    $response = curl_exec($curl);
    self::$calls++;
    
    if(!$response)
      throw new ClientException('Error occurred while retrieving resource. ' . curl_error($curl));
    
    $responseInfo = curl_getinfo($curl);
    $responseInfo['response'] = $response;
    //curl_close($curl);
    
    if($responseInfo['http_code'] != 200)
    {
      return $this->formatResponse($responseInfo);      
    }
    
    return $this->formatResponse($this->recognizeResponse($responseInfo));
  }
  
  private function recognizeResponse($response)
  {
    $data = array();
    switch($response['content_type'])
    {
      case 'application/json':
        $data = json_decode($response['response'], true);
        break;
      case 'image/png':
        $data = $response['response'];
        break;
      default:
        throw new ClientException('Content type returned by the server was unrecognized. Server returned: ' . $response['content_type']);
        break;
    }
    
    return $data;
  }
  
  private function formatResponse($response) {
    switch($this->returnType)
    {
      case 'json':
        $response = json_encode($response);
        break;
      default:
        break;
    }
    
    return $response;
  }
  
  public function getServerList(array $parameters = array())
  {
    $command = 'get_servers';
    $prepend = $this->getPrepend($command);
    $append = $this->getAppend($command);
    
    if($this->verifyParameters($command, $parameters)) {
      $queryString = $this->buildParameterString($parameters, $command);
      return $this->executeRequest($prepend, $queryString, $append);        
    }
  }
  
  public function getServerStatus(array $parameters = array())
  {
    $command = 'get_server_status';
    $prepend = $this->getPrepend($command);
    $append = $this->getAppend($command);
    
    if($this->verifyParameters($command, $parameters)) {
      $queryString = $this->buildParameterString($parameters, $command);
      return $this->executeRequest($prepend, $queryString, $append);        
    }
  }
  
  public function getServerMinimap(array $parameters = array())
  {
    $command = 'get_server_minimap';
    $prepend = $this->getPrepend($command);
    $append = $this->getAppend($command);
    
    if($this->verifyParameters($command, $parameters)) {
      $queryString = $this->buildParameterString($parameters, $command);
      return $this->executeRequest($prepend, $queryString, $append);        
    }
  }
  
  public function getPlayerStatus($playerName) {
    $command = 'get_player_status';
    $prepend = $this->getPrepend($command);
    $append = $this->getAppend($command);
    
    $parameters = array('name' => $playerName);
    
    if($this->verifyParameters($command, $parameters)) {
      $queryString = $this->buildParameterString($parameters, $command);
      return $this->executeRequest($prepend, $queryString, $append);
    }
      
  }
  
  public function getPlayerAvatar($playerName) {
    $command = 'get_player_avatar';
    $prepend = $this->getPrepend($command);
    $append = $this->getAppend($command);
    
    $parameters = array('name' => $playerName);
    
    if($this->verifyParameters($command, $parameters)) {
      $queryString = $this->buildParameterString($parameters, $command);
      return $this->executeRequest($prepend, $queryString, $append);
    }      
  }
  
  public function getPlayerInfo($playerName) {
    $command = 'get_player_info';
    $prepend = $this->getPrepend($command);
    $append = $this->getAppend($command);
    
    $parameters = array('name' => $playerName);
    
    if($this->verifyParameters($command, $parameters)) {
      $queryString = $this->buildParameterString($parameters, $command);
      return $this->executeRequest($prepend, $queryString, $append);
    }      
  }
  
  public function validateSSOToken($playerName, $token) {
    $command = 'validate_token';
    $prepend = $this->getPrepend($command);
    $append = $this->getAppend($command);
    
    $parameters = array('player' => $playerName, 'token' => $token);
    if($this->verifyParameters($command, $parameters)) {
      $queryString = $this->buildParameterString($parameters, $command);
      $result = $this->executeRequest($prepend, $queryString, $append);
      
      if(isset($result['playerTokenStatus']) && $result['playerTokenStatus'] === true)
        return true;
    }
    return false;
  }
  
  private function verifyParameters($command, array $parameters) {
    $ret = true;
    
    //Check for required parameters
    $paramdef = ServiceDefinition::getCommandDefinition($command);
    foreach($paramdef['params'] as $param => $definition)
      if(isset($definition['required']) && $definition['required'] == true)
        if(!isset($parameters[$param]))
          throw new ClientException('Required parameter "' . $param . '" not set.');    
    
    //Check parameters against definition
    foreach($parameters as $key => $value)
    {
      if(!$this->verifyParameter($command, $key, $value))
        $ret = false;
    }
    
    return $ret;
  }
  
  public function verifyParameter($command, $key, $value) {
    $def = ServiceDefinition::getCommandDefinition($command);
    
    if(!isset($def['params']) || !isset($def['params'][$key]))
      throw new ClientException('Invalid command parameter: "' , $key . '".');
    
    $pdef = $def['params'][$key];
    switch($pdef['type'])
    {
      case 'integer':
      case 'int':
        if(!ParseUtils::isInt($value))
          throw new ClientException('Parameter value "' . $key . '" is not of required type (integer): "' . $value . '"');
        break;
      case 'float':
        if(!ParseUtils::isFloat($value))
          throw new ClientException('Parameter value "' . $key . '" is not of required type (float): "' . $value . '"');
        break;
      case 'boolean':
      case 'bool':
        if(!ParseUtils::isBool($value))
          throw new ClientException('Parameter value "' . $key . '" is not of required type (boolean): "' . $value . '"');
        break;
    }
    
    if(isset($pdef['min']) && $value < $pdef['min'])
      throw new ClientException('Parameter value "' . $key . '" is ' . $value . ', less than the accepted minimum value, ' . $pdef['min'] . '.');
    if(isset($pdef['max']) && $value > $pdef['max'])
      throw new ClientException('Parameter value "' . $key . '" is ' . $value . ', greater than the accepted maximum value, ' . $pdef['max'] . '.');
    if(isset($pdef['regex']) && !preg_match($pdef['regex'], $value))
      throw new ClientException('Parameter value "' . $key . '" is invalid. Value: "' . $value . '".');
    if(isset($pdef['maxLength']) && strlen($value) > $pdef['maxLength'])
      throw new ClientException('Parameter value "' . $key . '" is ' . $value . ', too long. Maximum length: ' . $pdef['maxLength'] . ' characters.');
    if(isset($pdef['minLength']) && strlen($value) < $pdef['minLength'])
      throw new ClientException('Parameter value "' . $key . '" is ' . $value . ', too short. Minimum length: ' . $pdef['minLength'] . ' characters.');
    
    return true;
  }
  
  private function getPrepend($command)
  {
    $def = ServiceDefinition::getCommandDefinition($command);
    $prepend = $def['prepend'];
    return $prepend;
  }
  
  private function getAppend($command)
  {
    $def = ServiceDefinition::getCommandDefinition($command);
    $append = $def['append'];
    return $append;
  }
  
  public static function getCallCount() {
    return self::$calls;
  }  
  
  public static function shutdown() {
    curl_close(self::$curlHandle);
  }
  
  public static function register_shutdown() {
    if(self::$shutdown_registered)
      return;
    register_shutdown_function(array('KAGClient\Client', 'shutdown'));
    self::$shutdown_registered = true;
  }
}

class ClientException extends \Exception {
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message);
    }
}
?>
