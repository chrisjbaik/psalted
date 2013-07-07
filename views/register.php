<?php
  include_once('../views/includes/header.php');
?>
  <h2>Sign Up</h2>
  <form class="form-horizontal" method="post" action="/register">
    <?php
      if (!empty($provider) && !empty($uid)) {
        echo '<input type="hidden" name="provider" value="'.$provider.'" />';
        echo '<input type="hidden" name="uid" value="'.$uid.'" />';
      }
    ?>
    <div class="control-group">
      <label class="control-label" for="email">Email</label>
      <div class="controls">
        <?php
          if (!empty($user) && !empty($user->email)) {
            echo '<input type="text" id="email" placeholder="Email" name="email" value="' . $user->email . '" />';
          } else {
            echo '<input type="text" id="email" placeholder="Email" name="email" />';
          }
        ?>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="password">Password</label>
      <div class="controls">
        <input type="password" id="password" placeholder="Password" name="password">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="first_name">First Name</label>
      <div class="controls">
        <?php
          if (!empty($user) && !empty($user->first_name)) {
            echo '<input type="text" id="first_name" placeholder="First Name" name="first_name" value="' . $user->first_name . '">';
          } else {
            echo '<input type="text" id="first_name" placeholder="First Name" name="first_name" />';
          }
        ?>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="last_name">Last Name</label>
      <div class="controls">
        <?php
          if (!empty($user) && !empty($user->last_name)) {
            echo '<input type="text" id="last_name" placeholder="First Name" name="last_name" value="' . $user->last_name . '">';
          } else {
            echo '<input type="text" id="last_name" placeholder="Last Name" name="last_name" />';
          }
        ?>
      </div>
    </div>
    <div class="control-group">
      <div class="controls">
        <button type="submit" class="btn btn-primary">Register</button>
      </div>
    </div>
  </form>
<?php
  include_once('../views/includes/footer.php');