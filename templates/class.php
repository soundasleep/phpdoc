<h1>
  <?php echo $this->linkTo($namespace->getFilename(), $namespace->getName()); ?>
  \
  <?php echo $this->linkTo($class->getFilename(), $class->getName()); ?>
</h1>

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
  echo "<td>" . $method->getDocTitle() . "</td>";
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
  echo "<td>" . $method->getDocTitle() . " <i>(from " . $this->linkTo($method->getFilename(), $method->getClass()->getName()) . ")</i></td>";
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
if ($method->getDocTitle()) {
  echo "<p>" . $method->getDocTitle() . "</p>";
}
if ($method->getDocDescription()) {
  echo "<p>" . str_replace("\n", "</p><p>", $method->getDocDescription()) . "</p>";
}

echo "<dl>";
if ($method->getDocParams()) {
  echo "<dt>Parameters:</dt>";
  echo "<dd>";
  foreach ($method->getDocParams() as $param => $description) {
    echo "<code>" . $param . "</code> - " . $description;
  }
  echo "</dd>";
}
echo "</dl>";
?>
</blockquote>

<?php } ?>
