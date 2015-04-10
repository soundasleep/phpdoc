<?php

namespace PHPDoc\Database;

use Monolog\Logger;

/**
 * Represents a class.
 */
class DocClass extends DocClasslike {

  function getFilename() {
    return "class_" . $this->escape($this->getNamespace()->getName()) . "_" . $this->escape($this->getName()) . ".html";
  }

  function getElementType() {
    return "class";
  }

  /**
   * Get the class hierarchy as a list of {@link DocClass}es or strings.
   */
  function getClassHierarchy(Logger $logger) {
    if (isset($this->data['extends'])) {
      $class = $this->findClass($this->data['extends'], $logger);
      if ($class) {
        return array_merge($class->getClassHierarchy($logger), array($class));
      } else {
        return array($this->data['extends']);
      }
    } else {
      return array();
    }
  }

}
