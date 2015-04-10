<h1>
  <small><?php echo $class->getModifiers(); ?></small>

  <?php echo $this->linkTo($namespace->getFilename(), $namespace->getName()); ?>
  \
  <?php echo $this->linkTo($class->getFilename(), $class->getName()); ?>
</h1>

<?php if ($class->getParentInterfaces($this->logger)) { ?>

<dl>
  <dt>All implemented interfaces:</dt>
  <dd>
    <?php
    $result = array();
    foreach ($class->getParentInterfaces($this->logger) as $interface) {
      if (is_string($interface)) {
        $result[] = $interface;
      } else {
        $result[] = $this->linkTo($interface->getFilename(), $interface->getName());
      }
    }
    echo implode(", ", $result);
    ?>
  </dd>
</dl>

<hr>

<?php
$doc_reference = $class;
require(__DIR__ . "/_doc.php");
?>

<?php } ?>

<hr>

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

<h3>
  <small><?php echo $method->getModifiers(); ?></small>
  <?php echo $method->getPrintableName(); ?>
</h3>

<blockquote>
<?php
$doc_reference = $method;
require(__DIR__ . "/_doc.php");
?>
</blockquote>

<?php } ?>
