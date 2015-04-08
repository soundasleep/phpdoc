<?php

namespace PHPDoc\Database;

/**
 * Represents a namespace.
 */
abstract class AbstractDocElement implements Visible {

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

}
