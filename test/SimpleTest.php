<?php

namespace PHPDoc2\Test;

use PHPDoc2\MyLogger;
use PHPDoc2\Parser;

class SimpleTest extends DocCommentTest {

  function getFile() {
    return __DIR__ . "/Apis/SimpleTestApi.php";
  }

  function testNamespace() {
    $this->assertTrue(isset($this->result['namespaces']['PHPDoc2\Test\Apis']));
  }

  function testCommentTitle() {
    $this->assertEquals("A simple test API.", $this->result['namespaces']['PHPDoc2\Test\Apis']['classes']['SimpleTestApi']['doc']['title']);
  }

  function testCommentDescription() {
    $this->assertEquals("This is an extended description.", $this->result['namespaces']['PHPDoc2\Test\Apis']['classes']['SimpleTestApi']['doc']['description']);
  }

  function testCommentParams() {
    $this->assertEquals(array(), $this->result['namespaces']['PHPDoc2\Test\Apis']['classes']['SimpleTestApi']['doc']['params']);
  }

}
