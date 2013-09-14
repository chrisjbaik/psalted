<?php include_once('../views/includes/header_jqm.php'); ?>

<?php if (!empty($song)): ?>
<div data-role="panel" id="right-panel" data-theme="c" data-position="right">
  <ul data-role="listview" data-theme="c">
    <li data-icon="delete">
      <a data-rel='popup' data-position-to='window' href="#song-delete-popup" class='songs-delete-link' id="delete-song" data-id="<?php echo $song->id; ?>">Delete Song</a>
    </li>
  </ul>
</div>
<?php endif; ?>

<div data-role="content">
  <form method='post' data-ajax='false' <?php if (!empty($song)) { echo "action='/songs/{$song->id}'"; } ?>>
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
    <select name="spotify_id" id="spotify">
        <option value="">Enter Title and Artist to Search Spotify</option>
    </select>
    <div id='spotify-preview'></div>
    <div class="ui-bar">
      <?php if (!empty($song)): ?>
      <fieldset data-role="controlgroup" data-type="horizontal" data-mini="true" class = "ui-btn-left">
        <a href="" data-role="button" data-icon="delete" data-theme="c">Delete</a>
      </fieldset>
      <?php endif; ?>
      <fieldset data-role="controlgroup" data-type="horizontal" data-mini="true" class="ui-btn-right" style=" float: right;">
        <a class='song-preview' href="#song-preview" data-rel="popup" data-position-to="window" data-transition="pop" data-role="button" data-icon="refresh" data-theme="c">Preview</a>
        <input type="submit" data-role="button" data-icon="plus" data-theme="b" value="Submit" />
      </fieldset>
    </div>
    <?php 
      if (!empty($song)) {
        echo "<input type='hidden' name='_METHOD' value='PUT' />";
      } 
    ?>
  </form>
  <div data-role="popup" id="song-preview" data-overlay-theme="a" data-theme="c" data-dismissible="true" class="ui-corner-all">
    <div data-role="header" data-theme="a" class="ui-corner-top">
      <h1>Song Preview</h1>
    </div>
    <div data-role="content" data-theme="d" class="ui-corner-bottom ui-content">
      <div id="song-chords"></div>
    </div>
  </div>
  <script>
    <?php if(!empty($song->key)): ?>
      $('select[name=key]').val('<?php echo $song->key; ?>');
    <?php endif; ?>
    <?php if(!empty($song->spotify_id)): ?>
      getSpotifyMeta($('#song-edit-title-input').val(), $('#song-edit-artist-input').val(), '<?= $song->spotify_id ?>');
    <?php endif; ?>
    function getSpotifyMeta(title, artist, preload_id) {
      var searchURL = 'http://ws.spotify.com/search/1/track.json?q=' + title + '+'+ artist;
      jQuery.get(searchURL, function(data, textStatus, jqXHR) {
        var options = '';
        var songIdTable = {};
        for (var i=0; i < Math.min(5, data.tracks.length); i++) {
          if (data.tracks[i]['external-ids'] && data.tracks[i]['external-ids'][0]) {
            var id = data.tracks[i]['external-ids'][0].id;
            if (! songIdTable[id]) {
              songIdTable[id] = true;
              options += '<option value="'+data.tracks[i].href+'">'+data.tracks[i].name +' (' +data.tracks[i].artists[0].name + ')';
            }
          }
        }
        options += '<option value="">(None of the above)';
        $('#spotify').html(options);
        if (preload_id) {
          $('#spotify').val(preload_id);
        }
        $('#spotify').selectmenu('refresh');
        updateSpotifyPreview();
      }, 'json' )
    }

    function updateSpotifyPreview() {
      var songUrl = $('#spotify').val();
      if (songUrl) {
        $('#spotify-preview').html('<iframe src="https://embed.spotify.com/?uri=' +songUrl + '" width=100%"'+$('#play').width()+'" height="80" frameborder="0" allowtransparency="true"></iframe>');
      } else {
        $('#spotify-preview').html('');
      }
    }

    $('.song-preview').click(function (e) {
      var chords = convertLyrics($('#original-key').val(), $('#chord-lyrics').val());
      $('#song-chords').html(chords);
    });

    $('#spotify').change(function (e) {
      updateSpotifyPreview();
    });

    $('#song-edit-title-input, #song-edit-artist-input').keyup(function (e) {
      if (window.spotifySearchTimeout) clearTimeout(window.spotifySearchTimeout);
      window.spotifySearchTimeout = setTimeout(function () {
        getSpotifyMeta($('#song-edit-title-input').val(), $('#song-edit-artist-input').val());
      }, 150);
    });
  </script>
  <div data-role="popup" id="song-delete-popup" data-overlay-theme="a" data-theme="c" data-dismissible="false" class="ui-corner-all">
    <div data-role="header" data-theme="a" class="ui-corner-top">
      <h1>Delete Song?</h1>
    </div>
    <div data-role="content" data-theme="d" class="ui-corner-bottom ui-content">
      <p>Are you sure you want to delete this song? This action cannot be undone.</p>
      <a href="#" data-role="button" data-inline="true" data-rel="back" data-theme="c">Cancel</a>
      <form id="song-delete-form" method='post' style='display: inline;' data-ajax='false'>
        <input type='hidden' name='_METHOD' value='DELETE' />
        <input type='submit' data-role="button" data-inline="true" data-rel="back" data-transition="flow" data-theme="b" value='Delete' />
      </form>
    </div>
  </div>
  <script>
    $(document).on('click', '.songs-delete-link', function (e) {
      $('#song-delete-form').attr('action', '/songs/' + $(this).attr('data-id'));
    })
  </script>  
</div>
<?php include_once('../views/includes/footer_jqm.php'); ?>