<?php

namespace PHPDoc2\Test\Database;

use Monolog\Logger;
use PHPDoc2\Test\SilentLogger;
use PHPDoc2\Database\Database;
use PHPDoc2\Database\DocNamespace;
use PHPDoc2\Database\DocClass;
use PHPDoc2\Database\DocMethod;

class OverridesTest extends SetupDatabase {

  function testNotOverrides() {
    $method = $this->database->findMethod("Empty\Foo#foo", $this->logger);
    $this->assertNotNull($method);
    $this->assertEquals("foo", $method->getName());
    $this->assertNull($method->overrides($this->logger));
    $this->assertEquals("Found it", $method->getDoc('title'));
  }

  function testOverridesInherited() {
    $method = $this->database->findMethod("Empty\Bar#foo", $this->logger);
    $this->assertNotNull($method);
    $this->assertEquals("foo", $method->getName());
    $this->assertNull($method->overrides($this->logger));
  }

  function testOverridesDirectly() {
    $method = $this->database->findMethod("Empty\Baz#foo", $this->logger);
    $this->assertNotNull($method);
    $this->assertEquals("foo", $method->getName());
    $this->assertEquals("Overridden", $method->getDoc('title'));
    $overrides = $method->overrides($this->logger);
    $this->assertNotNull($overrides);
    $this->assertEquals("foo", $overrides->getName());
    $this->assertEquals("Foo", $overrides->getClass()->getName());
  }

}
