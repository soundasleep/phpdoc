<?php

require(__DIR__ . "/vendor/autoload.php");

use PHPDocParser\Collector;
use PHPDocParser\MyLogger;

$logger = new \Monolog\Logger("test");
$logger->pushHandler(new MyLogger());

$collector = new Collector($logger);
$result = $collector->parse(__DIR__);

print_r($result);
