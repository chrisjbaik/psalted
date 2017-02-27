<?php include_once('../views/includes/header.php'); ?>

<script>
$(document).ready(function () {
  $chordsify = $('.chordsify-raw');
  if ($chordsify.length > 0)
    $chordsify.chordsify();
});
</script>

<div data-role="content" id="set-lyrics-view">

  <div id="setlist-nav">
    <ul data-role="listview" data-divider-theme="a" data-inset="true">
      <li data-role="list-divider" role="heading">Songs</li>
<?php foreach ($songs as $song) { ?>
        <li class="setlist-view-song" data-icon="false">
          <a href="#<?= $song->url ?>" data-ajax="false"><?= $song->title ?></a>
        </li>
<?php } ?>
    </ul>
  </div>

<?php foreach ($songs as $song) { ?>
  <div class="setlist-songs-song">
    <div data-role="header">
      <h1 class="setlist-songs-header" id="<?= $song->url ?>"><?= $song->title ?></h1>
    </div>
    <div class="chordsify chordsify-raw">
<?= htmlspecialchars($song->lyrics) ?>
    </div>
  </div>
<?php } ?>
</div>
<?php include_once('../views/includes/footer.php'); ?>
