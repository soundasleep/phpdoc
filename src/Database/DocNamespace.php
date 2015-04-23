<?php

namespace PHPDoc2\Database;

use Monolog\Logger;

/**
 * Represents a namespace.
 */
class DocNamespace extends AbstractDocElement {

  var $classes = array();
  var $interfaces = array();

  function __construct($name, $data) {
    // $name may be empty!
    $this->name = $name;
    $this->data = $data;

    // sort
    if (!isset($data['classes'])) {
      $data['classes'] = array();
    }
    ksort($data['classes']);

    foreach ($data['classes'] as $class => $class_data) {
      $obj = new DocClass($class, $class_data);
      $this->addClass($obj);
    }

    // sort
    if (!isset($data['interfaces'])) {
      $data['interfaces'] = array();
    }
    ksort($data['interfaces']);

    foreach ($data['interfaces'] as $interface => $interface_data) {
      $obj = new DocInterface($interface, $interface_data);
      $this->addInterface($obj);
    }
  }

  function addClass($class) {
    $this->classes[] = $class;
    $class->setNamespace($this);
  }

  function getClasses() {
    return $this->classes;
  }

  function addInterface($interface) {
    $this->interfaces[] = $interface;
    $interface->setNamespace($this);
  }

  function getInterfaces() {
    return $this->interfaces;
  }

  function setDatabase($database) {
    $this->database = $database;
  }

  function getDatabase() {
    return $this->database;
  }

  function getTitle($options) {
    return "PHPDoc2 - " . $this->getPrintableName();
  }

  function getFilename() {
    return "namespace_" . $this->escape($this->getName()) . ".html";
  }

  function getChildNamespaces() {
    $result = array();

    foreach ($this->getDatabase()->getNamespaces() as $namespace) {
      if (substr($namespace->getName(), 0, strlen($this->getName()) + 1) == $this->getName() . "\\") {
        $result[$namespace->getName()] = $namespace;
      }
    }

    return $result;
  }

  function getElementType() {
    return "namespace";
  }

  /**
   * @return null
   */
  function getInheritedDocElement(Logger $logger, $key) {
    return null;
  }

  function getPrintableName() {
    if (!$this->getName()) {
      return "(none)";
    }
    return $this->getName();
  }

}
