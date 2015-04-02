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

    $this->generateFile("index");

    // copy over CSS
    copy(__DIR__ . "/../templates/default.css", $this->output . "default.css");
  }

  function generateFile($template) {
    $_file = $this->output . $template . ".html";
    $this->logger->info("Generating '$_file'...");

    switch ($template) {
      case "index":
        $title = "PHPDoc - " . $this->options['project_name'];
        break;
      default:
        $title = "PHPDoc";
    }

    ob_start();

    // lets use PHP to make our lives easier!
    $database = $this->database;
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

}
