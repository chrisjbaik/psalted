<?php include_once('../views/includes/header.php'); ?>
<div data-role="content">
  <a download="songbook.pdf" href="/band/songbook/download" data-ajax="false" data-theme="b" data-role="button">Print Songbook</a>
  <ul id="songs-list-songs" data-role="listview" data-divider-theme="a" data-inset="true">
    <li data-role="list-divider" role="heading">
      Songs
    </li>
    <?php
      if (count($songs) == 0) {
        echo "<li>There are currently no songs.</li>";
      }
      foreach ($songs as $song) {
      ?>
        <li class="listview" data-title="<?= $song->title ?>" data-key="<?= $song->key ?>" data-certified="<?= $song->certified ?>" data-chords="<?= $song->has_chords ?>" data-pop="<?= $song->popularity ?>">
          <a href="/songs/<?= $song->url ?>/edit" data-transition="slide">
            <!-- <input type="checkbox" name="checked_songs[]" value="<?= $song->id ?>"> -->
            <!-- <div class="song-label-key" data-chord="<?= $song->key ?>"><?= $song->keyToString() ?></div> -->
            <!-- <h2 class="listview-heading"><?= $song->song_code ?> --><h2 class="listview-heading"><?= song::formatSongCode($song->song_code) ?><span>  </span><?= $song->title ?></h2>
            <?= $song->artist ? '<span class="listview-footer">'.$song->artist.'</span>' : '' ?>
          </a>
        </li>
      <?php
      }
    ?>
  </ul><!--list view-->
</div> <!--list view-->
<?php include_once('../views/includes/footer.php'); ?>
