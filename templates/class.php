<h1>
  <?php echo $this->linkTo($namespace->getFilename(), $namespace->getName()); ?>
  \
  <?php echo $this->linkTo($class->getFilename(), $class->getName()); ?>
</h1>

<?php
$doc_reference = $class;
require(__DIR__ . "/_doc.php");
?>

<h2>Method Summary</h2>

<table>
  <thead>
    <tr>
      <th>Name</th>
      <th>Description</th>
    </tr>
  </thead>
  <tbody>
<?php foreach ($class->getMethods() as $method) {
  echo "<tr>";
  echo "<td>" . $this->linkTo($method->getFilename(), $method->getPrintableName()) . "</td>";
  echo "<td>" . $method->getDoc('title') . "</td>";
  echo "</tr>";
} ?>
  </tbody>
</table>

<?php

$inherited = $class->getInheritedMethods($this->logger);
if ($inherited) { ?>

<h2>Inherited Method Summary</h2>

<table>
  <thead>
    <tr>
      <th>Name</th>
      <th>Description</th>
    </tr>
  </thead>
  <tbody>
<?php foreach ($inherited as $method) {
  echo "<tr>";
  echo "<td>" . $this->linkTo($method->getFilename(), $method->getPrintableName()) . "</td>";
  echo "<td>" . $method->getDoc('title') . " <i>(from " . $this->linkTo($method->getFilename(), $method->getClass()->getName()) . ")</i></td>";
  echo "</tr>";
} ?>
  </tbody>
</table>

<?php } ?>

<?php foreach ($class->getMethods() as $method) { ?>

<hr>

<h3><?php echo $method->getPrintableName(); ?></h3>

<blockquote>
<?php
$doc_reference = $method;
require(__DIR__ . "/_doc.php");
?>
</blockquote>

<?php } ?>
