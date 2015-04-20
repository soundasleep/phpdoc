<?php

namespace PHPDoc2\Database;

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

  /**
   * Get all known classes that implement this interface.
   */
  function getKnownImplementations(Logger $logger) {
    $result = array();
    foreach ($this->getDatabase()->getNamespaces() as $namespace) {
      foreach ($namespace->getClasses() as $class) {
        if ($class->doesImplement($this, $logger)) {
          $result[] = $class;
        }
      }
    }
    return $result;
  }

}
