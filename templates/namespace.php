<h1>
  <small><?php echo $namespace->getModifiers(); ?></small>
  <?php echo $this->linkTo($namespace->getFilename(), $namespace->getPrintableName()); ?>
</h1>

<?php
$namespaces = $namespace->getChildNamespaces();

if ($namespaces) { ?>

<h2>Child Namespaces</h2>

<ul>
<?php
  foreach ($namespaces as $child) {
    echo "<li>";
    echo $this->linkTo($child->getFilename(), $child->getPrintableName());
    $references = array();
    if ($child->getInterfaces()) {
      $references[] = $this->plural(count($child->getInterfaces()), "interface");
    }
    if ($child->getClasses()) {
      $references[] = $this->plural(count($child->getClasses()), "class", "classes");
    }
    if ($references) {
      echo " - " . implode(", ", $references);
    }
    echo "</li>";
  }
?>
</ul>

<?php } ?>

<?php if ($namespace->getInterfaces()) { ?>

<h2>Interfaces</h2>

<table>
  <thead>
    <tr>
      <th>Name</th>
      <th>Description</th>
    </tr>
  </thead>
  <tbody>
<?php foreach ($namespace->getInterfaces() as $class) {
  echo "<tr>";
  echo "<td>" . $this->linkTo($class->getFilename(), $class->getName()) . "</td>";
  echo "<td>" . $this->formatInline($class, $class->getInheritedDoc($this->logger, 'title')) . "</td>";
  echo "</tr>";
} ?>
  </tbody>
</table>

<?php } ?>

<?php if ($namespace->getClasses()) { ?>

<h2>Classes</h2>

<table>
  <thead>
    <tr>
      <th>Name</th>
      <th>Description</th>
    </tr>
  </thead>
  <tbody>
<?php foreach ($namespace->getClasses() as $class) {
  echo "<tr>";
  echo "<td>" . $this->linkTo($class->getFilename(), $class->getName()) . "</td>";
  echo "<td>" . $this->formatInline($class, $class->getInheritedDoc($this->logger, 'title')) . "</td>";
  echo "</tr>";
} ?>
  </tbody>
</table>

<?php } ?>
