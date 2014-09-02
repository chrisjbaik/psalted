<?php include_once('../views/includes/header.php'); ?>
<div data-role="panel" id="right-panel" data-position="right">
  <ul data-role="listview">
    <li data-icon="gear">
      <?php
        if (!empty($group)) {
          $base_url = "/groups/{$group->url}/{$setlist->url}";
        } else {
          $base_url = "/personal/{$setlist->url}";
        }
        echo "<a href='{$base_url}/edit'>Edit Setlist</a>";
      ?>
    </li>
    <li data-icon="delete" data-theme="b">
      <a data-rel='popup' data-position-to='window' href="#setlist-delete-popup" class='setlists-delete-link' id="delete-group" data-setlist-url="<?php echo $setlist->url; ?>" <?php if (!empty($group->url)) { echo "data-group-url='{$group->url}'"; } ?>>
        Delete Setlist
      </a>
    </li>
  </ul>
</div>
<div data-role="content" id="page-setlist-view">
  <div class="option-toolbar">
    <div class="option-toolbar-main">
      <a id="btn-pdf-save" download="<?= $pdf_file ?>" href="<?= $pdf_url ?>" data-ajax="false" data-role="button" data-theme="b" <?php if (count($songs) == 0) echo 'disabled' ?>>Save PDF</a>
    </div>
    <div class="option-toolbar-side">
      <a id="btn-settings" href="<?= $base_url ?>/settings" data-transition="slide" data-role="button" class="ui-btn ui-shadow ui-corner-all ui-icon-gear ui-btn-icon-left">Settings</a>
    </div>
  </div>
  <ul data-role="listview" data-divider-theme="a" data-inset="true">
    <li data-role="list-divider" role="heading">Songs</li>
    <?php
      if (count($songs) === 0) {
        echo "<li>There are currently no songs in this setlist.</li>";
      }
      foreach ($songs as $song) {
        echo "<li>";
        echo "<a href='/songs/{$song->url}' data-transition='slide'>";
        echo $song->title;
        echo "</a></li>";
      }
    ?>
  </ul>
  <div data-role="popup" id="setlist-delete-popup" data-overlay-theme="a" data-theme="a" data-dismissible="false" class="ui-corner-all">
    <div data-role="header" class="ui-corner-top">
      <h1>Delete Setlist?</h1>
    </div>
    <div data-role="content" class="ui-corner-bottom ui-content">
      <p>Are you sure you want to delete this setlist? This action cannot be undone.</p>
      <a href="#" data-role="button" data-inline="true" data-rel="back">Cancel</a>
      <form id="setlist-delete-form" method='post' style='display: inline;' data-ajax='false'>
        <input type='hidden' name='_METHOD' value='DELETE' />
        <input type='submit' data-role="button" data-inline="true" data-rel="back" data-transition="flow" data-theme="b" value='Delete' />
      </form>
    </div>
  </div>

  <a href="<?= $base_url ?>/songs" data-role="button" data-theme="b" <?php if (count($songs) == 0) echo 'disabled' ?>>View All</a>

</div>

<?php include_once('../views/includes/footer.php'); ?>