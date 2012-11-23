<?php
	require 'vendor/autoload.php';

	$app = new \Slim\Slim();

	$app->get('/', function () {
	    echo "Hello, Chris";
	});

	$app->get('/hello/:name', function ($name) {
	    echo "Hello, $name";
	});
	
	$app->run();
?>