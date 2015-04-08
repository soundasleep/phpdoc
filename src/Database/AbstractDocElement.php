<?php

namespace PHPDoc\Database;

/**
 * Represents a namespace.
 */
abstract class AbstractDocElement implements Visible {

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

  function getDocTitle() {
    if (isset($this->data['doc']['title']) && $this->data['doc']['title']) {
      return $this->data['doc']['title'];
    }
    // we can't find anything
    return null;
  }

  function getDocDescription() {
    if (isset($this->data['doc']['description']) && $this->data['doc']['description']) {
      return $this->data['doc']['description'];
    }
    // we can't find anything
    return null;
  }

  function getDocParams() {
    if (isset($this->data['doc']['params']) && $this->data['doc']['params']) {
      return $this->data['doc']['params'];
    }
    // we can't find anything
    return null;
  }

  var $warnings;

  function addWarning($s) {
    $this->warnings[] = $s;
  }

  function getWarnings() {
    return $this->warnings;
  }

}
