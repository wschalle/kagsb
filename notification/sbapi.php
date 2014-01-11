<?php

class Response {
  private $_properties = array();
  
  function __construct() {
  }
  
  function __set($name, $value) {
    $this->_properties[$name] = $value;
  }
  
  function __isset($name) {
    return isset($this->_properties[$name]);
  }
  
  function __get($name) {
    if(isset($this->_properties[$name]))
      return $this->_properties[$name];
    else
      return null;
  }
  
  function setStatus($status, $message = "") {
    switch($status) {
      case 'error':
        $this->status = 'error';
        $this->message = $message;
        break;
      case 'success':
        $this->status = 'success';
        $this->message = $message;
        break;
      default:
        throw new InvalidArgumentException("Invalid status: " . $status);
    }
  }
  
  function getJson() {
    return json_encode($this->_properties);
  }
  
  function sendJson() {
    echo $this->getJson();
    exit;
  }
}