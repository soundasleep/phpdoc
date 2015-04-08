<h1><?php echo $this->namespaceLink($namespace); ?> \\ <?php echo $this->classLink($namespace, $class); ?> Class</h1>

<h2>Methods</h2>

<table>
  <thead>
    <tr>
      <th>Name</th>
      <th>Description</th>
    </tr>
  </thead>
  <tbody>
<?php foreach ($database['namespaces'][$namespace]['classes'][$class]['methods'] as $method => $data) {
  echo "<tr>";
  echo "<td>" . $this->methodLink($namespace, $class, $method) . "(" . implode(", ", array_keys($data['params'])) . ")</td>";
  echo "<td>" . $data['doc']['title'] . "</td>";
  echo "</tr>";
} ?>
  </tbody>
</table>
