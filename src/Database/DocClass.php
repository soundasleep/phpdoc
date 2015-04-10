<?php

namespace PHPDoc\Database;

use Monolog\Logger;

/**
 * Represents a class.
 */
class DocClass extends AbstractDocElement {

  var $methods = array();

  /**
   * Constructor.
   * @param $name the name of the class
   * @param $data the data for the class, in JSON format
   * @throws InvalidArgumentException if {@code $name} is empty
   * @see DocMethod
   * @see DocNamespace parent class
   */
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
      $class = $this->findClass($this->data['extends'], $logger);

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

  function getElementType() {
    return "class";
  }

  /**
   * Try to find the given class, either by fully qualified name or by
   * relative reference within the same namespace.
   *
   * @return the {@link DocClass} or {@code false} if none could be found
   */
  function findClass($name, Logger $logger) {
    // try fqn
    $class = $this->getDatabase()->findClass($name, $logger);
    if (!$class) {
      // try our local namespace
      $class = $this->getDatabase()->findClass($this->getNamespace()->getName() . "\\" . $name, $logger);
    }
    return $class;
  }

  /**
   * Get the class hierarchy as a list of {@link DocClass}es or strings.
   */
  function getClassHierarchy(Logger $logger) {
    if ($this->data['extends']) {
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
