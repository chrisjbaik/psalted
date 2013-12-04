<?php include_once('../views/includes/header.php');

  $chords = array(0=>'C', 1=>'C♯/D♭', 2=>'D', 3=>'D♯/E♭', 4=>'E',  5=>'F',
                  6=>'F♯/G♭',7=>'G' , 8=>'G♯/A♭',9=>'A' ,10=>'A♯/B♭',11=>'B');
?>

<div data-role="panel" id="right-panel" data-theme="c" data-position="right">
  <ul data-role="listview" data-theme="c">
    <?php 
    if ($isAdmin){
      if ($song->certified == true) { 
        echo "<li data-icon='gear'><a data-ajax='false' href='/songs/$song->id/decertify'>Decertify this Song</a></li>";
      } else {
        echo "<li data-icon='gear'><a data-ajax='false' href='/songs/$song->id/certify'>Certify this Song</a></li>";
      }
    } else {
      if ($song->certified == true) {
        echo "<li><a>This song is certified</a></li>";
      } else {
        echo "<li data-icon='gear'><a data-ajax='false' href='/songs/{$song->id}/edit'>Edit Song</a></li>";
      }
    }
    ?>
  </ul>
</div>

<div data-role="content" id="song-view">
  <div id="song-spotify">
    <?php 
      if (!empty($song->spotify_id)) {
        echo "<iframe src='https://embed.spotify.com/?uri={$song->spotify_id}' width='100%' height='80' frameborder='0' allowtransparency='true'></iframe>";
      }
    ?>
  </div>
  <div id="transpose">
    <?php
      if (!empty($song->key)) {
        $original_key = $song->key;
        $index = 0;
        echo '<select id="select-transpose" data-mini="true">';
          foreach ($chords as $index => $key)
          {
            if ($index == $original_key) {
              echo "<option value=\"$index\" selected>$key (Original Key)</option>";
            } else {
              echo "<option value=\"$index\">$key</option>";
            }
          }
        echo "</select>" ;
      };
    ?>
  </div>

  <div id="song-chords" class="chordsify chordsify-raw" data-original-key="<?= $song->key ?>"><?= htmlspecialchars($song->chords) ?></div>
</div>
<?php include_once('../views/includes/footer.php'); ?>
