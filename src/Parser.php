<?php

namespace PHPDocParser;

use Monolog\Logger;

/**
 * Loads a PHP file and generates an AST for it.
 */
class Parser extends \PhpParser\NodeVisitorAbstract {

  var $logger;
  var $result;

  // current state
  var $current_namespace = null;
  var $current_class = null;
  var $current_uses = array();

  public function __construct(Logger $logger) {
    $this->logger = $logger;
  }

  public function load($file) {
    $this->logger->info("Parsing file '$file'");

    $this->parser = new \PhpParser\Parser(new \PhpParser\Lexer\Emulative);
    $this->traverser = new \PhpParser\NodeTraverser;

    // add your visitor
    $this->traverser->addVisitor($this);
    $this->result = array(
      'namespaces' => array(),
      'files' => array(),
    );

    // reset
    $this->current_namespace = "";

    $code = file_get_contents($file);
    try {
      $stmts = $this->parser->parse($code);
      $this->traverser->traverse($stmts);
    } catch (\PhpParser\Error $e) {
      // in the case of parse error, ignore this file and continue
      $this->logger->error("Could not parse '" . $dir . "/" . $entry . "': " . $e->getMessage());
    }

    $this->result['files'][] = $file;

    return $this->result;
  }

  function enterNode(\PhpParser\Node $node) {
    if ($node instanceof \PhpParser\Node\Stmt\Namespace_) {
      $this->addNamespace(array(
        'namespace' => $node->name->toString(),
      ));
    }

    if ($node instanceof \PhpParser\Node\Stmt\Use_) {
      foreach ($node->uses as $use) {
        $this->addUse(array(
          'name' => $use->name,
          'alias' => $use->alias,
        ));
      }
    }

    if ($node instanceof \PhpParser\Node\Stmt\Class_) {
      $this->addClass(array(
        'name' => $node->name,
        'extends' => $node->extends,
        'implements' => $node->implements,
        'abstract' => $node->isAbstract(),
        'final' => $node->isFinal(),
        'comment' => $node->getDocComment(),
      ));
    }

    $this->logger->info(get_class($node));
  }

  function addNamespace($data) {
    $this->current_namespace = $data['namespace'];
    $this->current_class = null;
    $this->current_uses = array();

    if (!isset($result['namespaces'][$this->current_namespace])) {
      $this->result['namespaces'][$this->current_namespace] = array(
        'classes' => array(),
      );
    }
  }

  function addClass($data) {
    $formatted = $data;
    $formatted['comment'] = ($data['comment'] ? $data['comment']->getReformattedText() : null);
    $formatted['extends'] = ($data['extends'] ? $data['extends']->toString() : null);
    $formatted['uses'] = $this->current_uses;

    $formatted['implements'] = array();
    foreach ($data['implements'] as $i) {
      $formatted['implements'][] = $i;
    }

    $this->result['namespaces'][$this->current_namespace]['classes'][$data['name']] = $formatted;

    $this->current_class = $data['name'];    
    $this->current_classes[] = $data['name'];
    print_r($this->current_classes);
  }

  function addUse($use) {
    $this->current_uses[$use['alias']] = $use['name']->toString();
  }

}