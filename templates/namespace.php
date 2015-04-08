<h1><?php echo $this->linkTo($namespace->getFilename(), $namespace->getName()); ?> Namespace</h1>

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
