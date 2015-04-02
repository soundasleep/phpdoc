<?php

namespace PHPDocParser\Test;

use PHPDocParser\MyLogger;
use PHPDocParser\Parser;

class MultipleParamsTest extends DocCommentTest {

  function getFile() {
    return __DIR__ . "/Apis/MultipleParamsApi.php";
  }

  function testNamespace() {
    $this->assertTrue(isset($this->result['namespaces']['PHPDocParser\Test\Apis']));
  }

  function testCommentTitle() {
    $this->assertEquals("An advanced test API.", $this->result['namespaces']['PHPDocParser\Test\Apis']['classes']['MultipleParamsApi']['doc']['title']);
  }

  function testCommentDescription() {
    $this->assertEquals("This is an extended description.\nIt spans multiple lines.", $this->result['namespaces']['PHPDocParser\Test\Apis']['classes']['MultipleParamsApi']['doc']['description']);
  }

  function testCommentParams() {
    $this->assertEquals(array(
      'currency' => 'the currency to use',
      'foo' => 'another parameter',
      'bar' => 'another parameter across multiple lines',
    ), $this->result['namespaces']['PHPDocParser\Test\Apis']['classes']['MultipleParamsApi']['doc']['params']);
  }

  function testCommentSee() {
    $this->assertEquals(array(
      'AdvancedTestApi',
    ), $this->result['namespaces']['PHPDocParser\Test\Apis']['classes']['MultipleParamsApi']['doc']['see']);
  }

}
