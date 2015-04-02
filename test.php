<?php

require(__DIR__ . "/vendor/autoload.php");

use PHPDoc\Collector;
use PHPDoc\MyLogger;
use PHPDoc\HtmlGenerator;

$logger = new \Monolog\Logger("test");
$logger->pushHandler(new MyLogger());

$collector = new Collector($logger);
$result = $collector->parse(__DIR__);

file_put_contents(__DIR__ . "/parsed.json", json_encode($result, JSON_PRETTY_PRINT));

// print_r($result);
$options = array(
  'project_name' => 'Untitled',
);

$html = new HtmlGenerator($result, $options, $logger, __DIR__ . "/docs/");
$html->generate();

