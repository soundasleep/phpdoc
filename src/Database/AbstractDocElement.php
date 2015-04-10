<?php

namespace PHPDoc\Database;

/**
 * Represents a namespace.
 */
abstract class AbstractDocElement implements Visible {

  /**
   * @return the type of doc element to display in {@link #getModifiers()},
   *      e.g. 'function', 'class', 'interface'
   */
  abstract function getElementType();

  /**
   * Make the given string filename-safe.
   * @param $s any arbitrary string
   */
  function escape($s) {
    return preg_replace("#[^a-zA-Z0-9_]#", "_", $s);
  }

  function getName() {
    return $this->name;
  }

  function getData() {
    return $this->data;
  }

  function getDoc($key) {
    if (isset($this->data['doc'][$key]) && $this->data['doc'][$key]) {
      return $this->data['doc'][$key];
    }
    // we can't find anything
    return null;
  }

  function getModifiers() {
    $modifiers = array();
    if (isset($this->data['public']) && $this->data['public']) {
      $modifiers[] = "public";
    }
    if (isset($this->data['protected']) && $this->data['protected']) {
      $modifiers[] = "protected";
    }
    if (isset($this->data['private']) && $this->data['private']) {
      $modifiers[] = "private";
    }
    if (isset($this->data['abstract']) && $this->data['abstract']) {
      $modifiers[] = "abstract";
    }
    if (isset($this->data['static']) && $this->data['static']) {
      $modifiers[] = "static";
    }
     if (isset($this->data['final']) && $this->data['final']) {
      $modifiers[] = "final";
    }
    $modifiers[] = $this->getElementType();
    return implode(" ", $modifiers);
  }

  var $warnings;

  function addWarning($s) {
    $this->warnings[] = $s;
  }

  function getWarnings() {
    return $this->warnings;
  }

}
