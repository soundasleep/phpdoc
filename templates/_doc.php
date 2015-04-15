<?php

if ($doc_reference->getInheritedDoc($this->logger, 'title')) {
  echo "<p>" . $doc_reference->getInheritedDoc($this->logger, 'title') . "</p>";
  $reference = $doc_reference->getInheritedDocElement($this->logger, 'title');
  if ($reference !== $doc_reference) {
    echo "<p><i>(from " . $this->linkTo($reference->getFilename(), $reference->getName()) . ")</i></p>";
  }
}
if ($doc_reference->getDoc('description')) {
  echo "<p>" . str_replace("\n", "</p><p>", $doc_reference->getDoc('description')) . "</p>";
}

echo "<dl>";

if ($doc_reference->getDoc('params')) {
  echo "<dt>Parameters:</dt>";
  echo "<dd>";
  foreach ($doc_reference->getDoc('params') as $param => $description) {
    echo "<code>" . $param . "</code> - " . $description . "<br>";
  }
  echo "</dd>";
}
if ($doc_reference->getDoc('returns')) {
  echo "<dt>Returns:</dt>";
  echo "<dd>";
  foreach ($doc_reference->getDoc('returns') as $description) {
    echo $description . "<br>";
  }
  echo "</dd>";
}
if ($doc_reference->getDoc('throws')) {
  echo "<dt>Throws:</dt>";
  echo "<dd>";
  foreach ($doc_reference->getDoc('throws') as $thrown_class => $description) {
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
if ($doc_reference->getDoc('see')) {
  echo "<dt>See Also:</dt>";
  echo "<dd>";
  foreach ($doc_reference->getDoc('see') as $see_class => $description) {
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
