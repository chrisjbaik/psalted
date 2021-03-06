<?php include_once('../views/includes/header.php'); ?>
<div data-role="content">
  <form action='/search' type='GET'><input type="search" placeholder="Search song titles, lyrics, or setlists..." name='q'></form>
  <ul data-role="listview" data-divider-theme="a" data-inset="true">
    <li data-role="list-divider" role="heading">
      Personal Setlists
    </li>
    <li>
      <a href="/personal">View Personal Setlists</a>
    </li>
    <li data-role="list-divider" role="heading">
      My Groups
    </li>
    <li data-theme="c" data-icon='plus'>
      <a href="/groups/new">New Group</a>
    </li>
    <?php
      if (empty($groups)) {
      ?>
        <li>
          <div class="word-wrap">
            You don't have a group yet. Create a new group to organize your setlists and collaborate with others.
          </div>
        </li>
      <?php
      } else {
        foreach ($groups as $group) {
          echo "<li>";
          echo "<a href='/groups/{$group->url}' data-transition='slide'>";
          echo $group->name;
          echo "</a>";
          echo "</li>";
        }
      }
    ?>
  </ul><!--list view-->
</div>
<?php include_once('../views/includes/footer.php'); ?>