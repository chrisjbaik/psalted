<?php
	require_once __DIR__ . '/../vendor/autoload.php';
	require_once __DIR__ . '/../config/database.php';

	/*
	 * Database/Models Setup
	 */
	ORM::configure("sqlite:../db/{$db_name}");
	spl_autoload_register(function ($class_name) {
		include __DIR__ . "/../models/{$class_name}.php";
	});

	/*
	 * Views Setup
	 */

	class SawadicopView extends \Slim\View {
		public function render($template) {
			$this->data['session'] = $_SESSION;
			$this->data['base_url'] = dirname($_SERVER['SCRIPT_NAME']) === DIRECTORY_SEPARATOR ? '' : dirname($_SERVER['SCRIPT_NAME']);
			return parent::render($template);
		}
	};


	/*
	 * App Configuration
	 */
	$app = new \Slim\Slim(array(
		'templates.path' => '../views',
		'view' => new SawadicopView()
	));

	session_cache_limiter(false);
	session_start();

	$app->view()->setData('base_url', dirname($_SERVER['SCRIPT_NAME']) === DIRECTORY_SEPARATOR ? '' : dirname($_SERVER['SCRIPT_NAME']));

	$app->error(function (\Exception $e) use ($app) {
		$app->flash('error', $e->getMessage());
		$app->render('index.php');
	});

	/*
	 * Routes
	 */
	$app->get('/', function () use ($app) {
		$app->render('index.php');
	});

	foreach (glob(__DIR__ ."/../routes/*.php") as $filename)
	{
	    include $filename;
	}

	$app->run();
?>