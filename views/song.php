<?php include_once('../views/includes/header.php'); ?>
<h2 class='song-title'><?php echo $page_title; ?></h2>
<?php if (!empty($song)): ?>
<form class="form-inline">
  <label><i class='icon-print'></i> Print:</label>
  <?php
    echo '<a class="btn" target="_blank" href="'.$base_url.'/songs/' . $song->url . '/chords">Chords</a>';
    echo '<a class="btn" target="_blank" href="'.$base_url.'/songs/' . $song->url . '/lyrics">Lyrics</a>';
    echo ' <a id="add_setlist" class="btn" href="#">Add to Setlist</a>';
  ?>
</form>
<?php endif; ?>
<div class="row-fluid">
  <div class="span6 well">
    <?php
      if (!empty($song)) {
        echo '<form id="input" method="post" action="'.$base_url.'/songs/' . $song->id . '">';
        echo '<input type="hidden" name="_METHOD" value="PUT" />';
      } else {
        echo '<form id="input" method="post" action="'.$base_url.'/songs/new">';
      }
    ?>
      <label for='title'>Title</label>
      <input id='title' type='text' name='title' class='input-block-level' value='<?php if(!empty($song)) { echo $song->title; }?>'>
      <label for='artist'>Artist</label>
      <input id = 'artist' type='text' name='artist' class='input-block-level' value='<?php if(!empty($song)) { echo $song->artist; }?>'>
      <label for="original_key">Original Key</label>
      <select name="original_key" id="original_key" class='input-block-level'>
        <!-- TODO: set key -->
        <option value="0">C
        <option value="1">C♯ / D♭
        <option value="2">D
        <option value="3">D♯ / E♭
        <option value="4">E
        <option value="5">F
        <option value="6">F♯ / G♭
        <option value="7">G
        <option value="8">G♯ / A♭
        <option value="9">A
        <option value="10">A♯ / B♭
        <option value="11">B
      </select>
      <label for='chords'>Chords</label>
      <textarea name='chords' class='input-block-level' rows='10'><?php if(!empty($song)) { echo $song->chords; } ?></textarea>
      <label for='copyright'>Copyright</label>
      <input type='text' name='copyright' class='input-block-level' value='<?php if(!empty($song)) { echo $song->copyright; }?>'>
      <label for='spotify_id'>Spotify</label>
      <select name="spotify_id" id="spotify_id" class='input-block-level'></select>
      <div id="play"></div>
      <div class="row-fluid">
        <?php
          if (!empty($song)) {
            echo '<a href="#delete-song-modal" class="btn btn-danger pull-left" data-toggle="modal">Delete</a>';
          }
        ?>
        <div class='pull-right'>
          <button class="preview-button btn">Preview</button>
          <input type='submit' value='Submit' class='btn btn-primary' />
        </div>
      </div>
    </form>
  </div>
  <div class="span6" id='preview-area'>
    <h4>Preview</h4>
    <form class='form-inline' id='transpose'>
      <label for='transposed_key'>Transpose to</label>
      <select name="transposed_key" id="transposed_key">
        <option value="0">C
        <option value="1">C♯ / D♭
        <option value="2">D
        <option value="3">D♯ / E♭
        <option value="4">E
        <option value="5">F
        <option value="6">F♯ / G♭
        <option value="7">G
        <option value="8">G♯ / A♭
        <option value="9">A
        <option value="10">A♯ / B♭
        <option value="11">B
      </select>
    </form>
    <section id="output"></section>
  </div>
</div>
<?php if (!empty($song)): ?>
  <div id="delete-song-modal" class="modal hide fade">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h3>Double-check again...</h3>
    </div>
    <div class="modal-body">
      <p>Are you sure you want to delete this song?</p>
    </div>
    <div class="modal-footer">
      <?php echo '<form method="post" action="/songs/'.$song->id.'">'; ?>
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <input type="hidden" name="_METHOD" value="DELETE"/>
        <input type="submit" value="Go ahead!" class="btn btn-danger"/>
      </form>
    </div>
  </div>
<?php endif; ?>
<?php
  include_once('../views/includes/footer.php');
  if (!empty($song)) {
    echo "<script>$(function(){loadPreview();})</script>";
  }
  if (!empty($song->key)) {
    echo "<script>$('select[name=original_key] option[value=".$song->key."]').attr('selected', 'selected')</script>";
  }
?>