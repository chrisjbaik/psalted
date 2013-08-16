<?php include_once('../views/includes/header_jqm.php'); ?>
<a data-role="button" href="#page1" data-icon="plus" data-iconpos="left">New Setlist</a>
<ul data-role="listview" data-divider-theme="b" data-inset="true">
  <li data-role="list-divider" role="heading">
    Saved Setlists
  </li>
  <?php
    if (count($setlists) == 0) {
      echo "<li>You have not made any setlists yet.</li>";
    }
    foreach ($setlists as $setlist) {
      echo "<li data-theme='c'>";
      echo "<a href='/groups/{$group->url}/{$setlist->url}' data-transition='slide'>";
      echo date("M. j: ", $setlist->date) . $setlist->title;
      echo "</a>";
      echo "</li>";
    }
  ?>
</ul>

<?php include_once('../views/includes/footer_jqm.php'); ?>