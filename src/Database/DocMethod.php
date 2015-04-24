<?php

namespace PHPDoc2\Database;

use Monolog\Logger;

/**
 * Represents a method.
 */
class DocMethod extends AbstractDocElement {

  function __construct($name, $data) {
    if (!$name) {
      throw new \InvalidArgumentException("'$name' is not a valid method name");
    }

    $this->name = $name;
    $this->data = $data;
  }

  function setClass($class) {
    $this->class = $class;
  }

  function getClass() {
    return $this->class;
  }

  function getTitle($options) {
    throw new \Exception("DocMethods are not generated to HTML");
  }

  function getDatabase() {
    return $this->getClass()->getDatabase();
  }

  function getNamespace() {
    return $this->getClass()->getNamespace();
  }

  function getFilename() {
    if ($this->getClass() instanceof DocClass) {
      $type = "class";
    } else if ($this->getClass() instanceof DocInterface) {
      $type = "interface";
    } else {
      throw new \Exception("Unknown class instance " . get_class($this->getClass()));
    }
    return $type . "_" . $this->escape($this->getClass()->getNamespace()->getName()) . "_" . $this->escape($this->getClass()->getName()) . ".html#" . $this->escape($this->getName());
  }

  function getPrintableName() {
    $params = array();
    foreach ($this->data['params'] as $name => $data) {
      $value = '$' . $name;
      if (isset($data['default'])) {
        switch ($data['default']['type']) {
          case "string":
            $value .= " = \"" . htmlspecialchars($data['default']['value']) . "\"";
            break;

          case "number":
            $value .= " = " . $data['default']['value'];
            break;

          case "array":
            $value .= " = array(";
            if ($data['default']['items']) {
              $value .= "...";
            }
            $value .= ")";
            break;

          case "const":
            // e.g. 'null'
            $value .= " = " . $data['default']['name'];
            break;

        }
      }
      $params[] = $value;
    }
    return $this->getName() . "(" . implode(", ", $params) . ")";
  }

  function getElementType() {
    return "function";
  }

  /**
   * Get the {@link DocMethod} that provides the inherited documentation
   * for the given key, as from its parent classes or interfaces, or
   * return {@code null}.
   */
  function getInheritedDocElement(Logger $logger, $key) {
    if ($this->getDoc($key)) {
      return $this;
    }
    if ($this->getClass() instanceof DocClass) {
      foreach ($this->getClass()->getClassHierarchy($logger) as $parent_class) {
        if (!is_string($parent_class)) {
          $method = $parent_class->getMethod($this->getName());
          if ($method && $method->getDoc($key)) {
            return $method;
          }
        }
      }
    }
    foreach ($this->getClass()->getParentInterfaces($logger) as $parent_interface) {
      if (!is_string($parent_interface)) {
        $method = $parent_interface->getMethod($this->getName());
        if ($method && $method->getDoc($key)) {
          return $method;
        }
      }
    }
    return null;
  }

  /**
   * Try to find the given class, either by fully qualified name or by
   * relative reference within the same namespace.
   *
   * @return the {@link DocClass} or {@code false} if none could be found
   */
  function findClass($name, Logger $logger) {
    return $this->getClass()->findClass($name, $logger);
  }

  function getDefaults() {
    $result = array();
    foreach ($this->data['params'] as $key => $value) {
      if (isset($value['default']) && $value['default']) {
        $result[$key] = $value['default'];
      }
    }
    return $result;
  }

}
