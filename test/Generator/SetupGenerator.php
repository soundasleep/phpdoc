<?php

namespace PHPDoc2\Test\Generator;

use Monolog\Logger;
use PHPDoc2\HtmlGenerator;
use PHPDoc2\Test\Database\SetupDatabase;

abstract class SetupGenerator extends SetupDatabase {

  function setUp() {
    parent::setUp();

    $this->generator = new HtmlGenerator($this->database, array(), $this->logger, null);
  }

}
