<?php
namespace Common\Template;
/**
 * Simple template class. 
 *
 * @author William Schaller
 */
class Template {
  private $template;
  private $parameters;
  
  function __construct($template)
  {
    if($this->file_exists($template))
    {
      $this->template = $template;
      $this->parameters = array();
    }
    else
      throw new \Exception('Template file ' . $template . ' was not found in ' . __FILE__ . ', line ' . __LINE__ . '.');
  }
  
  function file_exists($path)
  {
    if(file_exists($path))
      return true;
    
    $includepath = explode(PATH_SEPARATOR, get_include_path());
    foreach($includepath as $check_path)
    {
      if(file_exists($check_path . DIRECTORY_SEPARATOR . $path))
        return true;
    }
    
    return false;
  }
  
  function __set($name, $value)
  {
	$this->setParam($name, $value);
  }
  
  function __get($name)
  {
    if(array_key_exists($name, $this->parameters))
      return $this->parameters[$name];     
  }
  
  public function __isset($name)      
  {
      return isset($this->parameters[$name]);
  }
  
  public function setParam($name, $value)
  {
	$this->parameters[$name] = $value;
  }

  public function __unset($name)
  {
      unset($this->parameters[$name]);
  }

  public function render($tidy = false)
  {
    ob_start();
    include($this->template);
    // Output buffer and strip excess whitespace (commented out for performances sake, re-enable for debug)
    //$output = preg_replace('(([ ]{2,}$)[\r\n]|([ ]{2,}$)[\n])', "\n", ob_get_contents());
    $output = ob_get_clean();
    if($tidy)
    {
      $config = array(
            'indent'         => true,
            'show-body-only' => true,
            'wrap'           => false);

      $tidyObj = new tidy;
      $tidyObj->parseString($output, $config, 'utf8');
      $tidyObj->cleanRepair();
      $output = $tidyObj;
    }
    echo $output;
  }
  
  public static function helper($template, $variables = array())
  {
	$template = new Template($template);
	foreach($variables as $key => $variable)
	{
	  $template->setParam($key, $variable);
	}
	$template->render();
  }
}
