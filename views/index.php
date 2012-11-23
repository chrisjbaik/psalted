<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Lyrics</title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width">
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,400,600,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet/less" type="text/css" href="css/style.less">
	<script src="js/libs/modernizr-2.5.3.min.js"></script>
	<script src="js/libs/less-1.3.0.min.js"></script>
</head>
<body>
	<!--[if lt IE 7]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->
	<header>
		<h1>Lyrics Generator</h1>
	</header>
	<div role="main">
		<section id="input">
			<div>
				<label for="original_key">Original Key:</label>
				<select name="original_key" id="original_key">
					<option value="0">C
					<option value="1">C♯ / D♭
					<option value="2">D
					<option value="3">C♯ / E♭
					<option value="4">E
					<option value="5">F
					<option value="6">F♯ / G♭
					<option value="7">G
					<option value="8">G♯ / A♭
					<option value="9" selected>A
					<option value="10">A♯ / B♭
					<option value="11">B
				</select>
			</div>
			<textarea rows="5" cols="80">
How [A]great is our God, [E/G#]sing with me,
How [F#m7]great is our God, and [E/G#]all will see,
How [Dmaj7]great, how [E]great is our [A]God.
</textarea>
		<div>
			<button id="convertBtn">Convert</button>
		</div>
		<div id="transpose">
			<label for="transposed_key">Transpose to:</label>
			<select name="transposed_key" id="transposed_key">
				<option value="0">C
				<option value="1">C♯ / D♭
				<option value="2">D
				<option value="3">C♯ / E♭
				<option value="4">E
				<option value="5">F
				<option value="6">F♯ / G♭
				<option value="7">G
				<option value="8">G♯ / A♭
				<option value="9" selected>A
				<option value="10">A♯ / B♭
				<option value="11">B
			</select>
		</div>
		</section>
		<section id="output">
		</section>
	</div>
	<footer>
		&copy; 2012
	</footer>
	<script src="js/libs/jquery-1.7.1.min.js"></script>
	<!--
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.1.min.js"><\/script>')</script>
	-->
	<script src="js/plugins.js"></script>
	<script src="js/script.js"></script>
</body>
</html>