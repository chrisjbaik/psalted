<?php include_once('../views/includes/header_jqm.php'); ?>
<div data-role="content" id='song-view'>
  <div id='song-spotify'>
    <?php 
      if (!empty($song->spotify_id)) {
        echo "<iframe src='https://embed.spotify.com/?uri={$song->spotify_id}' width='100%' height='80' frameborder='0' allowtransparency='true'></iframe>";
      }
    ?>
  </div>
  <div id='song-chords'></div>
  <script>
    var chords = convertLyrics(<?= $song->key ?>, '<?= $song->chords ?>')
    $('#song-chords').html(chords);
  </script>
</div>
<?php include_once('../views/includes/footer_jqm.php'); ?>