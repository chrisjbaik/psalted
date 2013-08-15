<?php
  include_once('../views/includes/header.php');
?>
<a href='/link?provider=facebook'>Connect with Facebook</a>
<h2>Social Accounts</h2>
<table class='table'>
  <tr>
    <th>Provider</th>
    <th>ID</th>
    <th></th>
  </tr>
<?php
  foreach ($hybridauths as $ha) {
    echo '<tr>
      <td>'.$ha->provider.'</td>
      <td>'.$ha->uid.'</td>
      <td><a href="/unlink?provider='.$ha->provider.'">Unlink</a></td>
    </tr>';
  }
?>
</table>
<?php
  include_once('../views/includes/footer.php');