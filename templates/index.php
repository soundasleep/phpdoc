<h1>PHPDoc</h1>

<dl>
  <dt>Namespaces</dt>
  <dd><?php echo number_format(count($database['namespaces'])); ?></dd>
</dl>

<h2>Namespaces</h2>

<ul>
<?php
  foreach ($database['namespaces'] as $namespace => $data) {
    echo "<li>" . $this->namespaceLink($namespace) . " - " . $this->plural(count($data['classes']), "class", "classes");
  }
?>
</ul>
