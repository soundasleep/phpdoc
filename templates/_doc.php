<?php

if ($doc_reference->getInheritedDoc($this->logger, 'title')) {
  echo "<p>";
  echo $this->formatInline($doc_reference, $doc_reference->getInheritedDoc($this->logger, 'title'));
  $reference = $doc_reference->getInheritedDocElement($this->logger, 'title');
  if ($reference !== $doc_reference) {
    echo " <i>(from " . $this->linkTo($reference->getFilename(), $reference->getName()) . ")</i>";
  }
  echo "</p>";
}

if ($doc_reference->getInheritedDoc($this->logger, 'description')) {
  echo "<p>";
  echo str_replace("\n", "</p><p>", $this->formatInline($doc_reference, $doc_reference->getInheritedDoc($this->logger, 'description')));
  $reference = $doc_reference->getInheritedDocElement($this->logger, 'description');
  if ($reference !== $doc_reference) {
    echo " <i>(from " . $this->linkTo($reference->getFilename(), $reference->getName()) . ")</i>";
  }
  echo "</p>";
}

echo "<dl>";

if ($doc_reference->getInheritedDoc($this->logger, 'params')) {
  echo "<dt>Parameters:</dt>";
  echo "<dd>";
  foreach ($doc_reference->getInheritedDoc($this->logger, 'params') as $param => $description) {
    echo "<code>" . $param . "</code> - " . $this->formatInline($doc_reference, $description) . "<br>";
  }
  echo "</dd>";
}

if ($doc_reference->getInheritedDoc($this->logger, 'return')) {
  echo "<dt>Returns:</dt>";
  echo "<dd>";
  foreach ($doc_reference->getInheritedDoc($this->logger, 'return') as $description) {
    echo $this->formatInline($doc_reference, $description) . "<br>";
  }
  echo "</dd>";
}

if ($doc_reference->getInheritedDoc($this->logger, 'throws')) {
  echo "<dt>Throws:</dt>";
  echo "<dd>";
  foreach ($doc_reference->getInheritedDoc($this->logger, 'throws') as $thrown_class => $description) {
    require(__DIR__ . "/_doc_hash.php");
    echo "<br>";
  }
  echo "</dd>";
}

if ($doc_reference->getInheritedDoc($this->logger, 'see')) {
  echo "<dt>See Also:</dt>";
  echo "<dd>";

  foreach ($doc_reference->getInheritedDoc($this->logger, 'see') as $see_class => $description) {
    require(__DIR__ . "/_doc_hash.php");
    echo "<br>";
  }
  echo "</dd>";
}

echo "</dl>";

?>
