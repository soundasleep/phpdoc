<?php

use \PHPDoc2\Database\DocMethod;

if ($doc_reference->getInheritedDoc($generator->logger, 'title')) {
  echo "<p>";
  echo $generator->formatInline($doc_reference, $doc_reference->getInheritedDoc($generator->logger, 'title'));
  $reference = $doc_reference->getInheritedDocElement($generator->logger, 'title');
  if ($reference !== $doc_reference) {
    echo " <i>(from " . $generator->linkTo($reference->getFilename(), $reference->getName()) . ")</i>";
  }
  echo "</p>";
}

if ($doc_reference->getInheritedDoc($generator->logger, 'description')) {
  echo "<p>";
  echo str_replace("\n", "</p><p>", $generator->formatInline($doc_reference, $doc_reference->getInheritedDoc($generator->logger, 'description')));
  $reference = $doc_reference->getInheritedDocElement($generator->logger, 'description');
  if ($reference !== $doc_reference) {
    echo " <i>(from " . $generator->linkTo($reference->getFilename(), $reference->getName()) . ")</i>";
  }
  echo "</p>";
}

echo "<dl>";

if ($doc_reference instanceof DocMethod && $doc_reference->overrides($generator->logger)) {
  echo "<dt>Overrides:</dt>";
  echo "<dd>";
  $reference = $doc_reference->overrides($generator->logger);
  echo $generator->linkTo($reference->getFilename(), $reference->getPrintableName());
  echo "</dd>";
}

if ($doc_reference->getInheritedDoc($generator->logger, 'params')) {
  echo "<dt>Parameters:</dt>";
  echo "<dd>";
  foreach ($doc_reference->getInheritedDoc($generator->logger, 'params') as $param => $description) {
    echo "<code>" . $param . "</code> - " . $generator->formatInline($doc_reference, $description) . "<br>";
  }
  echo "</dd>";
}

if ($doc_reference instanceof DocMethod && $doc_reference->getDefaults($generator->logger)) {
  echo "<dt>Defaults:</dt>";
  echo "<dd>";

  foreach ($doc_reference->getDefaults($generator->logger) as $param => $description) {
    echo "<code>" . $param . "</code> = ";
    switch ($description['type']) {
      case "array":
        echo "array(";
        if ($description['items']) {
          echo "...";
        }
        echo ")";
        break;

      case "string":
        echo "<code>\"" . $description['value'] . "\"</code>";
        break;

      case "number":
        echo $description['value'];
        break;

      case "const":
        echo "<code>" . $description['name'] . "</code>";
        break;

      default:
        echo $description['type'];
        break;

    }
    echo "<br>";
  }
  echo "</dd>";
}

if ($doc_reference->getInheritedDoc($generator->logger, 'return')) {
  echo "<dt>Returns:</dt>";
  echo "<dd>";
  foreach ($doc_reference->getInheritedDoc($generator->logger, 'return') as $description) {
    echo $generator->formatInline($doc_reference, $description) . "<br>";
  }
  echo "</dd>";
}

if ($doc_reference->getInheritedDoc($generator->logger, 'throws')) {
  echo "<dt>Throws:</dt>";
  echo "<dd>";
  foreach ($doc_reference->getInheritedDoc($generator->logger, 'throws') as $see_class => $description) {
    require(__DIR__ . "/_doc_hash.php");
    echo "<br>";
  }
  echo "</dd>";
}

if ($doc_reference->getInheritedDoc($generator->logger, 'see')) {
  echo "<dt>See Also:</dt>";
  echo "<dd>";

  foreach ($doc_reference->getInheritedDoc($generator->logger, 'see') as $see_class => $description) {
    require(__DIR__ . "/_doc_hash.php");
    echo "<br>";
  }
  echo "</dd>";
}

echo "</dl>";

?>
