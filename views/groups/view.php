<?php include_once('../views/includes/header_jqm.php'); ?>
<div data-role="panel" id="right-panel" data-theme="c" data-position="right">
  <ul data-role="listview" data-theme="c">
    <li data-icon="gear"><a href="/groups/<?php echo $group->url; ?>/edit">Edit Group</a></li>
    <li data-icon="delete"><a href="#">Delete Group</a></li>
  </ul>
</div>
<div data-role="content">
  <a data-role="button" href="/groups/<?php echo $group->url; ?>/new" data-icon="plus" data-iconpos="left">New Setlist</a>
  <ul data-role="listview" data-divider-theme="b" data-inset="true">
    <li data-role="list-divider" role="heading">
      Setlists
    </li>
    <?php
      if (count($setlists) == 0) {
        echo "<li>You have not made any setlists yet.</li>";
      }
      foreach ($setlists as $setlist) {
        echo "<li data-theme='c'>";
        echo "<a href='/groups/{$group->url}/{$setlist->url}' data-transition='slide'>";
        echo date("M. j: ", $setlist->date) . $setlist->title;
        echo "</a>";
        echo "</li>";
      }
    ?>
  </ul>
  <ul data-role="listview" data-divider-theme="b" data-inset="true">
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
</div>
<?php include_once('../views/includes/footer_jqm.php'); ?>