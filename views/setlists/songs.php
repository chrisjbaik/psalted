<?php include_once('../views/includes/header.php');

  $chords = array(0=>'C', 1=>'C♯/D♭', 2=>'D', 3=>'D♯/E♭', 4=>'E',  5=>'F',
                  6=>'F♯/G♭',7=>'G' , 8=>'G♯/A♭',9=>'A' ,10=>'A♯/B♭',11=>'B');
?>

<div data-role="content" id="page-setlist-songs">
<?php
  foreach ($songs as $song) {
  ?>
  <div class="setlist-songs-song">
    <div data-role="header">
      <h1 class="setlist-songs-header"><?= $song->title ?></h1>
    </div>
    <div class="setlist-songs-content">
      <div class="transpose option-toolbar">
        <div class="option-toolbar-main">
          <?php
            if (isset($song->key)) {
              $original_key = $song->key;
              $setlist_key = $song->setlist_key;
              $index = 0;
              echo '<select data-mini="true">';
                foreach ($chords as $index => $key)
                {
                  if ($index == $original_key) {
                    $key .= ' (Original Key)';
                  }
                  echo "<option value=\"$index\"";
                  if ($index == $setlist_key) {
                    echo " selected";
                  }
                  echo ">$key</option>";
                }
              echo "</select>" ;
            };
          ?>
        </div>
        <div class="option-toolbar-side">
          <button type="button" class="transpose-all ui-btn ui-btn-b ui-mini ui-shadow ui-corner-all">Transpose All</button>
        </div>
      </div>

      <div class="chordsify chordsify-raw" data-original-key="<?= $song->key ?>" data-transpose-to="<?= $song->setlist_key ?>"><?= htmlspecialchars($song->chords) ?></div>

      <a href="#" class="ui-btn ui-btn-inline ui-mini ui-corner-all ui-shadow ui-btn-icon-left ui-icon-carat-l" data-rel="back" data-direction="reverse">Back</a>
    </div>
  </div>
  <?php
  }
?>
</div>

<?php include_once('../views/includes/footer.php'); ?>