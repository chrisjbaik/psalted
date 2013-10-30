<?php include_once('../views/includes/header.php'); ?>
<div data-role="panel" id="right-panel" data-theme="c" data-position="right">
  <ul data-role="listview" data-theme="c">
    <li data-icon="gear">
      <?php
        if (!empty($group)) {
          $edit_url = "/groups/{$group->url}/{$setlist->url}/edit";
        } else {
          $edit_url = "/personal/{$setlist->url}/edit";
        }
        echo "<a href='{$edit_url}'>Edit Setlist</a>";
      ?>
    </li>
    <li data-icon="delete">
      <a data-rel='popup' data-position-to='window' href="#setlist-delete-popup" class='setlists-delete-link' id="delete-group" data-setlist-url="<?php echo $setlist->url; ?>" <?php if (!empty($group->url)) { echo "data-group-url='{$group->url}'"; } ?>>
        Delete Setlist
      </a>
    </li>
  </ul>
</div>
<div data-role="content" id="page-setlist-view">
  <button id="btn-pdf-save" type="button" data-theme="b" <?php if (count($songs) == 0) echo 'disabled' ?>>Save PDF</button>
  <ul data-role="listview" data-divider-theme="a" data-inset="true">
    <li data-role="list-divider" role="heading">Songs</li>
    <?php
      if (count($songs) === 0) {
        echo "<li>There are currently no songs in this setlist.</li>";
      }
      foreach ($songs as $song) {
        echo "<li data-theme='c'>";
        echo "<a href='/songs/{$song->url}' data-transition='slide'>";
        echo $song->title;
        echo "</a></li>";
      }
    ?>
  </ul>
  <div data-role="popup" id="setlist-delete-popup" data-overlay-theme="a" data-theme="c" data-dismissible="false" class="ui-corner-all">
    <div data-role="header" data-theme="a" class="ui-corner-top">
      <h1>Delete Setlist?</h1>
    </div>
    <div data-role="content" data-theme="d" class="ui-corner-bottom ui-content">
      <p>Are you sure you want to delete this setlist? This action cannot be undone.</p>
      <a href="#" data-role="button" data-inline="true" data-rel="back" data-theme="c">Cancel</a>
      <form id="setlist-delete-form" method='post' style='display: inline;' data-ajax='false'>
        <input type='hidden' name='_METHOD' value='DELETE' />
        <input type='submit' data-role="button" data-inline="true" data-rel="back" data-transition="flow" data-theme="b" value='Delete' />
      </form>
    </div>
  </div>
  <script>
    $(document).on('click', '.setlists-delete-link', function (e) {
      if ($(this).attr('data-group-url')) {
        $('#setlist-delete-form').attr('action', '/groups/' + $(this).attr('data-group-url') + '/' + $(this).attr('data-setlist-url'));
      } else {
        $('#setlist-delete-form').attr('action', '/personal/' + $(this).attr('data-setlist-url'));
      }
    });
    $( "#btn-pdf-save" ).click(function(event) {
      $.get('<?= $songs_url ?>', function(data) {
        if (data.error) {
          alert(data.error);
          return;
        }
        var sheet = new Songsheet();
        sheet.addSongs(data.songs).render().save('<?= $pdf_file ?>');
      }, 'json');
    });
  </script>
</div>

<?php include_once('../views/includes/footer.php'); ?>