<?php

namespace Webforge\Configuration;

class MissingConfigVariableException extends \Webforge\Common\Exception {
  
  /**
   * better use the getter
   */
  public $keys;
  
  public static function fromKeys(Array $keys) {
    $e = new static(sprintf("the config variable '%s' cannot be found.", implode('.', $keys)));
    $e->keys = $keys;
    return $e;
  }

  public function getKeys() {
    return $this->keys;
  }
}
