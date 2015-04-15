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
    foreach ($class->getParentInterfaces($this->logger) as $ref) {
      if (is_string($ref)) {
        $result[] = $ref;
      } else {
        $result[] = $this->linkTo($ref->getFilename(), $ref->getName());
      }
    }
    echo implode(", ", $result);
    ?>
  </dd>
</dl>

<?php } ?>

<?php if ($class->getKnownImplementations($this->logger)) { ?>

<dl>
  <dt>All known implementing classes:</dt>
  <dd>
    <?php
    $result = array();
    foreach ($class->getKnownImplementations($this->logger) as $ref) {
      if (is_string($ref)) {
        $result[] = $ref;
      } else {
        $result[] = $this->linkTo($ref->getFilename(), $ref->getName());
      }
    }
    echo implode(", ", $result);
    ?>
  </dd>
</dl>

<?php } ?>

<?php if ($class->getDirectSubclasses($this->logger)) { ?>

<dl>
  <dt>All known direct subclasses:</dt>
  <dd>
    <?php
    $result = array();
    foreach ($class->getDirectSubclasses($this->logger) as $ref) {
      if (is_string($ref)) {
        $result[] = $ref;
      } else {
        $result[] = $this->linkTo($ref->getFilename(), $ref->getName());
      }
    }
    echo implode(", ", $result);
    ?>
  </dd>
</dl>

<?php } ?>

<hr>

<?php
$doc_reference = $class;
require(__DIR__ . "/_doc.php");
?>

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
  echo "<td>" . $this->formatInline($method, $method->getInheritedDoc($this->logger, 'title')) . "</td>";
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
  echo "<td>" . $this->formatInline($method, $method->getInheritedDoc($this->logger, 'title')) . " <i>(from " . $this->linkTo($method->getFilename(), $method->getClass()->getName()) . ")</i></td>";
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
