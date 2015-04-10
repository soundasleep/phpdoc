<?php

namespace PHPDoc\Database;

/**
 * Represents a namespace.
 */
class DocNamespace extends AbstractDocElement {

  var $classes = array();

  function __construct($name, $data) {
    if (!$name) {
      throw new \InvalidArgumentException("'$name' is not a valid namespace name");
    }

    $this->name = $name;
    $this->data = $data;

    // sort
    ksort($data['classes']);

    foreach ($data['classes'] as $class => $class_data) {
      $obj = new DocClass($class, $class_data);
      $this->addClass($obj);
    }
  }

  function addClass($class) {
    $this->classes[] = $class;
    $class->setNamespace($this);
  }

  function getClasses() {
    return $this->classes;
  }

  function setDatabase($database) {
    $this->database = $database;
  }

  function getDatabase() {
    return $this->database;
  }

  function getTitle($options) {
    return "PHPDoc - " . $this->getName();
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

}
