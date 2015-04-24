<h1>
  <small><?php echo $class->getModifiers(); ?></small>

  <?php echo $this->linkTo($namespace->getFilename(), $namespace->getPrintableName()); ?>
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
  <?php

    $params = array();
    foreach ($method->getParams() as $name => $data) {
      $value = "";
      if (isset($data['type']) && $data['type']) {
        // try find the class reference
        // e.g. Namespace\Class $arg
        $discovered_class = $database->findClasslike($data['type'], $this->logger);
        if (!$discovered_class) {
          // try our local namespace
          // e.g. Class $arg
          $discovered_class = $database->findClasslike($class->getNamespace()->getName() . "\\" . $data['type'], $this->logger);
        }

        if ($discovered_class) {
          $value .= $this->linkTo($discovered_class->getFilename(), $discovered_class->getPrintableName());
          $value .= " ";
        } else {
          // just get the class name without namespace
          $value .= $method->getSimpleName($data['type']) . " ";
        }
      }

      $value .= '$' . $name;
      if (isset($data['default'])) {
        switch ($data['default']['type']) {
          case "string":
            $value .= " = \"" . $data['default']['value'] . "\"";
            break;

          case "number":
            $value .= " = " . $data['default']['value'];
            break;

          case "array":
            $value .= " = array(";
            if ($data['default']['items']) {
              $value .= "...";
            }
            $value .= ")";
            break;

          case "const":
            // e.g. 'null'
            $value .= " = " . $data['default']['name'];
            break;

        }
      }
      $params[] = $value;
    }
    echo $method->getName() . "(" . implode(", ", $params) . ")";

  ?>
  <a name="<?php echo htmlspecialchars($method->getName()); ?>"></a>
</h3>

<blockquote>
<?php
$doc_reference = $method;
require(__DIR__ . "/_doc.php");
?>
</blockquote>

<?php } ?>
