<?php
	require_once __DIR__ . '/../vendor/autoload.php';
	require_once __DIR__ . '/../config.php';

	/*
	 * Database/Models Setup
	 */
	ORM::configure("sqlite:../db/{$db_name}");
	spl_autoload_register(function ($class_name) {
		include "models/{$class_name}.php";
	});

	/*
	 * App Configuration
	 */
	$app = new \Slim\Slim(array(
		'templates.path' => '../views'
	));

	/*
	 * Routes
	 */
	$app->get('/', function () use ($app) {
		$app->render('lyrics.php');
	});
	
	$app->run();
?>