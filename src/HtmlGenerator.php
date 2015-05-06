<?php

namespace PHPDoc2;

use PHPDoc2\Database\Database;
use PHPDoc2\Database\DocClasslike;
use PHPDoc2\Database\DocMethod;
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
    PageRenderer::addStylesheet("default.css");

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

    // replace {@link foo}s with {@link foo foos}
    $text = preg_replace_callback("/{@link ([^ }]+)}s/", array($this, 'insertLinkPlurals'), $text);

    // @code
    $text = preg_replace_callback("/{@code ([^}]+)}/", array($this, 'formatInlineCode'), $text);

    // `...`
    $text = preg_replace_callback("/`([^`]+)`/", array($this, 'formatInlineCode'), $text);

    // @link http
    $text = preg_replace_callback("/{@link (https?:\\/\\/[^ ]+)(| [^}]+)}/", array($this, 'formatInlineLinkHttp'), $text);

    // @link #method
    $text = preg_replace_callback("/{@link #([^}\( ]+).*?(| [^}]+)}/", array($this, 'formatInlineLinkMethod'), $text);

    // @link class#method
    $text = preg_replace_callback("/{@link ([^#} ]+)#([^}\( ]+).*?(| [^}]+)}/", array($this, 'formatInlineLinkClassMethod'), $text);

    // @link class
    $text = preg_replace_callback("/{@link ([^} ]+?)(| [^}]+)}/", array($this, 'formatInlineLinkClass'), $text);

    // insert back <code>...</code>
    foreach ($this->code_references as $key => $value) {
      $text = str_replace($key, $value, $text);
    }

    $this->format_reference = null;

    return $text;
  }

  /**
   * Replace <code>{@link foo}s</code> with <code>{@link foo foos}</code>
   */
  function insertLinkPlurals($matches) {
    return "{@link " . $matches[1] . " " . $matches[1] . "s}";
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
    // optional text
    if ($matches[2]) {
      return $this->linkTo($matches[1], trim($matches[2]));
    } else {
      return $this->linkTo($matches[1], $matches[1]);
    }
  }

  /**
   * Render <code>{@link class}</code> and
   * <code>{@link class optional}</code>.
   */
  protected function formatInlineLinkClass($matches) {
    $class = $this->format_reference->findClass($matches[1], $this->logger);
    if ($class) {
      // optional text
      if ($matches[2]) {
        return $this->linkTo($class->getFilename(), trim($matches[2]));
      } else {
        return $this->linkTo($class->getFilename(), $class->getName());
      }
    }
    // optional text
    if ($matches[2]) {
      return trim($matches[2]);
    } else {
      return $matches[1];
    }
  }

  /**
   * Render <code>{@link class#method}</code>
   * and <code>{@link class#method optional}</code>.
   */
  protected function formatInlineLinkClassMethod($matches) {
    $class = $this->format_reference->findClass($matches[1], $this->logger);
    if ($class) {
      $method = $class->findMethod($matches[2], $this->logger);
      if ($method) {
        // optional text
        if ($matches[3]) {
          return $this->linkTo($method->getFilename(), trim($matches[3]));
        } else {
          return $this->linkTo($method->getFilename(), $class->getName() . "#" . $method->getName() . "()");
        }
      }
    }
    // optional text
    if ($matches[3]) {
      return trim($matches[3]);
    } else {
      return $matches[1] . "#" . $matches[2];
    }
  }

  /**
   * Render <code>{@link #method}</code>
   * and <code>{@link #method optional}</code>.
   */
  protected function formatInlineLinkMethod($matches) {
    $class = $this->format_reference;
    if (!($class instanceof DocClasslike)) {
      $class = $class->getClass();
    }
    $method = $class->findMethod($matches[1], $this->logger);
    if ($method) {
      // optional text
      if ($matches[2]) {
        return $this->linkTo($method->getFilename(), trim($matches[2]));
      } else {
        return $this->linkTo($method->getFilename(), "#" . $method->getName() . "()");
      }
    }
    // optional text
    if ($matches[2]) {
      return trim($matches[2]);
    } else {
      return "#" . $matches[1];
    }
  }

  /**
   * Get a printed representation of the method signature,
   * with links to parameter types as possible.
   */
  public function printMethod(DocMethod $method) {
    $params = array();
    foreach ($method->getParams() as $name => $data) {
      $value = "";
      if (isset($data['type']) && $data['type']) {
        // try find the class reference
        // e.g. Namespace\Class $arg
        $discovered_class = $this->database->findClasslike($data['type'], $this->logger);
        if (!$discovered_class) {
          // try our local namespace
          // e.g. Class $arg
          $discovered_class = $this->database->findClasslike($method->getClass()->getNamespace()->getName() . "\\" . $data['type'], $this->logger);
        }

        if ($discovered_class) {
          $value .= $this->linkTo($discovered_class->getFilename(), $discovered_class->getPrintableName());
          $value .= " ";
        } else {
          // just get the class name without namespace
          $value .= $method->getSimpleName($data['type']) . " ";
        }
      }

      $value .= '$' . $name;
      if (isset($data['default'])) {
        switch ($data['default']['type']) {
          case "string":
            $value .= " = \"" . $data['default']['value'] . "\"";
            break;

          case "number":
            $value .= " = " . $data['default']['value'];
            break;

          case "array":
            $value .= " = array(";
            if ($data['default']['items']) {
              $value .= "...";
            }
            $value .= ")";
            break;

          case "const":
            // e.g. 'null'
            $value .= " = " . $data['default']['name'];
            break;

        }
      }
      $params[] = $value;
    }

    return $method->getName() . "(" . implode(", ", $params) . ")";
  }

  function formatDefault($description) {
    switch ($description['type']) {
      case "array":
        $result = "array(";
        if ($description['items']) {
          $result .=  "...";
        }
        $result .= ")";
        return $result;

      case "string":
        return "<code>\"" . $description['value'] . "\"</code>";

      case "number":
        return $description['value'];

      case "const":
        return "<code>" . $description['name'] . "</code>";

      default:
        return $description['type'];
    }
  }

}
