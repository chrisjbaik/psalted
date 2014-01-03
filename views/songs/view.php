<?php include_once('../views/includes/header.php');

  $chords = array(0=>'C', 1=>'C♯/D♭', 2=>'D', 3=>'D♯/E♭', 4=>'E',  5=>'F',
                  6=>'F♯/G♭',7=>'G' , 8=>'G♯/A♭',9=>'A' ,10=>'A♯/B♭',11=>'B');
?>

<div data-role="panel" id="right-panel" data-position="right">
  <ul data-role="listview">
    <?php 
    if ($song->certified) {
      if ($isAdmin) {
        echo '<li data-icon="gear"><a data-ajax="false" href="/songs/'.$song->id.'/decertify">Decertify this Song</a></li>';
        echo '<li data-icon="gear"><a data-ajax="false" href="/songs/'.$song->id.'/edit">Edit Song</a></li>';
      } else {
        echo '<li>This song is certified</li>';
      }
    } else {
      if ($isAdmin) {
        echo '<li data-icon="gear"><a data-ajax="false" href="/songs/'.$song->id.'/certify">Certify this Song</a></li>';
      }
      echo '<li data-icon="gear"><a data-ajax="false" href="/songs/'.$song->id.'/edit">Edit Song</a></li>';
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
  
  <div id="song-tags">
    <?php
      $tags = $song->tags()->find_many();
      if (!empty($tags)) {
        echo "<b>Tags:  </b>";
        foreach ($tags as $tag) {
          if (empty($tag->url)) {
            $tag->save();
          }
          $name = $tag->name;
          echo "<a href='/tags/{$tag->url}'><input type=\"button\" data-mini=\"true\" data-theme=\"c\" data-inline=\"true\" data-enhanced=\"false\" value=\"$name\"></input></a>";
        }
      }
    ?>
  </div>
</div>

<?php include_once('../views/includes/footer.php'); ?>
