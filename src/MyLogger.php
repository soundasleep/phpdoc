<?php

namespace PHPDoc2;

use \Monolog\Logger;

class MyLogger extends \Monolog\Handler\AbstractHandler {
  function handle(array $record) {
    $message = $record['message'];
    if ($record['level'] >= Logger::WARNING) {
      if ($record['level'] >= Logger::ERROR) {
        $message = "[ERROR] " . $message;
      } else {
        $message = "[Warning] " . $message;
      }
    }
    echo $message . "\n";
  }
}
