<?php include_once('../views/includes/header.php');

  $chords = array(0=>'C', 1=>'C♯/D♭', 2=>'D', 3=>'D♯/E♭', 4=>'E',  5=>'F',
                  6=>'F♯/G♭',7=>'G' , 8=>'G♯/A♭',9=>'A' ,10=>'A♯/B♭',11=>'B');
?>

<div data-role="panel" id="right-panel" data-position="right">
  <ul data-role="listview">
    <?php 
    if (!$song->certified or $isAdmin) {
      if ($isAdmin) {
        if ($song->certified) {
          echo '<li data-icon="back"><a data-ajax="false" href="/songs/'.$song->url.'/decertify">Decertify this Song</a></li>';
        } else {
          echo '<li data-icon="check"><a data-ajax="false" href="/songs/'.$song->url.'/certify">Certify this Song</a></li>';
        }
      }
      
      echo '<li data-icon="edit"><a data-ajax="false" href="/songs/'.$song->url.'/edit">Edit Song</a></li>';

      if ($isAdmin and !$song->certified) {
        echo '<li data-icon="delete"><a data-rel="popup" data-position-to="window" href="#song-delete-popup">Delete Song</a></li>';
      }
    } else {
      echo '<li>This song is certified</li>';
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
      if (isset($song->key)) {
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

  <div data-role="popup" id="song-delete-popup" data-overlay-theme="a" data-theme="a" data-dismissible="false" class="ui-corner-all">
    <div data-role="header" class="ui-corner-top">
      <h1>Delete Song?</h1>
    </div>
    <div data-role="content" class="ui-corner-bottom ui-content">
      <p>Are you sure you want to delete this song? This action cannot be undone.</p>
      <a href="#" data-role="button" data-inline="true" data-rel="back">Cancel</a>
      <form method="POST" action="/songs/<?= $song->url ?>" style="display: inline;" data-ajax="false">
        <input type="hidden" name="_METHOD" value="DELETE" />
        <input type="submit" data-role="button" data-inline="true" data-rel="back" data-transition="flow" data-theme="b" value='Delete' />
      </form>
    </div>
  </div>
</div>
<?php include_once('../views/includes/footer.php'); ?>
