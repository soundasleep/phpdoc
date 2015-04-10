<?php

namespace PHPDoc\Test;

use PHPDoc\MyLogger;
use PHPDoc\Parser;

class MultipleParamsTest extends DocCommentTest {

  function getFile() {
    return __DIR__ . "/Apis/MultipleParamsApi.php";
  }

  function testNamespace() {
    $this->assertTrue(isset($this->result['namespaces']['PHPDoc\Test\Apis']));
  }

  function testCommentTitle() {
    $this->assertEquals("An advanced test API.", $this->result['namespaces']['PHPDoc\Test\Apis']['classes']['MultipleParamsApi']['doc']['title']);
  }

  function testCommentDescription() {
    $this->assertEquals("This is an extended description.\nIt spans multiple lines.", $this->result['namespaces']['PHPDoc\Test\Apis']['classes']['MultipleParamsApi']['doc']['description']);
  }

  function testCommentParams() {
    $this->assertEquals(array(
      'currency' => 'the currency to use',
      'foo' => 'another parameter',
      'bar' => 'another parameter across multiple lines',
    ), $this->result['namespaces']['PHPDoc\Test\Apis']['classes']['MultipleParamsApi']['doc']['params']);
  }

  function testCommentSee() {
    $this->assertEquals(array(
      'AdvancedTestApi' => false,
    ), $this->result['namespaces']['PHPDoc\Test\Apis']['classes']['MultipleParamsApi']['doc']['see']);
  }

  function testCommentWithJustOneTag() {
    $this->assertFalse(!!$this->result['namespaces']['PHPDoc\Test\Apis']['classes']['MultipleParamsApi']['methods']['getEndpoint']['doc']['title'], "The @return tag should not be interpreted as title");

    $this->assertEquals(array(
      'a string',
    ), $this->result['namespaces']['PHPDoc\Test\Apis']['classes']['MultipleParamsApi']['methods']['getEndpoint']['doc']['return']);
  }

  function testBothReturnTags() {
    $this->assertEquals(array(
      'one',
      'two'
    ), $this->result['namespaces']['PHPDoc\Test\Apis']['classes']['MultipleParamsApi']['methods']['test']['doc']['return']);
  }

}
