<?php include_once('../views/includes/header_jqm.php'); ?>
<div data-role="content">
  <ul data-role="listview" data-inset="true" data-split-icon='gear' data-split-theme='c'>
    <li data-role='list-divider' role='heading'>Groups</li>
    <?php
      if (count($groups) == 0) {
        echo "<li>There are currently no groups.</li>";
      }
      foreach ($groups as $group) {
        echo "<li data-theme='c'>";
        echo "<a href='/groups/{$group->url}'>{$group->name}</a>";
        echo "<a href='#admin-groups-menu-{$group->id}' data-rel='popup' data-position-to='origin' data-transition='pop'>Modify Group</a>";
        echo "<div data-role='popup' id='admin-groups-menu-{$group->id}' data-theme='d'>";
        echo "<ul data-role='listview' data-divider-theme='d'>";
        echo "<li><a href='/groups/{$group->url}'>View</a></li>";
        echo "<li data-icon='gear'><a href='/groups/{$group->url}/edit'>Edit Group</a></li>";
        echo "<li data-icon='delete'><a class='admin-groups-delete-link' href='#admin-groups-delete-popup' data-rel='popup' data-position-to='window' data-id='{$group->id}'>Delete</a></li>";
        echo "</ul>";
        echo "</div>";
        echo "</li>";
      }
    ?>
  </ul>

  <div data-role="popup" id="admin-groups-delete-popup" data-overlay-theme="a" data-theme="c" data-dismissible="false" class="ui-corner-all">
    <div data-role="header" data-theme="a" class="ui-corner-top">
      <h1>Delete Group?</h1>
    </div>
    <div data-role="content" data-theme="d" class="ui-corner-bottom ui-content">
      <p>Are you sure you want to delete this group? This action cannot be undone.</p>
      <a href="#" data-role="button" data-inline="true" data-rel="back" data-theme="c">Cancel</a>
      <form id="admin-groups-delete-form" method='post' style='display: inline;' data-ajax='false'>
        <input type='hidden' name='_METHOD' value='DELETE' />
        <input type='submit' data-role="button" data-inline="true" data-rel="back" data-transition="flow" data-theme="b" value='Delete' />
      </form>
    </div>
  </div>

  <script>
    $(document).on('click', '.admin-groups-delete-link', function (e) {
      $('#admin-groups-delete-form').attr('action', '/admin/groups/' + $(this).attr('data-id'));
    })
  </script>
</div>
<?php include_once('../views/includes/footer_jqm.php'); ?>