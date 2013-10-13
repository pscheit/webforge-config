<?php

namespace Webforge\Configuration;

use Webforge\Common\System\File;
use Webforge\Common\JS\JSONConverter;

class ConfigurationReader {

  protected $scope = array();

  /**
   * @return Webforge\Configuration\Configuration
   */
  public function fromPHPFile(File $phpFile) {
    extract($this->scope);

    require $phpFile;
      
    if (!isset($conf) || !is_array($conf)) {
      throw new ConfigurationReadingException(
        sprintf("Config-File '%s' does not define \$conf. Even if its empty it should define \$conf as empty array.", $phpFile)
      );
    }

    return $this->fromArray($conf);
  }

  /**
   * @return Webforge\Configuration\Configuration
   */
  public function fromJSONFile(File $file) {
    try {
      return $this->fromTraversable(
        JSONConverter::create()->parseFile($file)
      );

    } catch(\Exception $e) {
      throw new ConfigurationReadingException(
        sprintf("Config-File '%s' connot be read as json", $file), 0, $e
      );
    }
  }

  /**
   * @return Webforge\Configuration\Configuration
   */
  public function fromTraversable($conf) {
    return new Configuration((array) $conf);
  }

  /**
   * @return Webforge\Configuration\Configuration
   */
  public function fromArray(Array $conf) {
    return new Configuration($conf);
  }

  public function setScope(Array $scope) {
    $this->scope = $scope;
    return $this;
  }
}
