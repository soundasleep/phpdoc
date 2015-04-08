<?php

namespace PHPDoc\Database;

/**
 * Represents a method.
 */
class DocMethod extends AbstractDocElement {

  function __construct($name, $data) {
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

  function getFilename() {
    return "class_" . $this->escape($this->getClass()->getNamespace()->getName()) . "_" . $this->escape($this->getClass()->getName()) . ".html#" . $this->escape($this->getName());
  }

}
