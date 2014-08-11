<?php include_once('../views/includes/header.php'); ?>
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
    <input type="password" id="password-confirm" placeholder="Confirm Password" name="password-confirm">
    <input type="text" id="first_name" placeholder="First Name" name="first_name" value="<?php if (!empty($user->first_name)) { echo $user->first_name; } ?>">
    <input type="text" id="last_name" placeholder="Last Name" name="last_name" value="<?php if (!empty($user->last_name)) { echo $user->last_name; } ?>">
    <input id="submit-form" type="submit" value="Register" data-valid-label="Register" data-theme="b" disabled="disabled" >
  </form>
  
<!--   <script>
   $('#password, #password-confirm').on('change', function (e) {
      var password = $('#password').val();
      var password_confirm = $('#password-confirm').val();
      if (password == password_confirm) {
        $('#submit-form').val('Register').button('refresh');
        $('#submit-form').button('enable','refresh');
      }

      else {
        $('#submit-form').val("Why u so like dat one? Passwords don't match lah").button('refresh');
        $('#submit-form').button('disable','refresh');
      }
   })  
  </script> -->

</div>
<?php
  include_once('../views/includes/footer.php');
