<?php include_once('../views/includes/header_jqm.php'); ?>
<div data-role="content">
  <form method='post' data-ajax='false'>
    <label for="song-edit-title-input" class="ui-hidden-accessible">Title</label>
    <input type="text" name="title" id="song-edit-title-input" placeholder="Title" value="<?php if(!empty($song->title)) { echo $song->title; } ?>">
    <label for="song-edit-artist-input" class="ui-hidden-accessible">Artist</label>
    <input type="text" name="artist" id="song-edit-artist-input" placeholder="Artist" value="<?php if(!empty($song->title)) { echo $song->artist; } ?>">
    <label for="key" class="select">Original Key</label>
    <select name="key" id="original-key">
      <option value="0">C</option>
      <option value="1">Db/C#</option>
      <option value="2">D</option>
      <option value="3">Eb/D#</option>
      <option value="4">E</option>
      <option value="5">F</option>
      <option value="6">F#/Gb</option>
      <option value="7">G</option>
      <option value="8">Ab/G#</option>
      <option value="9">A</option>
      <option value="10">Bb/A#</option>
      <option value="11">B</option>
    </select>
    <label for="chord-lyrics">Chords &amp; Lyrics</label>
    <textarea cols="40" rows="100" name="chords" id="chord-lyrics" style="height:200px;"><?php if(!empty($song->chords)) { echo $song->chords; } ?></textarea>
    <label for="copyright" class="ui-hidden-accessible">Copyright</label>
    <input type="text" name="copyright" id="copyright" placeholder="Copyright" value="<?php if(!empty($song->copyright)) { echo $song->copyright; } ?>">
    <label for="spotify" class="ui-hidden-accessible">Spotify</label>
    <select name="spotify" id="spotify">
        <option value="0">Spotify Place Holder</option>
    </select>
    <div class="ui-bar"> 
      <fieldset data-role="controlgroup" data-type="horizontal" data-mini="true" class = "ui-btn-left">
        <a href="" data-role="button" data-icon="delete" data-theme="c">Delete</a>
      </fieldset>
      <fieldset data-role="controlgroup" data-type="horizontal" data-mini="true" class="ui-btn-right" style=" float: right;">
        <a href="#preview" data-rel="popup" data-position-to="window" data-transition="pop" data-role="button" data-icon="refresh" data-theme="c">Preview</a>
        <input type="submit" data-role="button" data-icon="plus" data-theme="b" value="Submit" />
      </fieldset>
    </div>
  </form>
  <?php if(!empty($song->key)): ?>
    <script>
      $('select[name=key]').val('<?php echo $song->key; ?>');
    </script>
  <?php endif; ?>
</div>
<?php include_once('../views/includes/footer_jqm.php'); ?>