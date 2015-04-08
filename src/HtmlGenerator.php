<?php

namespace PHPDoc;

class HtmlGenerator {

  var $database;
  var $options;
  var $logger;
  var $output;

  function __construct($database, $options, $logger, $output) {
    $this->database = $database;
    $this->options = $options;
    $this->logger = $logger;
    $this->output = $output;
  }

  function generate() {
    if (!file_exists($this->output)) {
      mkdir($this->output);
    }
    if (!is_dir($this->output)) {
      throw new \Exception("'" . $this->output . "' is not a directory");
    }

    // TODO delete all files within it?

    $this->generateFile("index", "index");

    // generate all namespaces
    foreach ($this->database['namespaces'] as $namespace => $data) {
      $this->generateFile("namespace", "namespace_" . $this->escape($namespace), array('namespace' => $namespace));

      // generate all classes
      foreach ($data['classes'] as $class => $class_data) {
        $this->generateFile("class", "class_" . $this->escape($namespace) . "_" . $this->escape($class), array('namespace' => $namespace, 'class' => $class));
      }

    }

    // copy over CSS
    copy(__DIR__ . "/../templates/default.css", $this->output . "default.css");
  }

  function generateFile($template, $filename, $args = array()) {
    $_file = $this->output . $filename . ".html";
    $this->logger->info("Generating '$_file'...");

    switch ($template) {
      case "index":
        $title = "PHPDoc - " . $this->options['project_name'];
        break;
      case "namespace":
        $title = "PHPDoc - " . $args['namespace'];
        break;
      case "class":
        $title = "PHPDoc - " . $args['namespace'] . "\\" . $args['class'];
        break;
      default:
        $title = "PHPDoc";
    }

    ob_start();

    // lets use PHP to make our lives easier!
    $database = $this->database;
    foreach ($args as $key => $value) {
      $$key = $value;
    }
    require(__DIR__ . "/../templates/header.php");
    require(__DIR__ . "/../templates/" . $template . ".php");
    require(__DIR__ . "/../templates/footer.php");

    $contents = ob_get_contents();
    ob_end_clean();

    file_put_contents($_file, $contents);

  }

  function linkTo($url, $title, $classes = array()) {
    return "<a href=\"" . htmlspecialchars($url) . "\" class=\"" . implode(" ", $classes) . "\">" . htmlspecialchars($title) . "</a>";
  }

  function namespaceLink($namespace) {
    return $this->linkTo("namespace_" . $this->escape($namespace) . ".html", $namespace, array('namespace'));
  }

  function classLink($namespace, $class) {
    return $this->linkTo("class_" . $this->escape($namespace) . "_" . $this->escape($class) . ".html", $class, array('class'));
  }

  function methodLink($namespace, $class, $method) {
    return $this->linkTo("class_" . $this->escape($namespace) . "_" . $this->escape($class) . ".html#" . $this->escape($method), $method, array('method'));
  }

  function escape($s) {
    return preg_replace("#[^a-zA-Z0-9_]#", "_", $s);
  }

  function plural($n, $s, $ss = false) {
    if ($ss == false) {
      $ss = $s . "s";
    }
    if ((int) $n == 1) {
      return number_format($n) . " " . $s;
    } else {
      return number_format($n) . " " . $ss;
    }
  }

}
