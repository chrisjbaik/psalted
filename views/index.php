<?php include_once('../views/includes/header.php'); ?>
<div data-role="content">
	<center><img src='img/psalted_03.gif'></center>
  <form method="post" action="/">
    <input type="text" name="email" id="text-basic" placeholder="Email" value="<?php if (isset($email)) { echo $email; }?>">
    <input type="password" name="password" id="password" placeholder="Password" value="" autocomplete="off">
    <input type="submit" value="Log In" data-role="button" />
    <!--<a href="#" data-theme="b" data-role="button">Log In w/ Facebook</a>-->
  </form>
</div>
<?php include_once('../views/includes/footer.php'); ?>