<?php include_once('../views/includes/header.php'); ?>
<div data-role="content">
  <ul data-role="listview" data-divider-theme="a" data-inset="true">
    <li data-role="list-divider" role="heading">
      Setlists
    </li>
    <li data-theme="e" data-icon="plus"><a href='/personal/new'>New Setlist</a></li>
    <?php
      foreach ($setlists as $setlist) {
        echo "<li data-theme='c'>";
        echo "<a href='/personal/{$setlist->url}' data-transition='slide'>";
        echo date("M j: ", $setlist->date) . $setlist->title;
        echo "</a>";
        echo "</li>";
      }
    ?>
  </ul>
</div>
<?php include_once('../views/includes/footer.php'); ?>