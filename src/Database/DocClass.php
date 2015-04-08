<?php

namespace PHPDoc\Database;

use Monolog\Logger;

/**
 * Represents a class.
 */
class DocClass extends AbstractDocElement {

  var $methods = array();

  function __construct($name, $data) {
    if (!$name) {
      throw new \InvalidArgumentException("'$name' is not a valid class name");
    }

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
    $this->methods[$obj->getName()] = $obj;
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

  function getDatabase() {
    return $this->namespace->getDatabase();
  }

  function getTitle($options) {
    return "PHPDoc - " . $options['project_name'];
  }

  function getFilename() {
    return "class_" . $this->escape($this->getNamespace()->getName()) . "_" . $this->escape($this->getName()) . ".html";
  }

  function getInheritedMethods(Logger $logger) {
    $methods = array();

    if ($this->data['extends']) {
      // try fqn
      $class = $this->getDatabase()->findClass($this->data['extends'], $logger);
      if (!$class) {
        // try our local namespace
        $class = $this->getDatabase()->findClass($this->getNamespace()->getName() . "\\" . $this->data['extends'], $logger);
      }

      if ($class) {
        $methods = array_merge($methods, $class->getMethods());
        $methods = array_merge($methods, $class->getInheritedMethods($logger));
      } else {
        $logger->warn("Could not find parent class '" . $this->data['extends'] . "' for '" . $this->getName() . "'");
      }
    }

    $our_methods = $this->getMethods();

    $result = array();
    foreach ($methods as $name => $data) {
      if (!isset($our_methods[$name])) {
        $result[$name] = $data;
      }
    }

    return $result;
  }

}
