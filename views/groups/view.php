<?php include_once('../views/includes/header.php'); ?>
<div data-role="panel" id="right-panel" data-theme="c" data-position="right">
  <ul data-role="listview" data-theme="c">
    <li data-icon="gear"><a href="/groups/<?php echo $group->url; ?>/edit">Edit Group</a></li>
    <li data-icon="delete">
      <a data-rel='popup' data-position-to='window' href="#group-delete-popup" class='groups-delete-link' id="delete-group" data-id="<?php echo $group->id; ?>">Delete Group</a>
    </li>
  </ul>
</div>
<div data-role="content">
  <ul data-role="listview" data-divider-theme="a" data-inset="true">
    <li data-role="list-divider" role="heading">
      Setlists
    </li>
    <li data-theme="e" data-icon="plus"><a href="/groups/<?php echo $group->url; ?>/new">New Setlist</a></li>
    <?php
      if (count($setlists) == 0) {
        echo "<li>You have not made any setlists yet.</li>";
      }
      foreach ($setlists as $setlist) {
        echo "<li data-theme='c'>";
        echo "<a href='/groups/{$group->url}/{$setlist->url}' data-transition='slide'>";
        echo date("M j: ", $setlist->date) . $setlist->title;
        echo "</a>";
        echo "</li>";
      }
    ?>
  </ul>
  <ul data-role="listview" data-divider-theme="a" data-inset="true">
    <li data-role="list-divider" role="heading">
      Members
    </li>
    <?php
      if (count($users) == 0) {
        echo "<li>There are no users here.</li>";
      }
      foreach ($users as $user) {
        echo "<li>{$user->first_name} {$user->last_name}</li>";
      }
    ?>
  </ul>
  <div data-role="popup" id="group-delete-popup" data-overlay-theme="a" data-theme="c" data-dismissible="false" class="ui-corner-all">
    <div data-role="header" data-theme="a" class="ui-corner-top">
      <h1>Delete Group?</h1>
    </div>
    <div data-role="content" data-theme="d" class="ui-corner-bottom ui-content">
      <p>Are you sure you want to delete this group? This action cannot be undone.</p>
      <a href="#" data-role="button" data-inline="true" data-rel="back" data-theme="c">Cancel</a>
      <form id="group-delete-form" method='post' style='display: inline;' data-ajax='false'>
        <input type='hidden' name='_METHOD' value='DELETE' />
        <input type='submit' data-role="button" data-inline="true" data-rel="back" data-transition="flow" data-theme="b" value='Delete' />
      </form>
    </div>
  </div>
</div>
<?php include_once('../views/includes/footer.php'); ?>