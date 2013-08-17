<?php include_once('../views/includes/header_jqm.php'); ?>
<div data-role="panel" id="right-panel" data-theme="c" data-position="right">
  <ul data-role="listview" data-theme="c">
    <li data-icon="gear"><a href="/groups/<?php echo $group->url; ?>/<?php echo $setlist->url; ?>/edit">Edit Setlist</a></li>
    <li data-icon="delete">
      <a data-rel='popup' data-position-to='window' href="#setlist-delete-popup" class='setlists-delete-link' id="delete-group" data-setlist-id="<?php echo $setlist->id; ?>" data-group-id="<?php echo $group->id; ?>">Delete Setlist</a>
    </li>
  </ul>
</div>
<div data-role="content">
  <fieldset class="ui-grid-a">
    <div class="ui-block-a"><button type="submit" data-theme="c">Print PDF</button></div>
    <div class="ui-block-b"><button type="submit" data-theme="b">Add a song</button></div>
  </fieldset>
  <ul data-role="listview" data-divider-theme="b" data-inset="true">
    <?php
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
      $('#setlist-delete-form').attr('action', '/groups/' + $(this).attr('data-group-id') + '/' + $(this).attr('data-setlist-id'));
    });
  </script>
</div>

<?php include_once('../views/includes/footer_jqm.php'); ?>