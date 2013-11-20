<?php
	require_once __DIR__ . '/../vendor/autoload.php';
	require_once __DIR__ . '/../config/database.php';

	/*
	 * Database/Models Setup
	 */
	ORM::configure("sqlite:../db/{$db_name}");
	spl_autoload_register(function ($class_name) {
    $class_name = strtolower($class_name);
		include realpath(__DIR__ . "/../models/{$class_name}.php");
	});

	/*
	 * Views Setup
	 */

	class PsaltedView extends \Slim\View {
		public function render($template) {
			$this->data['session'] = $_SESSION;
			$this->data['base_url'] = dirname($_SERVER['SCRIPT_NAME']) === DIRECTORY_SEPARATOR ? '' : dirname($_SERVER['SCRIPT_NAME']);
			if (empty($this->data['page_id'])) {
				$page_id = preg_replace("/([-a-z]+).php/i", "$1", $template);
				$page_id = preg_replace("/\//", "-", $page_id);
				$this->data['page_id'] = $page_id;
			}
			return parent::render($template);
		}
	};


	/*
	 * App Configuration
	 * Settings can be pulled from config/settings.json.
	 * 'mode': is either 'dev' or 'prod' for development and production, respectively
	 */
	$app_settings = json_decode(file_get_contents(__DIR__ . "/../config/settings.json"));
	$app = new \Slim\Slim(array(
		'templates.path' => '../views',
		'view' => new PsaltedView(),
		'debug' => $app_settings->mode === 'dev'
		'mode' => $app_settings->mode
	));

	session_cache_limiter(false);
	session_start();

	$app->view()->setData('base_url', dirname($_SERVER['SCRIPT_NAME']) === DIRECTORY_SEPARATOR ? '' : dirname($_SERVER['SCRIPT_NAME']));
	if (!empty($_SESSION['user'])) {
		$app->view()->setData('user', $_SESSION['user']);
		$app->view()->setData('isAdmin', $_SESSION['user']->hasRole('admin'));
	} else {
		$app->view()->setData('isAdmin', false);
	}

	$app->error(function (\Exception $e) use ($app) {
		$app->redirect('/');
	});

	foreach (glob(__DIR__ ."/../routes/middleware/*.php") as $filename)
	{
	    include $filename;
	}

	/*
	 * Routes
	 */
	$app->get('/', function () use ($app) {
		if (!empty($_SESSION['user'])) {
			$app->redirect('/home');
		} else {
			$app->render('index.php');
		}
	});

	foreach (glob(__DIR__ ."/../routes/*.php") as $filename)
	{
	    include $filename;
	}

	$app->run();
?>
