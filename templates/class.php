<h1>
  <?php echo $this->linkTo($namespace->getFilename(), $namespace->getName()); ?>
  \
  <?php echo $this->linkTo($class->getFilename(), $class->getName()); ?>
</h1>

<h2>Methods</h2>

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

<h2>Inherited Methods</h2>

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
