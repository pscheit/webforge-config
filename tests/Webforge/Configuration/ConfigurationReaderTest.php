<?php

namespace Webforge\Configuration;

use Webforge\Common\System\File;
use Webforge\Framework\Package\SimplePackage;

class ConfigurationReaderTest extends \Webforge\Code\Test\Base {

  protected $reader;
  protected $file;
  
  public function setUp() {
    $this->chainClass = 'Webforge\\Configuration\\ConfigurationReader';
    parent::setUp();

    $this->reader = new $this->chainClass;

    $this->package = new PackageFake();
    $this->file = $this->getFile('config.php');
  }

  public function testSimpleReadingFromValuesAcceptance() {
    $this->reader->setScope(array(
      'package'=>$this->package
    ));

    $configuration = $this->reader->fromPHPFile($this->file);

    $this->assertConfigurationContents($configuration);
  }

  public function testCannotReadEmptyFile() {
    $this->setExpectedException('Webforge\Configuration\ConfigurationReadingException');

    $this->reader->fromPHPFile($this->getFile('empty.php'));
  }

  public function testReadFromEmptyArray() {
    $this->assertInstanceOf('Webforge\Configuration\Configuration', $this->reader->fromArray(array()));
  }

  public function testReadFromJSONFile() {
    $configuration = $this->reader->fromJSONFile($this->file->setExtension('json'));

    $this->assertConfigurationContents($configuration);
  }

  protected function assertConfigurationContents($configuration) {
    $this->assertInstanceOf('Webforge\Configuration\Configuration', $configuration);

    $this->assertEquals('ACME SuperBlog', $configuration->get('project.title'));
    $this->assertEquals('fake', $configuration->get('db.default.user'));
    $this->assertEquals('fake', $configuration->get('db.default.database'));

    $this->assertEquals('fake', $configuration->get('db.tests.user'));
    $this->assertEquals('fake_tests', $configuration->get('db.tests.database'));
  }
}
