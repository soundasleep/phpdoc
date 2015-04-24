<?php

namespace PHPDoc2\Test\Database;

use Monolog\Logger;
use PHPDoc2\Test\SilentLogger;
use PHPDoc2\Database\Database;
use PHPDoc2\Database\DocNamespace;
use PHPDoc2\Database\DocClass;
use PHPDoc2\Database\DocMethod;

class FindMethodTest extends SetupDatabase {

  function testFindMethod() {
    $method = $this->database->findMethod("Empty\Foo#foo", $this->logger);
    $this->assertNotNull($method);
    $this->assertEquals("foo", $method->getName());
  }

  function testFindMethodBrackets() {
    $method = $this->database->findMethod("Empty\Foo#foo()", $this->logger);
    $this->assertNotNull($method);
    $this->assertEquals("foo", $method->getName());
  }

  function testFindMethodArguments() {
    $method = $this->database->findMethod("Empty\Foo#foo(\$arg1)", $this->logger);
    $this->assertNotNull($method);
    $this->assertEquals("foo", $method->getName());
  }

  function testFindMethodArguments2() {
    $method = $this->database->findMethod("Empty\Foo#foo(\$arg1, \$arg2)", $this->logger);
    $this->assertNotNull($method);
    $this->assertEquals("foo", $method->getName());
  }

  function testFindMethodMissing() {
    $method = $this->database->findMethod("Empty\Foo#missing", $this->logger);
    $this->assertNull($method);
  }

  function testFindMethodMissingClass() {
    $method = $this->database->findMethod("Empty\Missing#missing", $this->logger);
    $this->assertNull($method);
  }

  function testFindMethodMissingNoNamespace() {
    $method = $this->database->findMethod("Foo#foo", $this->logger);
    $this->assertNull($method);
  }

}
