<?php

namespace Webforge\Configuration;

use Mockery as m;

class DirectoryConfigurationReaderTest extends \Webforge\Code\Test\Base {
  
  public function setUp() {
    $this->chainClass = __NAMESPACE__ . '\\DirectoryConfigurationReader';
    parent::setUp();

    $this->dir = $this->getTestDirectory();
    $this->reader = m::mock(__NAMESPACE__.'\\ConfigurationReader');
    $this->directoryReader = new DirectoryConfigurationReader($this->dir, $this->reader);
  }

  public function testReaderThrowsAFileNotFoundExceptionForANonExistingFileToBeRead() {
    $this->setExpectedException('Webforge\Common\Exception\FileNotFoundException');
    $this->directoryReader->fromFile('non-existing.php');
  }

  public function testReaderThrowsAnInvalidArgumentException_ForFileThatHasNoExtensionKnown() {
    $this->setExpectedException('InvalidArgumentException');
    $this->directoryReader->fromFile('config.undefined');
  }

  public function testReaderUsesTheConfigurationReaderToReadAPHPFile() {
    $this->expectReaderToRead('fromPHPFile', 'config.php');
    
    $this->directoryReader->fromFile('config.php');
  }

  public function testReaderUsesTheConfigurationReaderToReadAJSONFile() {
    $this->expectReaderToRead('fromJSONFile', 'config.json');
    
    $this->directoryReader->fromFile('config.json');
  }

  protected function expectReaderToRead($methodName, $file) {
    $dir = $this->dir;
    $this->reader
      ->shouldReceive($methodName)
      ->once()
      ->with(m::on(function ($readFile) use ($dir, $file) {
        return (string) $readFile === (string) $dir->getFile($file);
      }))
      ->andReturn($this->createConfig())
    ;
  }

  protected function createConfig($config = array()) {
    return new Configuration($config);
  }
}
