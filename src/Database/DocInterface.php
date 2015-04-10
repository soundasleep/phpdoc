<?php

namespace PHPDoc\Database;

use Monolog\Logger;

/**
 * Represents an interface.
 */
class DocInterface extends DocClasslike {

  function getFilename() {
    return "interface_" . $this->escape($this->getNamespace()->getName()) . "_" . $this->escape($this->getName()) . ".html";
  }

  function getElementType() {
    return "interface";
  }

}
