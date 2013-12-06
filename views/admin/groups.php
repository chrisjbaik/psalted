<?php include_once('../views/includes/header.php'); ?>
<div data-role="content">
  <ul data-role="listview" data-inset="true" data-split-icon='gear'>
    <li data-role='list-divider' role='heading'>Groups</li>
    <?php
      if (count($groups) == 0) {
        echo "<li>There are currently no groups.</li>";
      }
      foreach ($groups as $group) {
        echo "<li>";
        echo "<a href='/groups/{$group->url}'>{$group->name}</a>";
        echo "<a href='#admin-groups-menu-{$group->id}' data-rel='popup' data-position-to='origin' data-transition='pop'>Group Menu</a>";
        echo "<div data-role='popup' data-rel='popup' id='admin-groups-menu-{$group->id}'>";
        echo "<ul data-role='listview'>";
        echo "<li><a href='/groups/{$group->url}'>View</a></li>";
        echo "<li data-icon='gear'><a href='/groups/{$group->url}/edit'>Edit Group</a></li>";
        echo "<li data-icon='delete' data-theme='b'><a class='admin-groups-delete-link' href='#admin-groups-delete-popup' data-rel='popup' data-position-to='window' data-id='{$group->id}'>Delete</a></li>";
        echo "</ul>";
        echo "</div>";
        echo "</li>";
      }
    ?>
  </ul>

  <div data-role="popup" id="admin-groups-delete-popup" data-overlay-theme="a" data-theme="a" data-dismissible="false" class="ui-corner-all">
    <div data-role="header" class="ui-corner-top">
      <h1>Delete Group?</h1>
    </div>
    <div data-role="content" class="ui-corner-bottom ui-content">
      <p>Are you sure you want to delete this group? This action cannot be undone.</p>
      <a href="#" data-role="button" data-inline="true" data-rel="back">Cancel</a>
      <form id="admin-groups-delete-form" method='post' style='display: inline;' data-ajax='false'>
        <input type='hidden' name='_METHOD' value='DELETE' />
        <input type='submit' data-role="button" data-inline="true" data-rel="back" data-transition="flow" data-theme="b" value='Delete' />
      </form>
    </div>
  </div>
</div>
<?php include_once('../views/includes/footer.php'); ?>