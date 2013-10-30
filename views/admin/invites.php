<?php include_once('../views/includes/header.php'); ?>
<div data-role="content">
  <form action="/admin/invites/new" method="post" data-ajax="false">
    <input type="email" name="email" id="email" placeholder="Email to Invite" value="" />
    <input type="submit" value="Send Invite" data-theme="b" />
  </form>
  <ul data-role="listview" data-inset="true" data-split-icon='delete' data-split-theme="c">
    <li data-role='list-divider' role='heading'>Pending Invites</li>
    <?php
      if (count($invites) === 0) {
        echo "<li>There are currently no pending invites.</li>";
      }
      foreach ($invites as $i) {
        echo "<li><a href='#'>{$i->email}</a><a data-ajax='false' href='/admin/invites/{$i->id}/delete'>Delete Invite</a></li>";
      }
    ?>
  </ul>
</div>
<?php include_once('../views/includes/footer.php'); ?>