<?php

require(__DIR__ . "/vendor/autoload.php");

use PHPDoc\Collector;
use PHPDoc\MyLogger;

$logger = new \Monolog\Logger("test");
$logger->pushHandler(new MyLogger());

$collector = new Collector($logger);
$result = $collector->parse(__DIR__);

file_put_contents(__DIR__ . "/parsed.json", json_encode($result, JSON_PRETTY_PRINT));

// print_r($result);
