<?php
foreach ($class->getClassHierarchy($this->logger) as $superclass) {
  echo "<ul class=\"class-hierarchy\"><li>";
  if (is_string($superclass)) {
    echo $superclass;
  } else {
    echo $this->linkTo($superclass->getFilename(), $superclass->getNamespace()->getName() . "\\" .  $superclass->getName());
  }
}
echo "<ul><li>" . $class->getNamespace()->getName() . "\\" . $class->getName() . "</li></ul>";
foreach ($class->getClassHierarchy($this->logger) as $superclass) {
  echo "</li></ul>";
}
?>

<?php
require(__DIR__ . "/_classlike.php");
?>
