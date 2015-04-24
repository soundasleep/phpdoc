<?php

namespace PHPDoc2\Database;

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

  function getClass() {
    return $this;
  }

  /**
   * Get all known direct subclasses of this class.
   */
  function getDirectSubclasses(Logger $logger) {
    $result = array();
    foreach ($this->getDatabase()->getNamespaces() as $namespace) {
      foreach ($namespace->getClasses() as $class) {
        if ($class->getExtends($logger) == $this) {
          $result[] = $class;
        }
      }
    }
    return $result;
  }

  /**
   * Get the {@link DocClass} this class extends, or {@code null} if none
   * can be found in our database.
   */
  function getExtends(Logger $logger) {
    if ($this->data['extends']) {
      return $this->findClass($this->data['extends'], $logger);
    }
    return null;
  }

  /**
   * Does this class implement this interface?
   */
  function doesImplement(DocInterface $interface, Logger $logger) {
    return array_search($interface, $this->getParentInterfaces($logger)) !== false;
  }

}
