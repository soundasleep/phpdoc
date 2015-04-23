<?php

namespace PHPDoc2\Test;

use PHPDoc2\MyLogger;
use PHPDoc2\Parser;

class DuplicateEmptyNamespaceTest extends DocCommentTestMultiple {

  function getFiles() {
    return array(
      __DIR__ . "/Apis/EmptyNamespace.php",
      __DIR__ . "/Apis/DuplicateEmptyNamespace.php",
    );
  }

  function testNamespace() {
    $this->assertTrue(isset($this->result['namespaces']['']));
  }

  function testSingleName() {
    $this->assertEquals("EmptyNamespace", $this->result['namespaces']['']['classes']['EmptyNamespace']['name']);
  }

}
