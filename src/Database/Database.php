<?php

namespace PHPDoc\Database;

/**
 * Represents an intelligent documentation database that can be
 * queried as necessary.
 */
class Database extends AbstractDocElement {

  var $namespaces = array();

  function __construct($name, $data) {
    $this->name = $name;
    $this->data = $data;

    // sort
    ksort($data['namespaces']);

    foreach ($data['namespaces'] as $namespace => $namespace_data) {
      $ns = new DocNamespace($namespace, $namespace_data);
      $this->addNamespace($ns);
    }
  }

  function addNamespace($ns) {
    $this->namespaces[] = $ns;
    $ns->setDatabase($this);
  }

  function getNamespaces() {
    return $this->namespaces;
  }

  function getTitle($options) {
    return "PHPDoc - " . $options['project_name'];
  }

  function getFilename() {
    return "index.html";
  }

}
