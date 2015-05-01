<?php

namespace PHPDoc2;

use PHPDoc2\Database\Database;
use PHPDoc2\Database\DocClasslike;
use \Pages\PageRenderer;

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

    // set up template locations
    PageRenderer::addTemplatesLocation(__DIR__ . "/../templates");
    foreach ($this->options['templates'] as $template) {
      PageRenderer::addTemplatesLocation($template);
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

    $args['options'] = $this->options;
    $args['database'] = $this->database;
    $args['logger'] = $this->logger;
    $args['generator'] = $this;
    $args['args'] = $args;    // so we can refer to them in child templates

    PageRenderer::header(array("title" => $title));
    PageRenderer::requireTemplate($template, $args);
    PageRenderer::footer();
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
  var $code_references = array();

  function formatInline($reference, $text) {
    $this->format_reference = $reference;

    // strip out <code>...</code>
    $this->code_references = array();
    $text = preg_replace_callback("/<code>(.+?)<\\/code>/", array($this, 'formatInlineStripCode'), $text);

    // @code
    $text = preg_replace_callback("/{@code ([^}]+)}/", array($this, 'formatInlineCode'), $text);

    // `...`
    $text = preg_replace_callback("/`([^`]+)`/", array($this, 'formatInlineCode'), $text);

    // @link http
    $text = preg_replace_callback("/{@link (https?:\\/\\/.+)}/", array($this, 'formatInlineLinkHttp'), $text);

    // @link #method
    $text = preg_replace_callback("/{@link #([^}\(]+).*}/", array($this, 'formatInlineLinkMethod'), $text);

    // @link class#method
    $text = preg_replace_callback("/{@link ([^#}]+)#([^}\(]+).*}/", array($this, 'formatInlineLinkClassMethod'), $text);

    // @link class
    $text = preg_replace_callback("/{@link ([^}]+)}/", array($this, 'formatInlineLinkClass'), $text);

    // insert back <code>...</code>
    foreach ($this->code_references as $key => $value) {
      $text = str_replace($key, $value, $text);
    }

    $this->format_reference = null;

    return $text;
  }

  /**
   * Safely strip out any <code>...</code> blocks so we can add them in later
   * so they don't get intercepted by any of the other inline formats.
   */
  function formatInlineStripCode($matches) {
    $key = "__inline_code_" . count($this->code_references) . "__";
    $this->code_references[$key] = $matches[0];
    return $key;
  }

  /**
   * Render <code>{@code text}</code>.
   */
  protected function formatInlineCode($matches) {
    return "<code>" . $matches[1] . "</code>";
  }

  /**
   * Render <code>{@link http://...}</code>.
   */
  protected function formatInlineLinkHttp($matches) {
    return $this->linkTo($matches[1], $matches[1]);
  }

  /**
   * Render <code>{@link class}</code>.
   */
  protected function formatInlineLinkClass($matches) {
    $class = $this->format_reference->findClass($matches[1], $this->logger);
    if ($class) {
      return $this->linkTo($class->getFilename(), $class->getName());
    }
    return $matches[1];
  }

  /**
   * Render <code>{@link class#method}</code>.
   */
  protected function formatInlineLinkClassMethod($matches) {
    $class = $this->format_reference->findClass($matches[1], $this->logger);
    if ($class) {
      $method = $class->getMethod($matches[2]);
      if ($method) {
        return $this->linkTo($method->getFilename(), $class->getName() . "#" . $method->getName() . "()");
      }
    }
    return $matches[1] . "#" . $matches[2];
  }

  /**
   * Render <code>{@link #method}</code>.
   */
  protected function formatInlineLinkMethod($matches) {
    $class = $this->format_reference;
    if (!($class instanceof DocClasslike)) {
      $class = $class->getClass();
    }
    $method = $class->getMethod($matches[1]);
    if ($method) {
      return $this->linkTo($method->getFilename(), "#" . $method->getName() . "()");
    }
    return "#" . $matches[1];
  }

}
