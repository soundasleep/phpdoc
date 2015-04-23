<?php

namespace PHPDoc2\Test;

use PHPDoc2\MyLogger;
use PHPDoc2\Parser;

/**
 * Testing loading a collection of files rather than just one.
 */
abstract class DocCommentTestMultiple extends \PHPUnit_Framework_TestCase {

  function getFile() {
    throw new Exception("Should not call #getFile()");
  }

  abstract function getFiles();

  function setUp() {
    $logger = new \Monolog\Logger("test");
    $logger->pushHandler(new SilentLogger());

    $parser = new Parser($logger);
    $this->result = $parser->load($this->getFiles());
  }

}
