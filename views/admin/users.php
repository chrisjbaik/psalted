<?php include_once('../views/includes/header.php'); ?>
<div data-role="content">
  <ul data-role="listview" data-inset="true" data-split-icon='gear' data-split-theme='c'>
    <li data-role='list-divider' role='heading'>Users</li>
    <?php
      if (count($users) == 0) {
        echo "<li>There are currently no users.</li>";
      }
      foreach ($users as $user) {
        echo "<li data-theme='d'>";
        echo "<a href='#'>{$user->first_name} {$user->last_name} ({$user->email})</a>";
        echo "<a href='#admin-users-menu-{$user->id}' data-rel='popup' data-position-to='origin' data-transition='pop'>User Menu</a>";
        echo "<div data-role='popup' id='admin-users-menu-{$user->id}' data-theme='d'>";
        echo "<ul data-role='listview' data-divider-theme='d'>";
        echo "<li><a href='/admin/users/{$user->id}/masquerade' data-ajax='false'>Masquerade</a></li>";
        echo "<li data-icon='delete'><a class='admin-users-delete-link' href='#admin-users-delete-popup' data-rel='popup' data-position-to='window' data-id='{$user->id}'>Delete</a></li>";
        echo "</ul>";
        echo "</div>";
        echo "</li>";
      }
    ?>
  </ul>

  <div data-role="popup" id="admin-users-delete-popup" data-overlay-theme="a" data-theme="c" data-dismissible="false" class="ui-corner-all">
    <div data-role="header" data-theme="a" class="ui-corner-top">
      <h1>Delete User?</h1>
    </div>
    <div data-role="content" data-theme="d" class="ui-corner-bottom ui-content">
      <p>Are you sure you want to delete this user? This action cannot be undone.</p>
      <a href="#" data-role="button" data-inline="true" data-rel="back" data-theme="c">Cancel</a>
      <form id="admin-users-delete-form" method='post' style='display: inline;' data-ajax='false'>
        <input type='hidden' name='_METHOD' value='DELETE' />
        <input type='submit' data-role="button" data-inline="true" data-rel="back" data-transition="flow" data-theme="b" value='Delete' />
      </form>
    </div>
  </div>
</div>
<?php include_once('../views/includes/footer.php'); ?>