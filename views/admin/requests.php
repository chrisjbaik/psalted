<?php include_once('../views/includes/header_jqm.php'); ?>
<div data-role="content">
  <ul data-role="listview" data-inset="true" data-icon='check'>
    <li data-role='list-divider' role='heading'>Approve Pending Requests</li>
    <?php
      if (count($requests) === 0) {
        echo "<li>There are currently no pending requests.</li>";
      }
      foreach ($requests as $r) {
        echo "<li data-theme='c'>";
        echo "<a data-ajax='false' href='/admin/requests/{$r->id}/approve'>{$r->email}</a>";
        echo "</li>";
      }
    ?>
  </ul>
</div>
<?php include_once('../views/includes/footer_jqm.php'); ?>