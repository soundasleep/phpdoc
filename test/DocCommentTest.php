<?php

namespace PHPDoc2\Test;

use PHPDoc2\Parser;
use Monolog\Logger;

abstract class DocCommentTest extends \PHPUnit_Framework_TestCase {

  abstract function getFile();

  function setUp() {
    $logger = new \Monolog\Logger("test");
    $logger->pushHandler(new SilentLogger());

    $parser = new Parser($logger);
    $this->result = $parser->load($this->getFile());
  }

}
