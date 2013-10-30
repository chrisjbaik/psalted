<?php include_once('../views/includes/header.php'); ?>
<div data-role="content">
  <ul data-role="listview" data-divider-theme="a" data-inset="true">
    <li data-role="list-divider" role="heading">
      Songs
    </li>
    <li data-theme="e" data-icon="plus"><a href="/songs/new">New Song</a></li>
    <?php
      if (count($songs) == 0) {
        echo "<li>There are currently no songs.</li>";
      }
      foreach ($songs as $song) {
        echo "<li data-theme='c'>";
        echo "<a href='/songs/{$song->url}' data-transition='slide'>";
        echo $song->title;
        echo "</a>";
        echo "</li>";
      }
    ?>
  </ul><!--list view-->
</div>

<?php include_once('../views/includes/footer.php'); ?>