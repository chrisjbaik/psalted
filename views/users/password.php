<?php include_once('../views/includes/header.php'); ?>
<div data-role="content">
  <form data-ajax="false" method="post" action="/settings/password">
    <input type="password" id="password" placeholder="Password" name="password">
    <input type="password" id="password-confirm" placeholder="Confirm Password" name="password-confirm">
    <input id="submit-form" type="submit" value="Save Password" data-valid-label="Save Password" data-theme="b">
  </form>
</div>
<?php
  include_once('../views/includes/footer.php');
