<h1>Generated Documentation for <?php echo $options['project_name']; ?></h1>

<dl>
  <dt>Namespaces</dt>
  <dd><?php echo count($database->getNamespaces()); ?></dd>

  <dt>Classes</dt>
  <dd><?php echo count($database->getAllClasses()); ?></dd>

  <dt>Interfaces</dt>
  <dd><?php echo count($database->getAllInterfaces()); ?></dd>
</dl>

<h2>Namespaces</h2>

<ul>
<?php
  foreach ($database->getNamespaces() as $namespace) {
    echo "<li>";
    echo $this->linkTo($namespace->getFilename(), $namespace->getPrintableName());
    echo " - " . $this->plural(count($namespace->getClasses()), "class", "classes");
    echo "</li>";
  }
?>
</ul>
