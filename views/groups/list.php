<?php include_once('../views/includes/header.php'); ?>
<div data-role="content">
  <a data-role="button" href="/groups/new" data-icon="plus" data-iconpos="left">New Group</a>
  <ul data-role="listview" data-divider-theme="b" data-inset="true">
    <li data-role="list-divider" role="heading">
      My Groups
    </li>
    <?php
      if (count($groups) == 0) {
        echo "<li>You are not part of any groups yet.</li>";
      }
      foreach ($groups as $group) {
        echo "<li data-theme='c'>";
        echo "<a href='/groups/{$group->url}' data-transition='slide'>";
        echo $group->name;
        echo "</a>";
        echo "</li>";
      }
    ?>
  </ul><!--list view-->
</div>
<?php include_once('../views/includes/footer.php'); ?>