<?php

namespace Webforge\Configuration;

use Psc\DataInput;
use Psc\DataInputException;
use Webforge\Common\DataStructure\KeysMap;
use Webforge\Common\DataStructure\KeysNotFoundException;

/**
 *
 */
class Configuration {

  /**
   * @var Webforge\Common\DataStructure\KeysMap
   */
  protected $keysMap;
  
  /**
   * @param $values $keys can be any arbitrary name with . for namespaces in it the values can be mixed but mainly scalar should be used
   */
  public function __construct(Array $values) {
    $this->keysMap = new KeysMap();
    
    foreach ($values as $key=>$value) {
      if (mb_strpos($key,'.')) {
        $key = explode('.', $key);
      } else {
        $key = array($key);
      }
      
      $this->set($key, $value);  // translate dot paths correctly through constructor
    }
  }

  /**
   * Sets a value in the configuration
   * 
   * @param string|array $keys use with . seperated or as an array
   */
  public function set($keys, $value) {
    $this->keysMap->set($keys, $value);
    return $this;
  }

  /**
   * Gets a value in the configuration
   *
   * Does return $default for non defined keys
   * @param string|array $keys use with . seperated or as an array
   */
  public function get($keys, $default = NULL) {
    return $this->keysMap->get($keys, $default);
  }

  /**  
   * Returns a value from the configuration
   * 
   * throws an MissingConfigVariableException if key does not exist
   */
  public function req($keys) {
    try {
      return $this->keysMap->get($keys, KeysMap::DO_THROW_EXCEPTION);
    } catch (KeysNotFoundException $e) {
      throw MissingConfigVariableException::fromKeys($e->getKeys());
    }
  }

  /**
   * Returns the values of the configuration as nested Array
   * 
   * keys seperated with . will be expanded
   * @return array
   */
  public function toArray() {
    return $this->keysMap->toArray();
  }
}