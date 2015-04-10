<h1>
  <small><?php echo $namespace->getModifiers(); ?></small>
  <?php echo $this->linkTo($namespace->getFilename(), $namespace->getName()); ?>
</h1>

<?php
$namespaces = $namespace->getChildNamespaces();

if ($namespaces) { ?>

<h2>Child Namespaces</h2>

<ul>
<?php
  foreach ($namespaces as $child) {
    echo "<li>";
    echo $this->linkTo($child->getFilename(), $child->getName());
    echo " - " . $this->plural(count($child->getClasses()), "class", "classes");
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
  echo "<td>" . $class->getDoc('title') . "</td>";
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
  echo "<td>" . $class->getDoc('title') . "</td>";
  echo "</tr>";
} ?>
  </tbody>
</table>

<?php } ?>
