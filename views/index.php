<?php include_once('../views/includes/header.php'); ?>
<div data-role="content">
	<div class="home-logo"><img src="img/psalted_03.gif" width="441" height="161"></div>
	<div class="home-login-form">
		<form method="post" action="/">
			<input type="email" autofocus name="email" id="text-basic" placeholder="Email" value="<?php if (isset($email)) { echo $email; }?>">
			<input type="password" name="password" id="password" placeholder="Password" value="" autocomplete="off">
			<input type="submit" value="Log In" data-role="button" data-theme="b">
			<!--<a href="#" data-theme="b" data-role="button">Log In w/ Facebook</a>-->
		</form>
	</div>
</div>
<?php include_once('../views/includes/footer.php'); ?>