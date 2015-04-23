<?php

namespace PHPDoc2\Database;

use Monolog\Logger;

/**
 * Represents an intelligent documentation database that can be
 * queried as necessary.
 */
class Database extends AbstractDocElement {

  var $namespaces = array();

  function __construct($name, $data) {
    $this->name = $name;
    $this->data = $data;

    // sort
    ksort($data['namespaces']);

    foreach ($data['namespaces'] as $namespace => $namespace_data) {
      $ns = new DocNamespace($namespace, $namespace_data);
      $this->addNamespace($ns);
    }
  }

  function addNamespace($ns) {
    $this->namespaces[$ns->getName()] = $ns;
    $ns->setDatabase($this);
  }

  function getNamespaces() {
    return $this->namespaces;
  }

  function getTitle($options) {
    return "PHPDoc2 - " . $options['project_name'];
  }

  function getFilename() {
    return "index.html";
  }

  /**
   * Find the given class, or return {@code null} if none can be found.
   */
  function findClasslike($fqn, Logger $logger) {
    // split
    $bits = explode("\\", $fqn);
    $ns = array();
    for ($i = 0; $i < count($bits) - 1; $i++) {
      $ns[] = $bits[$i];
    }
    $relative_name = $bits[count($bits)-1];
    $ns = implode("\\", $ns);

    foreach ($this->getNamespaces() as $namespace) {
      if ($namespace->getName() == $ns) {
        foreach ($namespace->getClasses() as $class) {
          if ($class->getName() == $relative_name) {
            return $class;
          }
        }
        foreach ($namespace->getInterfaces() as $interface) {
          if ($interface->getName() == $relative_name) {
            return $interface;
          }
        }
      }
    }

    return null;
  }

  function getElementType() {
    throw new \Exception("Database has no element type");
  }

  /**
   * @return null
   */
  function getInheritedDocElement(Logger $logger, $key) {
    return null;
  }

  function getAllClasses() {
    $result = array();
    foreach ($this->namespaces as $ns) {
      $result = array_merge($result, $ns->getClasses());
    }
    return $result;
  }

  function getAllInterfaces() {
    $result = array();
    foreach ($this->namespaces as $ns) {
      $result = array_merge($result, $ns->getInterfaces());
    }
    return $result;
  }

}
