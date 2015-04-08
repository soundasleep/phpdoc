<?php

namespace PHPDoc\Database;

/**
 * Represents a class.
 */
class DocClass extends AbstractDocElement {

  var $methods = array();

  function __construct($name, $data) {
    $this->name = $name;
    $this->data = $data;

    // sort
    ksort($data['methods']);

    foreach ($data['methods'] as $method => $method_data) {
      $obj = new DocMethod($method, $method_data);
      $this->addMethod($obj);
    }
  }

  function addMethod($obj) {
    $this->methods[] = $obj;
    $obj->setClass($this);
  }

  function getMethods() {
    return $this->methods;
  }

  function setNamespace($ns) {
    $this->namespace = $ns;
  }

  function getNamespace() {
    return $this->namespace;
  }

  function getTitle($options) {
    return "PHPDoc - " . $options['project_name'];
  }

  function getFilename() {
    return "class_" . $this->escape($this->getNamespace()->getName()) . "_" . $this->escape($this->getName()) . ".html";
  }

}
