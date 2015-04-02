<?php

namespace PHPDocParser\Test;

use PHPDocParser\MyLogger;
use PHPDocParser\Parser;

class SimpleTest extends DocCommentTest {

  function getFile() {
    return __DIR__ . "/Apis/SimpleTestApi.php";
  }

  function testNamespace() {
    $this->assertTrue(isset($this->result['namespaces']['PHPDocParser\Test\Apis']));
  }

  function testCommentTitle() {
    $this->assertEquals("A simple test API.", $this->result['namespaces']['PHPDocParser\Test\Apis']['classes']['SimpleTestApi']['doc']['title']);
  }

  function testCommentDescription() {
    $this->assertEquals("This is an extended description.", $this->result['namespaces']['PHPDocParser\Test\Apis']['classes']['SimpleTestApi']['doc']['description']);
  }

  function testCommentParams() {
    $this->assertEquals(array(), $this->result['namespaces']['PHPDocParser\Test\Apis']['classes']['SimpleTestApi']['doc']['params']);
  }

}
