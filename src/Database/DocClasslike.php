<?php

namespace PHPDoc2\Database;

use Monolog\Logger;

/**
 * Represents a class or interface.
 */
abstract class DocClasslike extends AbstractDocElement {

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
    if (!isset($data['methods'])) {
      $data['methods'] = array();
    }
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
    return "PHPDoc2 - " . $options['project_name'];
  }

  /**
   * Get <i>just</i> the inherited methods for this class,
   * ignoring any methods that are already present.
   */
  function getInheritedMethods(Logger $logger) {
    $methods = $this->getAllMethods($logger);
    $our_methods = $this->getMethods();

    $result = array();
    foreach ($methods as $name => $data) {
      if (!isset($our_methods[$name])) {
        $result[$name] = $data;
      }
    }

    return $result;
  }

  /**
   * Get <i>all</i> methods, including inherited methods,
   * that this class supports.
   */
  function getAllMethods(Logger $logger) {
    $methods = $this->methods;

    if (isset($this->data['extends'])) {
      $class = $this->findClass($this->data['extends'], $logger);

      if ($class) {
        $methods = array_merge($methods, $class->getMethods());
        $methods = array_merge($methods, $class->getInheritedMethods($logger));
      } else {
        // $logger->warn("Could not find parent class '" . $this->data['extends'] . "' for '" . $this->getName() . "'");
      }
    }

    return $methods;
  }

  /**
   * Try to find the given class, either by fully qualified name or by
   * relative reference within the same namespace.
   *
   * @return the {@link DocClass} or {@code false} if none could be found
   */
  function findClass($name, Logger $logger) {
    // try uses
    $class = false;
    if (isset($this->data['uses']) && isset($this->data['uses'][$name])) {
      $class = $this->getDatabase()->findClasslike($this->data['uses'][$name], $logger);
    }
    if (!$class) {
      // try fqn
      $class = $this->getDatabase()->findClasslike($name, $logger);
    }
    if (!$class) {
      // try our local namespace
      $class = $this->getDatabase()->findClasslike($this->getNamespace()->getName() . "\\" . $name, $logger);
    }
    return $class;
  }

  /**
   * Get the parent interfaces as a list of {@link DocInterface}es or strings.
   */
  function getParentInterfaces(Logger $logger) {
    $result = array();
    if (isset($this->data['implements'])) {
      foreach ($this->data['implements'] as $name) {
        $class = $this->findClass($name, $logger);
        if ($class) {
          $result = array_merge($result, $class->getParentInterfaces($logger), array($class));
        } else {
          $result[] = $name;
        }
      }
    }
    if (isset($this->data['extends'])) {
      $extends = $this->data['extends'];
      if (!is_array($extends)) {
        $extends = array($extends);
      }

      foreach ($extends as $name) {
        $class = $this->findClass($name, $logger);
        if ($class) {
          $result = array_merge($result, $class->getParentInterfaces($logger));
        }
      }
    }
    return array_unique($result);
  }

  function getMethod($key) {
    if (isset($this->methods[$key])) {
      return $this->methods[$key];
    }
    return null;
  }

  /**
   * Get all known classes that implement this interface.
   * By default, returns empty.
   */
  function getKnownImplementations(Logger $logger) {
    return array();
  }

  /**
   * Get all known direct subclasses of this class.
   * By default, returns empty.
   */
  function getDirectSubclasses(Logger $logger) {
    return array();
  }

  /**
   * Get the {@link DocClasslike} that provides the inherited documentation
   * for the given key, as from its parent classes or interfaces, or
   * return {@code null}.
   */
  function getInheritedDocElement(Logger $logger, $key) {
    if ($this->getDoc($key)) {
      return $this;
    }
    if ($this instanceof DocClass) {
      foreach ($this->getClassHierarchy($logger) as $parent_class) {
        if (!is_string($parent_class)) {
          if ($parent_class->getDoc($key)) {
            return $parent_class;
          }
        }
      }
    }
    foreach ($this->getParentInterfaces($logger) as $parent_interface) {
      if (!is_string($parent_interface)) {
        if ($parent_interface->getDoc($key)) {
          return $parent_interface;
        }
      }
    }
    return null;
  }

  /**
   * Try to find a {@link DocMethod} on this class name,
   * or {@code null} if none can be found.
   * Will also look through inherited methods.
   */
  function findMethod($fqn, Logger $logger) {
    // ignore any arguments
    $bits = explode("(", $fqn, 2);
    foreach ($this->getAllMethods($logger) as $method) {
      if ($method->getName() == $bits[0]) {
        return $method;
      }
    }

    return null;
  }

}
