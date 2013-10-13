<?php

namespace Webforge\Configuration;

use Webforge\Common\System\Dir;
use Webforge\Common\Exception\FileNotFoundException;
use InvalidArgumentException;

class DirectoryConfigurationReader {

  protected $dir;

  protected $reader;

  public function __construct(Dir $dir, ConfigurationReader $reader) {
    // yagni: reader should be an array of readers for every file type?
    // this is VERY similar to symfony here
    $this->dir = $dir;
    $this->reader = $reader;
  }

  /**
   * Reads the configuration from a file in the directory
   * 
   * the type of the configuration is determined from the extension
   * @param string $url relative to dir without slash before with slashes
   */
  public function fromFile($url) {
    $file = $this->dir->getFile($url);

    if (!$file->exists()) {
      throw FileNotFoundException::fromFile($file);
    }

    $extension = mb_strtolower($file->getExtension());

    if ($extension === 'php') {
      return $this->reader->fromPHPFile($file);
    } elseif ($extension === 'json') {
      return $this->reader->fromJSONFile($file);
    } else {
      throw new InvalidArgumentException(sprintf("The extension '%s' is not known to be read as configuration", $extension));
    }

    return $file;
  }
}
