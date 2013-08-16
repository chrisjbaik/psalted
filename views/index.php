<?php include_once('../views/includes/header_jqm.php'); ?>

<form method="post" action="/">
  <input type="text" name="email" id="text-basic" placeholder="Email" value="<?php if (isset($email)) { echo $email; }?>">
  <input type="password" name="password" id="password" placeholder="Password" value="" autocomplete="off">
  <input type="submit" value="Log In" data-role="button" />
  <a href="#" data-theme="b" data-role="button">Log In w/ Facebook</a>
</form>

<?php include_once('../views/includes/footer_jqm.php'); ?>