<?php

namespace PHPDoc2\Test\Database;

use Monolog\Logger;
use PHPDoc2\Test\SilentLogger;
use PHPDoc2\Database\Database;
use PHPDoc2\Database\DocNamespace;
use PHPDoc2\Database\DocClass;
use PHPDoc2\Database\DocMethod;

class FindClasslikeTest extends SetupDatabase {

  function testFindClasslike() {
    $class = $this->database->findClasslike("Empty\\Foo", $this->logger);
    $this->assertNotNull($class);
    $this->assertEquals("Foo", $class->getName());
  }

  function testFindClasslikeMissing() {
    $class = $this->database->findClasslike("Missing\\Foo", $this->logger);
    $this->assertNull($class);
  }

  function testFindClasslikeNoNamespace() {
    $class = $this->database->findClasslike("Foo", $this->logger);
    $this->assertNull($class);
  }

}
