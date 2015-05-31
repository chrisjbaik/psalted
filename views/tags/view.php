<?php include_once('../views/includes/header.php'); ?>
<div data-role="content">
  <ul data-role="listview" data-divider-theme="a" data-inset="true">
    <li data-role="list-divider" role="heading">
      <?php echo "Tag: $tag->name" ?>
    </li>
    <?php
      if (count($songs) == 0) {
        echo "<li>There are currently no songs.</li>";
      }
      foreach ($songs as $song) {
      ?>
        <li class="listview">
          <a href="/songs/<?= $song->url ?>" data-transition="slide">
            <label>
              <h2 class="listview-heading"><?= $song->title ?><?php if ($song->certified) { echo "&nbsp;&#10004"; } ?></h2>
              <span class="listview-footer"><?= $song->artist ?></span>
            </label>
          </a>
          <!-- <a href="/songs/<?= $song->url ?>" data-transition="slide"></a> -->
        </li>
      <?php
      }
    ?>
  </ul><!--list view-->

<?php include_once('../views/includes/footer.php'); ?>