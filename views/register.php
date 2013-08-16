<?php include_once('../views/includes/header_jqm.php'); ?>
<div data-role="content">
  <form data-ajax="false" method="post" action="/register">
    <?php
      if (!empty($provider) && !empty($uid)) {
        echo '<input type="hidden" name="provider" value="'.$provider.'" />';
        echo '<input type="hidden" name="uid" value="'.$uid.'" />';
      }
      if (!empty($invite_id)) {
        echo '<input type="hidden" name="invite_id" value="'.$invite_id.'" />';
      }
    ?>
    <input type="text" id="email" placeholder="Email" name="email" value="<?php if (!empty($user->email)) { echo $user->email; } ?>" />
    <input type="password" id="password" placeholder="Password" name="password">
    <input type="text" id="first_name" placeholder="First Name" name="first_name" value="<?php if (!empty($user->first_name)) { echo $user->first_name; } ?>">
    <input type="text" id="last_name" placeholder="Last Name" name="last_name" value="<?php if (!empty($user->last_name)) { echo $user->last_name; } ?>">
    <input type="submit" value="Register" data-theme="b" />
  </form>
  <a href="/login?provider=facebook">Register with Facebook</a>
</div>
<?php
  include_once('../views/includes/footer_jqm.php');