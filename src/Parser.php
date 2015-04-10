<?php

namespace PHPDoc;

use Monolog\Logger;

/**
 * Loads a PHP file and generates an AST for it.
 */
class Parser extends \PhpParser\NodeVisitorAbstract {

  var $logger;
  var $result;

  // current state
  var $file = null;
  var $current_namespace = null;
  var $current_class = null;
  var $current_uses = array();
  var $last_property = null;

  public function __construct(Logger $logger) {
    $this->logger = $logger;
  }

  /**
   * Load the given file.
   */
  public function load($file) {
    $this->logger->info("Parsing file '$file'");

    $this->file = $file;
    $this->parser = new \PhpParser\Parser(new \PhpParser\Lexer\Emulative);
    $this->traverser = new \PhpParser\NodeTraverser;
    $this->doc_comment_parser = new DocCommentParser();

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
        'line' => $node->getLine(),
        'file' => $this->file,
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
        'doc' => $this->doc_comment_parser->parse($node->getDocComment()),
        'line' => $node->getLine(),
        'file' => $this->file,
      ));
    }

    if ($node instanceof \PhpParser\Node\Stmt\Interface_) {
      $this->addInterface(array(
        'name' => $node->name,
        'extends' => $node->extends,
        'comment' => $node->getDocComment(),
        'doc' => $this->doc_comment_parser->parse($node->getDocComment()),
        'line' => $node->getLine(),
        'file' => $this->file,
      ));
    }

    if ($node instanceof \PhpParser\Node\Stmt\ClassMethod) {
      $this->addClassMethod(array(
        'byRef' => $node->byRef,
        'name' => $node->name,
        'params' => $node->params,
        'returnType' => $node->returnType,
        'public' => $node->isPublic(),
        'protected' => $node->isProtected(),
        'private' => $node->isPrivate(),
        'abstract' => $node->isAbstract(),
        'final' => $node->isFinal(),
        'static' => $node->isStatic(),
        'comment' => $node->getDocComment(),
        'doc' => $this->doc_comment_parser->parse($node->getDocComment()),
        'line' => $node->getLine(),
        'file' => $this->file,
      ));
    }

    if ($node instanceof \PhpParser\Node\Stmt\Property) {
      $this->last_property = $node;
    }

    if ($node instanceof \PhpParser\Node\Stmt\PropertyProperty) {
      $this->addClassProperty(array(
        'name' => $node->name,
        'default' => $node->default,
        'public' => $this->last_property->isPublic(),
        'protected' => $this->last_property->isProtected(),
        'private' => $this->last_property->isPrivate(),
        'static' => $this->last_property->isStatic(),
        'comment' => $node->getDocComment(),
        'doc' => $this->doc_comment_parser->parse($node->getDocComment()),
        'line' => $node->getLine(),
        'file' => $this->file,
      ));
    }

    // $this->logger->info(get_class($node));
  }

  function addNamespace($data) {
    $this->current_namespace = $data['namespace'];
    $this->current_class = null;
    $this->current_interface = null;
    $this->last_property = null;
    $this->current_uses = array();

    if (!isset($result['namespaces'][$this->current_namespace])) {
      $this->result['namespaces'][$this->current_namespace] = array(
        'classes' => array(),
        'interfaces' => array(),
      );
    }
  }

  function addClass($data) {
    $formatted = $data;
    $formatted['comment'] = ($data['comment'] ? $data['comment']->getReformattedText() : null);
    $formatted['extends'] = ($data['extends'] ? $data['extends']->toString() : null);
    $formatted['uses'] = $this->current_uses;
    $formatted['methods'] = array();
    $formatted['properties'] = array();

    $formatted['implements'] = array();
    foreach ($data['implements'] as $i) {
      $formatted['implements'][] = $i->toString();
    }

    $this->result['namespaces'][$this->current_namespace]['classes'][$data['name']] = $formatted;

    $this->current_class = $data['name'];
    $this->current_interface = null;
  }

  function addInterface($data) {
    $formatted = $data;
    $formatted['comment'] = ($data['comment'] ? $data['comment']->getReformattedText() : null);
    unset($formatted['extends']);
    $formatted['uses'] = $this->current_uses;
    $formatted['methods'] = array();
    $formatted['properties'] = array();

    $formatted['implements'] = array();
    foreach ($data['extends'] as $i) {
      $formatted['implements'][] = $i->toString();
    }

    $this->result['namespaces'][$this->current_namespace]['interfaces'][$data['name']] = $formatted;

    $this->current_interface = $data['name'];
    $this->current_class = null;
  }

  function addUse($use) {
    $this->current_uses[$use['alias']] = $use['name']->toString();
  }

  function addClassMethod($data) {
    if ($this->current_class) {
      $ref = $this->result['namespaces'][$this->current_namespace]['classes'][$this->current_class];
    } else {
      $ref = $this->result['namespaces'][$this->current_namespace]['interfaces'][$this->current_interface];
    }

    $formatted = $data;
    $formatted['comment'] = ($data['comment'] ? $data['comment']->getReformattedText() : null);
    $formatted['default'] = isset($data['default']) ? $this->formatDefault($data['default']) : null;

    $formatted['params'] = array();
    foreach ($data['params'] as $param) {
      // do we have a 'uses' reference for this type?
      if ($param->type) {
        foreach ($ref['uses'] as $alias => $name) {
          if ($alias === (string) $param->type) {
            $param->type = $name;
          }
        }
      }

      $formatted['params'][$param->name] = array(
        'type' => $param->type ? (string) $param->type : null,
        'byRef' => $param->byRef,
        'variadic' => $param->variadic,
        'default' => $param->default,
      );
    }

    if ($this->current_class) {
      $this->result['namespaces'][$this->current_namespace]['classes'][$this->current_class]['methods'][$data['name']] = $formatted;
    } else {
      $this->result['namespaces'][$this->current_namespace]['interfaces'][$this->current_interface]['methods'][$data['name']] = $formatted;
    }
  }

  function addClassProperty($data) {
    if ($this->current_class) {
      $ref = $this->result['namespaces'][$this->current_namespace]['classes'][$this->current_class];
    } else {
      $ref = $this->result['namespaces'][$this->current_namespace]['interfaces'][$this->current_interface];
    }

    $formatted = $data;
    $formatted['comment'] = ($data['comment'] ? $data['comment']->getReformattedText() : null);
    $formatted['default'] = isset($data['default']) ? $this->formatDefault($data['default']) : null;

    if ($this->current_class) {
      $this->result['namespaces'][$this->current_namespace]['classes'][$this->current_class]['properties'][$data['name']] = $formatted;
    } else {
      $this->result['namespaces'][$this->current_namespace]['interfaces'][$this->current_interface]['properties'][$data['name']] = $formatted;
    }
  }

  function formatDefault($default) {
    if (!$default) {
      return null;
    }

    if ($default instanceof \PhpParser\Node\Expr\Array_) {
      return array(
        'type' => 'array',
        'items' => count($default->items),
      );
    }

    if ($default instanceof \PhpParser\Node\Expr\ConstFetch) {
      return array(
        'type' => 'const',
        'name' => $default->name->toString(),
      );
    }

    if ($default instanceof \PhpParser\Node\Scalar\LNumber) {
      return array(
        'type' => 'number',
        'value' => $default->parse($default->value),
      );
    }

    if ($default instanceof \PhpParser\Node\Scalar\DNumber) {
      return array(
        'type' => 'number',
        'value' => $default->parse($default->value),
      );
    }

    if ($default instanceof \PhpParser\Node\Scalar\String) {
      return array(
        'type' => 'string',
        'value' => $default->value,
      );
    }

    return array(
      'type' => get_class($default),
    );
  }

}
