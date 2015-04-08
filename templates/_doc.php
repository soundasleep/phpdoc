<?php

if ($doc_reference->getDoc('title')) {
  echo "<p>" . $doc_reference->getDoc('title') . "</p>";
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

echo "</dl>";

?>
