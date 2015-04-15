<?php

if ($doc_reference->getInheritedDoc($this->logger, 'title')) {
  echo "<p>";
  echo $doc_reference->getInheritedDoc($this->logger, 'title');
  $reference = $doc_reference->getInheritedDocElement($this->logger, 'title');
  if ($reference !== $doc_reference) {
    echo " <i>(from " . $this->linkTo($reference->getFilename(), $reference->getName()) . ")</i>";
  }
  echo "</p>";
}

if ($doc_reference->getInheritedDoc($this->logger, 'description')) {
  echo "<p>";
  echo str_replace("\n", "</p><p>", $doc_reference->getInheritedDoc($this->logger, 'description'));
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
    echo "<code>" . $param . "</code> - " . $description . "<br>";
  }
  echo "</dd>";
}

if ($doc_reference->getInheritedDoc($this->logger, 'return')) {
  echo "<dt>Returns:</dt>";
  echo "<dd>";
  foreach ($doc_reference->getInheritedDoc($this->logger, 'return') as $description) {
    echo $description . "<br>";
  }
  echo "</dd>";
}

if ($doc_reference->getInheritedDoc($this->logger, 'throws')) {
  echo "<dt>Throws:</dt>";
  echo "<dd>";
  foreach ($doc_reference->getInheritedDoc($this->logger, 'throws') as $thrown_class => $description) {
    // try fqn
    $discovered_class = $database->findClasslike($thrown_class, $this->logger);
    if (!$discovered_class) {
      // try our local namespace
      $discovered_class = $database->findClasslike($doc_reference->getNamespace()->getName() . "\\" . $thrown_class, $this->logger);
    }

    if ($discovered_class) {
      echo $this->linkTo($discovered_class->getFilename(), $discovered_class->getName());
    } else {
      echo $thrown_class;
    }
    if ($description) {
      echo " - " . $description;
    }
    echo "<br>";
  }
  echo "</dd>";
}

if ($doc_reference->getInheritedDoc($this->logger, 'see')) {
  echo "<dt>See Also:</dt>";
  echo "<dd>";
  foreach ($doc_reference->getInheritedDoc($this->logger, 'see') as $see_class => $description) {
    // try fqn
    $discovered_class = $database->findClasslike($see_class, $this->logger);
    if (!$discovered_class) {
      // try our local namespace
      $discovered_class = $database->findClasslike($doc_reference->getNamespace()->getName() . "\\" . $see_class, $this->logger);
    }

    if ($discovered_class) {
      echo $this->linkTo($discovered_class->getFilename(), $discovered_class->getName());
    } else {
      echo $see_class;
    }
    if ($description) {
      echo " - " . $description;
    }
    echo "<br>";
  }
  echo "</dd>";
}

echo "</dl>";

?>
