<?php

namespace PHPDoc2\Test\Generator;

use Monolog\Logger;
use PHPDoc2\Test\SilentLogger;
use PHPDoc2\Database\Database;
use PHPDoc2\Database\DocNamespace;
use PHPDoc2\Database\DocClass;
use PHPDoc2\Database\DocMethod;

class FormatInlineTest extends SetupGenerator {

  function testText() {
    $method = $this->database->findMethod("Empty\Foo#foo", $this->logger);
    $this->assertEquals(
      "Hello",
      $this->generator->formatInline($method, "Hello"));
  }

  function testLink() {
    $method = $this->database->findMethod("Empty\Foo#foo", $this->logger);
    $this->assertEquals(
      '<a href="class_Empty_Foo.html#foo" class="">#foo()</a>',
      $this->generator->formatInline($method, "{@link #foo()}"));
  }

  function testLinkWithoutParens() {
    $method = $this->database->findMethod("Empty\Foo#foo", $this->logger);
    $this->assertEquals(
      '<a href="class_Empty_Foo.html#foo" class="">#foo()</a>',
      $this->generator->formatInline($method, "{@link #foo}"));
  }

}
