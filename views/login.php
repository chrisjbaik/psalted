<?php
  include_once('../views/includes/header.php');
?>
  <h2>Login</h2>
  <form class="form-horizontal" method="post">
    <div class="control-group">
      <label class="control-label" for="email">Email</label>
      <div class="controls">
        <?php
          if (!empty($email)) {
            echo '<input type="text" id="email" placeholder="Email" name="email" value="'. $email .'">';
          } else {
            echo '<input type="text" id="email" placeholder="Email" name="email">';
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
      <div class="controls">
        <button type="submit" class="btn btn-primary">Login</button>
      </div>
    </div>
  </form>
  <a href="/login?provider=facebook">Login with Facebook</a>
<?php
  include_once('../views/includes/footer.php');