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
  echo "<td>" . $this->linkTo($method->getFilename(), $method->getName()) . "</td>";
  echo "<td>" . $method->getDocTitle() . "</td>";
  echo "</tr>";
} ?>
  </tbody>
</table>
