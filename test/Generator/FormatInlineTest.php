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

  function testMissingLink() {
    $method = $this->database->findMethod("Empty\Foo#foo", $this->logger);
    $this->assertEquals(
      '#bar',
      $this->generator->formatInline($method, "{@link #bar()}"));
  }

  function testLinkWithoutParens() {
    $method = $this->database->findMethod("Empty\Foo#foo", $this->logger);
    $this->assertEquals(
      '<a href="class_Empty_Foo.html#foo" class="">#foo()</a>',
      $this->generator->formatInline($method, "{@link #foo}"));
  }

  function testLinkWithText() {
    $method = $this->database->findMethod("Empty\Foo#foo", $this->logger);
    $this->assertEquals(
      '<a href="class_Empty_Foo.html#foo" class="">hello</a>',
      $this->generator->formatInline($method, "{@link #foo() hello}"));
  }

  function testClassMethodLinkWithText() {
    $method = $this->database->findMethod("Empty\Foo#foo", $this->logger);
    $this->assertEquals(
      '<a href="class_Empty_Foo.html#foo" class="">hello</a>',
      $this->generator->formatInline($method, "{@link Foo#foo() hello}"));
  }

  function testClassLinkWithText() {
    $method = $this->database->findMethod("Empty\Foo#foo", $this->logger);
    $this->assertEquals(
      '<a href="class_Empty_Foo.html" class="">hello</a>',
      $this->generator->formatInline($method, "{@link Foo hello}"));
  }

  function testMissingLinkText() {
    $method = $this->database->findMethod("Empty\Foo#foo", $this->logger);
    $this->assertEquals(
      'baz',
      $this->generator->formatInline($method, "{@link #bar() baz}"));
  }

  function testLinkPlural() {
    $method = $this->database->findMethod("Empty\Foo#foo", $this->logger);
    $this->assertEquals(
      '<a href="class_Empty_Foo.html#foo" class="">#foo()s</a>',
      $this->generator->formatInline($method, "{@link #foo()}s"));
  }

  function testMissingLinkTextPlural() {
    $method = $this->database->findMethod("Empty\Foo#foo", $this->logger);
    $this->assertEquals(
      'foos',
      $this->generator->formatInline($method, "{@link #bar() foo}s"));
  }

}
