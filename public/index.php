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
	
	$app->get('/register', function () use ($app) {
		$app->render('register.php');
	});

	$app->post('/register', function () use ($app) {
		$req = $app->request();

		$user = Model::factory('User')->create();
		$user->first_name = $req->post('first_name');
		$user->last_name = $req->post('last_name');
		$user->email = $req->post('email');
		$user->password = password_hash($req->post('password'), PASSWORD_BCRYPT);
		if ($user->save()) {
			$app->flash('success', 'Thanks for signing up!');
			$app->redirect('/');
		} else {
			$app->flashNow('error', 'Registration failed. Please check all fields and try again.');
			$app->render('/register.php', array(
				'user' => $user
			));
		}
	});

	$app->get('/login', function () use ($app) {
		$app->render('login.php');
	});

	$app->post('/login', function () use ($app) {
		$req = $app->request();

		$email = $req->post('email');
		$user = Model::factory('User')->where('email', $email)->find_one();
		if ($user && password_verify($req->post('password'), $user->password)) {
			$_SESSION['user'] = $user;
			$app->flash('success', 'Welcome, ' . $user->first_name . '!');
			$app->redirect('/');
		} else {
			$app->flashNow('error', 'No user was found with that email and password. Please try again.');
			$app->render('login.php', array(
				'email' => $email,
				'password' => $password
			));
		}
	});

	$app->get('/new', function () use ($app) {
		$app->render('song.php', array(
			'page_title' => 'New Song'
		));
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
		$song->key = $req->post('original_key');
		$song->artist = $req->post('artist');
		$song->copyright = $req->post('copyright');
		if ($song->save()) {
			$app->flash('success', 'Song was successfully added!');
			$app->redirect('/song/'.$song->url);
		} else {
			$app->flash('error', 'Song save failed.');
			$app->redirect('/new');
		}
	});

	$app->put('/song/:id', function ($id) use ($app) {
		$req = $app->request();

		$song = Model::factory('Song')->find_one($id);
		if ($song) {
			$song->title = $req->params('title');
			if (empty($song->url)) {
				$song->url = generateSlug($song->title);
			}
			$song->chords = $req->params('chords');
			$song->lyrics = removeChords($song->chords);
			$song->key = $req->params('original_key');
			$song->copyright = $req->params('copyright');
			$song->artist = $req->params('artist');
			if ($song->save()) {
				$app->flash('success', 'Song was successfully edited!');
				$app->redirect('/song/'.$song->url);
			} else {
				$app->flash('error', 'Song save failed.');
				$app->redirect('/song/'.$song->url);
			}
		} else {
			$app->flash('error', 'Song does not exist.');
			$app->redirect('/new');
		}
	});

	$app->delete('/song/:id', function ($id) use ($app) {
		$req = $app->request();

		$song = Model::factory('Song')->find_one($id);
		if ($song) {
			$song->delete();
			$app->flash('success', 'Song was successfully deleted!');
			$app->redirect('/');
		} else {
			$app->flash('error', 'Song does not exist.');
			$app->redirect('/');
		}
	});

	$app->get('/songs.json', function () use ($app) {
		$res = $app->response();
		$songs = Model::factory('Song')->select_many('id','url','title')->find_array();
		$res->write(json_encode($songs));
	});

	$app->get('/song/:url.json', function ($url) use ($app) {
		$res = $app->response();

		$song = Model::factory('Song')->where('url', $url)->find_one();
		if ($song) {
			$res['Content-Type'] = 'application/json';
			$res->write(
				json_encode($song)
			);
		} else {
			$res->write('{}');
		}
	});

	$app->get('/song/:url', function ($url) use ($app) {
		$res = $app->response();

		$song = Model::factory('Song')->where('url', $url)->find_one();

		if ($song) {
			$app->render('song.php', array(
				'page_title' => 'Edit Song', 
				'song' => $song
			));
		} else {
			$app->flash('error', 'Song was not found!');
			$res->redirect('/');
		}
	});

	$app->get('/song/:url/chords', function ($url) use ($app) {
		$res = $app->response();

		$song = Model::factory('Song')->where('url', $url)->find_one();

		if ($song) {
			$app->render('print.php', array(
				'song' => $song,
				'type' => 'chords'
			));
		} else {
			$app->flash('error', 'Song was not found!');
			$res->redirect('/');
		}
	});

	$app->get('/song/:url/lyrics', function ($url) use ($app) {
		$res = $app->response();

		$song = Model::factory('Song')->where('url', $url)->find_one();

		if ($song) {
			$app->render('print.php', array(
				'song' => $song,
				'type' => 'lyrics'
			));
		} else {
			$app->flash('error', 'Song was not found!');
			$res->redirect('/');
		}
	});


	$app->run();
?>