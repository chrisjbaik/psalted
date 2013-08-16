<?php include_once('../views/includes/header_jqm.php'); ?>
<div data-role="content">
  <a data-role="button" href="#page1" data-icon="plus" data-iconpos="left">New Song</a>
  <ul data-role="listview" data-divider-theme="b" data-inset="true">
    <li data-role="list-divider" role="heading">
      Songs
    </li>
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

<?php include_once('../views/includes/footer_jqm.php'); ?>