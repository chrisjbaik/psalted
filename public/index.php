<?php
	require_once __DIR__ . '/../vendor/autoload.php';
	require_once __DIR__ . '/../config.php';

	/*
	 * Database/Models Setup
	 */
	ORM::configure("sqlite:../db/{$db_name}");
	spl_autoload_register(function ($class_name) {
		include __DIR__ . "/../models/{$class_name}.php";
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
		$app->render('index.php');
	});
	
	$app->get('/new', function () use ($app) {
		$app->render('new.php');
	});

	function print_array($aArray) {
	// Print a nicely formatted array representation:
	  echo '<pre>';
	  print_r($aArray);
	  echo '</pre>';
	}

	function generateSlug($title) {
		$url = URLify::filter($title);
		$found = Model::factory('Song')->where('url', $url)->find_one();

		while ($found) {
			$url = preg_replace_callback('/[-]?([0-9]+)?$/', function ($matches) {
				if (isset($matches[1])) {
					return '-' . ($matches[1] + 1);
				} else if (empty($matches[0])) {
					return '-1';
				} else {
					return;
				}
			}, $url, 1);

			$found = Model::factory('Song')->where('url', $url)->find_one();
		}

		return $url;
	}

	function removeChords($text) {
		return preg_replace('/\[[^\]]*\]/', '', $text);
	}

	$app->post('/new', function () use ($app) {
		$req = $app->request();

		$song = Model::factory('Song')->create();
		$song->title = $req->post('title');
		$song->url = generateSlug($song->title);
		$song->chords = $req->post('chords');
		$song->lyrics = removeChords($song->chords);
		print_array($song);
		$song->save();
	});

	$app->get('/song/:url.json', function ($url) use ($app) {
		$res = $app->response();

		$song = Model::factory('Song')->where('url', $url)->find_one();
		if ($song) {
			$res->write(
				json_encode(array(
					'id' => $song->id,
					'title' => $song->title,
					'chords' => $song->chords,
				));
			);
		} else {
			$res->write('foo');
		}
		$res->finalize();
	});

	$app->run();
?>