<?php include_once('../views/includes/header_jqm.php'); ?>
<div data-role="content">
  <ul data-role="listview" data-divider-theme="b" data-inset="true">
    <li data-theme='c'>
      <a href='/admin/groups' data-transition='slide'>Groups</a>
    </li>
    <li data-theme='c'>
      <a href='/admin/users' data-transition='slide'>Users</a>
    </li>
    <li data-theme='c'>
      <a href='/admin/requests' data-transition='slide'>Approve Requests</a>
    </li>
    <li data-theme='c'>
      <a href='/admin/invites' data-transition='slide'>Invite New Users</a>
    </li>
  </ul>
</div>
<?php include_once('../views/includes/footer_jqm.php'); ?>