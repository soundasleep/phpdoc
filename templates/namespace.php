<h1><?php echo $this->namespaceLink($namespace); ?> Namespace</h1>

<h2>Classes</h2>

<table>
  <thead>
    <tr>
      <th>Name</th>
      <th>Description</th>
    </tr>
  </thead>
  <tbody>
<?php foreach ($database['namespaces'][$namespace]['classes'] as $class => $data) {
  echo "<tr>";
  echo "<td>" . $this->classLink($namespace, $class) . "</td>";
  echo "<td>" . $data['doc']['title'] . "</td>";
  echo "</tr>";
} ?>
  </tbody>
</table>
