<?php

namespace PHPDoc2;

use Monolog\Logger;

/**
 * Loads a collection of directories and parses for PHP documents and
 * generates a class AST.
 */
class Collector {

  var $logger;

  function __construct(Logger $logger) {
    $this->logger = $logger;
  }

  public function parse($dirs) {
    $files = array();
    foreach ($dirs as $dir) {
      $files = array_merge($files, $this->iterate($dir));
    }

    $this->logger->info("Parsing " . number_format(count($files)) . " PHP files...");

    $parser = new Parser($this->logger);
    return $parser->load($files);
  }

  /**
   * Find all the PHP files to search in the given directory.
   */
  function iterate($dir) {
    $files = array();
    $this->logger->info("Parsing dir '$dir'...");

    if ($handle = opendir($dir)) {
      while (false !== ($entry = readdir($handle))) {
        if (substr($entry, 0, 1) != ".") {
          if ($this->shouldIgnore($dir . "/" . $entry)) {
            continue;
          }
          if (is_dir($dir . "/" . $entry)) {
            $files = array_merge($files, $this->iterate($dir . "/" . $entry));
          } else if ($this->isPHPFile($entry)) {
            $files[] = $dir . "/" . $entry;
          }
        }
      }
      closedir($handle);
    }

    return $files;
  }

  function isPHPFile($filename) {
    return substr(strtolower($filename), -4) === ".php";
  }

  function shouldIgnore($dir) {
    return preg_match("#/vendor/#i", $dir);
  }

}
