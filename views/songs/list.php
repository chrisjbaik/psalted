<?php include_once('../views/includes/header.php'); ?>
<div data-role="content">
  <div class="songs-list-options">
    <fieldset data-role="controlgroup">
      <input name="view" id="songs-list-view-all" value="all" checked type="radio">
      <label for="songs-list-view-all">All songs</label>
      <input name="view" id="songs-list-view-chords" value="chords" type="radio">
      <label for="songs-list-view-chords">Only songs with chords</label>
      <input name="view" id="songs-list-view-certified" value="certified" type="radio">
      <label for="songs-list-view-certified">Only certified songs</label>
    </fieldset>
    <fieldset data-role="controlgroup" data-type="horizontal">
      <input name="sortby" id="songs-list-sortby-title" value="title" checked type="radio">
      <label for="songs-list-sortby-title">Sort by Title</label>
      <input name="sortby" id="songs-list-sortby-key" value="key" type="radio">
      <label for="songs-list-sortby-key">Sort by Key</label>
    </fieldset>
  </div>

  <ul id="songs-list-songs" data-role="listview" data-divider-theme="a" data-inset="true">
    <li data-role="list-divider" role="heading">
      Songs
    </li>
    <li data-theme="c" data-icon="plus"><a href="/songs/new">New Song</a></li>
    <?php
      if (count($songs) == 0) {
        echo "<li>There are currently no songs.</li>";
      }
      foreach ($songs as $song) {
      ?>
        <li class="listview-checkbox" data-title="<?= $song->title ?>" data-key="<?= $song->key ?>" data-certified="<?= $song->certified ?>" data-chords="<?= $song->has_chords ?>">
          <a href="#">
            <label class="song-label<?= $song->artist ? '' : ' song-no-artist' ?>">
              <input type="checkbox" name="checked_songs[]" value="<?= $song->id ?>">
              <div class="song-label-key" data-chord="<?= $song->key ?>"><?= $song->keyToString() ?></div>
              <h2 class="listview-heading"><?= $song->title ?><?php if ($song->certified) { echo "&nbsp;<span class=\"certified-icon ui-icon-check ui-alt-icon\"></span>"; } ?></h2>
              <?= $song->artist ? '<span class="listview-footer">'.$song->artist.'</span>' : '' ?>
            </label>
          </a>
          <a href="/songs/<?= $song->url ?>" data-transition="slide"></a>
        </li>
      <?php
      }
    ?>
  </ul><!--list view-->
  <div data-role="footer" data-position="fixed" class="hidden in">
    <a data-rel='popup' data-position-to='window' href="#song-setlist-popup" class="ui-btn ui-corner-all ui-btn-b ui-icon-plus ui-btn-icon-left">
      Add to Setlist (<span class="songs-list-selected-count">0</span> selected)
    </a>
  </div>
  <div id="song-setlist-popup" data-role="popup" data-overlay-theme="a" data-theme="c" class="ui-corner-all" data-dismissible="true">
    <div data-role="header" data-theme="a" class="ui-corner-top">
      <h1>Add <span class="songs-list-selected-count">0</span> Song(s) to Setlist</h1>
    </div>
    <div data-role="content" data-theme="d" class="ui-corner-bottom ui-content">
      <a id="song-setlist-popup-new" href="/setlists/new" class="ui-btn ui-corner-all ui-btn-a ui-icon-plus ui-btn-icon-left">Create New Setlist</a>
      <div class="popup-divider"><strong>or</strong></div>
      <form id="song-setlist-popup-form" data-ajax="false" method="post" action="/songs/addtosetlist">
        <select name="setlist">
          <?php
            foreach ($setlists as $setlist) {
              echo "<option value={$setlist->id}>";
              if ($setlist->group_name) {
                echo $setlist->group_name;
              } else {
                echo 'Personal';
              }
              echo ": {$setlist->title}</option>";
            }
          ?>
        </select>
        <input type="hidden" value="" name="songs">
        <input type="submit" value="Add to Existing Setlist" data-theme="b" />
      </form>
    </div>
  </div>
</div>

<?php include_once('../views/includes/footer.php'); ?>