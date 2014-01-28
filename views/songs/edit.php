<?php include_once('../views/includes/header.php'); ?>

<?php if (!empty($song)): ?>
<div data-role="panel" id="right-panel" data-position="right">
  <ul data-role="listview">
    <li data-icon="delete" data-theme="b">
      <a data-rel='popup' data-position-to='window' href="#song-delete-popup" class='songs-delete-link' id="delete-song" data-id="<?php echo $song->id; ?>">Delete Song</a>
    </li>
  </ul>
</div>
<?php endif; ?>

<div data-role="content">
  <form method='post' data-ajax='false' <?php if (!empty($song)) { echo "action='/songs/{$song->url}'"; } ?>>
    <label for="song-edit-title-input" class="ui-hidden-accessible">Title</label>
    <input type="text" name="title" id="song-edit-title-input" placeholder="Title" value="<?php if(!empty($song->title)) { echo $song->title; } ?>">
    <label for="song-edit-artist-input" class="ui-hidden-accessible">Artist</label>
    <input type="text" name="artist" id="song-edit-artist-input" placeholder="Artist" value="<?php if(!empty($song->title)) { echo $song->artist; } ?>">
    <label for="key" class="select">Original Key</label>
    <select name="key" id="original-key" data-key="<?php if (!empty($song->key)) { echo $song->key; } ?>">
      <option value="0">C</option>
      <option value="1">C♯/D♭</option>
      <option value="2">D</option>
      <option value="3">D♯/E♭</option>
      <option value="4">E</option>
      <option value="5">F</option>
      <option value="6">F♯/G♭</option>
      <option value="7">G</option>
      <option value="8">G♯/A♭</option>
      <option value="9">A</option>
      <option value="10">A♯/B♭</option>
      <option value="11">B</option>
    </select>
    <label for="chord-lyrics">Chords &amp; Lyrics</label>
    <textarea cols="40" rows="100" name="chords" id="chord-lyrics" style="height:200px;"><?php if(!empty($song->chords)) { echo $song->chords; } ?></textarea>
    
    <ul id='song-tags' data-role="listview" data-inset="true" data-divider-theme="a" data-split-icon='delete' data-split-theme='c'>
      <li data-role="list-divider" role="heading">Tags</li>
      <?php
      if (!empty($tags)) {
        foreach ($tags as $tag) {
          echo "<li data-id=\"{$tag->id}\">";
          echo "<a href=\"#\">{$tag->name}</a>";
          echo '<a href="#" class="remove-tag" data-theme="b">X</a>';
          echo "<input type=\"hidden\" name=\"tags[]\" value=\"{$tag->id}\">";
        }
      }
      ?>
    </ul>

    <!-- TAG THIS SONG -->
    <div id="new-tag-choices-box" style="padding-bottom: 15px;"> 
      <ul id="new-tag-choices" data-filter-reveal="true" data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="Tag this song...">
      </ul>
    </div>    

    <label for="copyright" class="ui-hidden-accessible">Copyright</label>
    <input type="text" name="copyright" id="copyright" placeholder="Copyright" value="<?php if(!empty($song->copyright)) { echo $song->copyright; } ?>">
    <label for="spotify" class="ui-hidden-accessible">Spotify</label>
    <select name="spotify_id" id="spotify" data-spotify-id="<?php if (!empty($song->spotify_id)) { echo $song->spotify_id; } ?>">
        <option value="">Enter Title and Artist to Search Spotify</option>
    </select>
    <div id='spotify-preview'></div>
    <div class="ui-grid-a">
      <div class="ui-block-a"><a href="#song-preview" data-rel="popup" data-position-to="window" data-transition="pop" data-icon="refresh" data-theme="c" class="song-preview ui-shadow ui-btn ui-corner-all" data-icon="delete">Preview</a></div>
      <div class="ui-block-b"><input type="submit" data-role="button" data-theme="b" value="Submit" /></div>
    </div>
    <?php 
      if (!empty($song)) {
        echo "<input type='hidden' name='_METHOD' value='PUT' />";
      } 
    ?>
  </form>
  <div data-role="popup" id="song-preview" data-dismissible="true" class="ui-corner-all">
    <div data-role="header" class="ui-corner-top">
      <h1>Song Preview</h1>
    </div>
    <div data-role="content" class="ui-corner-bottom ui-content">
      <div id="song-chords" class="chordsify"></div>
    </div>
  </div>
  <div data-role="popup" id="song-delete-popup" data-overlay-theme="a" data-theme="a" data-dismissible="false" class="ui-corner-all">
    <div data-role="header" class="ui-corner-top">
      <h1>Delete Song?</h1>
    </div>
    <div data-role="content" class="ui-corner-bottom ui-content">
      <p>Are you sure you want to delete this song? This action cannot be undone.</p>
      <a href="#" data-role="button" data-inline="true" data-rel="back">Cancel</a>
      <form id="song-delete-form" method='post' style='display: inline;' data-ajax='false'>
        <input type='hidden' name='_METHOD' value='DELETE' />
        <input type='submit' data-role="button" data-inline="true" data-rel="back" data-transition="flow" data-theme="b" value='Delete' />
      </form>
    </div>
  </div>
</div>
<?php include_once('../views/includes/footer.php'); ?>