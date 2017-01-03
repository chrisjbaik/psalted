<?php include_once('../views/includes/header.php'); ?>

<script>
$(document).ready(function () {
  $chordsify = $('.chordsify-raw');
  if ($chordsify.length > 0)
    $chordsify.chordsify();
});
</script>

<div data-role="content" id="set-lyrics-view">

  <div id="set-lyrics-nav">
    <ul>
<?php
foreach ($songs as $song) {
  echo '<a href="#'.$song->url.'" data-ajax="false"><li>'.$song->title.'</li></a>'."\n";
}
?>
    </ul>
  </div>

  <div id="set-lyrics">
<?php
foreach ($songs as $song) {
  echo '<h3 id="'.$song->url.'">'.$song->title.'</h3>' ."\n";
  echo '<div class="chordsify chordsify-raw">'.
    htmlspecialchars($song->lyrics).'</div>'."\n";
}
?>
  </div>
</div>
<?php include_once('../views/includes/footer.php'); ?>
