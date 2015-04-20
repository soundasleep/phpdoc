<?php

require(__DIR__ . "/vendor/autoload.php");

use PHPDoc2\Collector;
use PHPDoc2\MyLogger;
use PHPDoc2\HtmlGenerator;
use PHPDoc2\Database\Database;

$logger = new \Monolog\Logger("test");
$logger->pushHandler(new MyLogger());

$collector = new Collector($logger);
$result = $collector->parse(__DIR__);

file_put_contents(__DIR__ . "/parsed.json", json_encode($result, JSON_PRETTY_PRINT));

// print_r($result);
$options = array(
  'project_name' => 'Untitled',
);

$database = new Database("untitled", $result);
$html = new HtmlGenerator($database, $options, $logger, __DIR__ . "/docs/");
$html->generate();

