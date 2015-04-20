<?php

namespace PHPDoc2;

use Monolog\Logger;

/**
 * Loads a collection of directories and parses for PHP documents and
 * generates a class AST.
 */
class Collector {

  var $logger;
  var $result;

  function __construct(Logger $logger) {
    $this->logger = $logger;
  }

  public function parse($dir) {
    $this->result = array();
    $this->iterate($dir);
    return $this->result;
  }

  function iterate($dir) {
    $parser = new Parser($this->logger);
    $this->logger->info("Parsing dir '$dir'");

    if ($handle = opendir($dir)) {
      while (false !== ($entry = readdir($handle))) {
        if (substr($entry, 0, 1) != ".") {
          if ($this->shouldIgnore($dir . "/" . $entry)) {
            continue;
          }
          if (is_dir($dir . "/" . $entry)) {
            $this->iterate($dir . "/" . $entry);
          } else if ($this->isPHPFile($entry)) {
            $result = $parser->load($dir . "/" . $entry);
            $this->mergeResult($result);
          }
        }
      }
      closedir($handle);
    }
  }

  function isPHPFile($filename) {
    return substr(strtolower($filename), -4) === ".php";
  }

  function mergeResult($result) {
    $this->result = array_merge_recursive($this->result, $result);
  }

  function shouldIgnore($dir) {
    return preg_match("#/vendor/#i", $dir);
  }

}
