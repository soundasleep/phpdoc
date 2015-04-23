<?php

namespace PHPDoc2\Test;

use PHPDoc2\MyLogger;
use PHPDoc2\Parser;

class EmptyNamespaceTest extends DocCommentTest {

  function getFile() {
    return __DIR__ . "/Apis/EmptyNamespace.php";
  }

  function testNamespace() {
    $this->assertTrue(isset($this->result['namespaces']['']));
  }

  function testCommentTitle() {
    $this->assertEquals("An empty namespace.", $this->result['namespaces']['']['classes']['EmptyNamespace']['doc']['title']);
  }

  function testCommentDescription() {
    $this->assertEquals("", $this->result['namespaces']['']['classes']['EmptyNamespace']['doc']['description']);
  }

}
