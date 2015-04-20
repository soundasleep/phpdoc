<?php

namespace PHPDoc2\Test;

use PHPDoc2\MyLogger;
use PHPDoc2\Parser;

class AdvancedTest extends DocCommentTest {

  function getFile() {
    return __DIR__ . "/Apis/AdvancedTestApi.php";
  }

  function testNamespace() {
    $this->assertTrue(isset($this->result['namespaces']['PHPDoc2\Test\Apis']));
  }

  function testCommentTitle() {
    $this->assertEquals("An advanced test API.", $this->result['namespaces']['PHPDoc2\Test\Apis']['classes']['AdvancedTestApi']['doc']['title']);
  }

  function testCommentDescription() {
    $this->assertEquals("This is an extended description.\nIt spans multiple lines.", $this->result['namespaces']['PHPDoc2\Test\Apis']['classes']['AdvancedTestApi']['doc']['description']);
  }

  function testCommentParams() {
    $this->assertEquals(array(
      'currency' => 'the currency to use',
    ), $this->result['namespaces']['PHPDoc2\Test\Apis']['classes']['AdvancedTestApi']['doc']['params']);
  }

}
