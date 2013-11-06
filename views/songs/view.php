<?php include_once('../views/includes/header.php');

  $chords = array(0=>'C', 1=>'C#', 2=>'D', 3=>'D♯', 4=>'E',  5=>'F',
                  6=>'F#',7=>'G' , 8=>'G#',9=>'A' ,10=>'A#',11=>'B');
?>
<div data-role="panel" id="right-panel" data-theme="c" data-position="right">
  <ul data-role="listview" data-theme="c">
    <li data-icon="gear"><a href="/songs/<?php echo $song->id; ?>/edit">Edit Song</a></li>
  </ul>
</div>

<div data-role="content" id='song-view'>
  <div id='song-spotify'>
    <?php 
      if (!empty($song->spotify_id)) {
        echo "<iframe src='https://embed.spotify.com/?uri={$song->spotify_id}' width='100%' height='80' frameborder='0' allowtransparency='true'></iframe>";
      }
    ?>
  </div>
  <div id='transpose'>
    <?php
      if (!empty($song->key)) {
        $original_key = $song->key;
        $index = 0;
        echo "<select id='select-transpose' data-mini='true'>";
          for ($index=0; $index<12; $index++)
            {
              if ($index == $original_key) { 
                echo "<option value ='$index' selected='selected'>$chords[$original_key] (Original Key) </option>";
              } else {
                echo "<option value = '$index'>$chords[$index]</option>";
              }
            }
        echo "</select>" ;
      };
    ?>
  </div>

  <div id='song-chords' data-key='<?= $song->key ?>' data-chords-json="<?= htmlspecialchars($song->chords) ?>"></div>

  <script>
  </script>
</div>
<?php include_once('../views/includes/footer.php'); ?>
