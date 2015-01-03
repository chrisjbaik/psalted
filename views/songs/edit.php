<?php include_once('../views/includes/header.php'); ?>

<div data-role="content">
  <form method='post' data-ajax='false' <?php if (!empty($song)) { echo "action='/songs/{$song->url}'"; } ?>>
    <label for="song-edit-title-input" class="ui-hidden-accessible">Title</label>
    <input type="text" name="title" id="song-edit-title-input" placeholder="Title" value="<?php if(!empty($song->title)) { echo $song->title; } ?>">
    <label for="song-edit-artist-input" class="ui-hidden-accessible">Artist</label>
    <input type="text" name="artist" id="song-edit-artist-input" placeholder="Artist" value="<?php if(!empty($song->title)) { echo $song->artist; } ?>">
    <label for="key" class="select">Original Key</label>
    <select name="key" id="original-key" data-key="<?php if (isset($song->key)) { echo $song->key; } ?>">
      <option value="auto">Auto-detect</option>
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
    <label>Chords
      <textarea cols="40" rows="100" name="chords" id="chords"><?php if(!empty($song->chords)) { echo $song->chords; } ?></textarea>
    </label>
    <label>
      <input type="checkbox" id="chords-as-lyrics" name="chords_as_lyrics" <?php if($song->chords_as_lyrics) { echo "checked"; } ?> /> Automatically use chord formatting to generate lyrics (default)
    </label>
    <label id="lyrics-container" <?php if($song->chords_as_lyrics) { echo "class='hidden'"; } ?>>Lyrics
      <textarea cols="40" rows="100" name="lyrics" id="lyrics"><?php if(!empty($song->lyrics)) { echo $song->lyrics; } ?></textarea>
    </label>
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
      <div id="song-chords" class="chordsify chordsify-raw"></div>
    </div>
  </div>
</div>
<?php include_once('../views/includes/footer.php'); ?>