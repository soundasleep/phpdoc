<?php

namespace PHPDoc;

use PHPDoc\Database\Database;

class HtmlGenerator {

  var $database;
  var $options;
  var $logger;
  var $output;

  function __construct(Database $database, $options, $logger, $output) {
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

    $this->generateFile("index", $this->database);

    foreach ($this->database->getNamespaces() as $namespace) {
      $this->generateFile("namespace", $namespace, array(
        'namespace' => $namespace,
      ));

      foreach ($namespace->getClasses() as $class) {
        $this->generateFile("class", $class, array(
          'namespace' => $namespace,
          'class' => $class,
        ));
      }

      foreach ($namespace->getInterfaces() as $interface) {
        $this->generateFile("interface", $interface, array(
          'namespace' => $namespace,
          'interface' => $interface,
        ));
      }
    }

    // copy over CSS
    copy(__DIR__ . "/../templates/default.css", $this->output . "default.css");
  }

  function generateFile($template, $object, $args = array()) {
    $filename = $object->getFilename();
    $title = $object->getTitle($this->options);

    $_file = $this->output . $filename;
    $this->logger->info("Generating '$_file'...");

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

  var $format_reference = null;

  function formatInline($reference, $text) {
    $this->format_reference = $reference;

    // @inheritDoc
    // @code
    $text = preg_replace_callback("/{@code ([^}]+)}/", array($this, 'formatInlineCode'), $text);

    // @link http
    // @link #method
    // @link class#method

    // @link class
    $text = preg_replace_callback("/{@link ([^}]+)}/", array($this, 'formatInlineLinkClass'), $text);

    $this->format_reference = null;

    return $text;
  }

  /**
   * Render <pre>{@code text}</pre>.
   */
  protected function formatInlineCode($matches) {
    return "<code>" . $matches[1] . "</code>";
  }

  /**
   * Render <pre>{@link class}</pre>.
   */
  protected function formatInlineLinkClass($matches) {
    $class = $this->format_reference->findClass($matches[1], $this->logger);
    if ($class) {
      return $this->linkTo($class->getFilename(), $class->getName());
    }
    return $matches[1];
  }

}
