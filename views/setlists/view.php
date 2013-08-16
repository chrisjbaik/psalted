<?php include_once('../views/includes/header_jqm.php'); ?>
<div data-role="content">
  <fieldset class="ui-grid-a">
    <div class="ui-block-a"><button type="submit" data-theme="c">Print PDF</button></div>
    <div class="ui-block-b"><button type="submit" data-theme="b">Add a song</button></div>
  </fieldset>
  <ul data-role="listview" data-divider-theme="b" data-inset="true">
    <?php
      foreach ($songs as $song) {
        echo "<li data-theme='c'>";
        echo "<a href='/songs/{$song->url}' data-transition='slide'>";
        echo $song->title;
        echo "</a></li>";
      }
    ?>
  </ul>
</div>

<?php include_once('../views/includes/footer_jqm.php'); ?>