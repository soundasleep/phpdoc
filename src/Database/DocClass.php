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
