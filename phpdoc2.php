<?php

/**
 * Runs PHPDoc2 on a collection of projects and generates HTML output.
 */

if (file_exists(__DIR__ . "/vendor/autoload.php")) {
  require(__DIR__ . /* ignore require() lint */ "/vendor/autoload.php");
} else {
  require(__DIR__ . /* ignore require() lint */ "/../../autoload.php");
}

use GetOptionKit\OptionCollection;
use GetOptionKit\OptionParser;
use GetOptionKit\OptionPrinter\ConsoleOptionPrinter;

$specs = new OptionCollection();
$specs->add('d|directory+', 'PHP directories to parse');
$specs->add('c|config?', 'Config JSON');
$specs->add('j|json?', 'Options database as JSON to file');
$specs->add('o|output?', 'Output HTML to given directory');
$specs->add('help', 'Display help');

$parser = new OptionParser($specs);
$result = $parser->parse($argv);

if (isset($result['help'])) {
  $printer = new ConsoleOptionPrinter();
  echo $printer->render($specs);
  return;
}

// now parse
$dirs = array();
$config = array();
$json_file = false;
$output_dir = "docs/";

foreach ($result as $key => $arg) {
  switch ($key) {
    case "directory":
      $dirs = $arg->value;
      break;

    case "config":
      if (!is_file($arg->value)) {
        throw new Exception("Config file '" . $arg->value . "' is not a file");
      }
      if (!file_exists($arg->value)) {
        throw new Exception("Config file '" . $arg->value . "' does not exist");
      }
      $config = json_decode(file_get_contents($arg->value), true /* assoc */);
      break;

    case "json":
      $json_file = $arg->value;
      break;

    case "output":
      $output_dir = $arg->value;
      if (substr($output_dir, -1) != "/") {
        $output_dir .= "/";
      }
      break;

  }
}

if (!$dirs) {
  throw new Exception("Need to specify at least one directory with --directory switch");
}

// load default options
$config += array(
  'project_name' => 'Untitled',
  'ignore' => array(
    '/vendor/',
  ),
);

use PHPDoc2\Collector;
use PHPDoc2\MyLogger;
use PHPDoc2\HtmlGenerator;
use PHPDoc2\Database\Database;

$logger = new \Monolog\Logger("PHPDoc2");
$logger->pushHandler(new MyLogger());

$collector = new Collector($logger, $config);
$result = $collector->parse($dirs);

if ($json_file) {
  file_put_contents($json_file, json_encode($result, JSON_PRETTY_PRINT));
}

$database = new Database($config['project_name'], $result);
$html = new HtmlGenerator($database, $config, $logger, $output_dir);
$html->generate();

