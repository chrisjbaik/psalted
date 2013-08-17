<?php
  $app->get('/songs.json', $acl_middleware(), function () use ($app) {
    $res = $app->response();
    $songs = Model::factory('Song')->select_many('id','url','title')->find_array();
    $res->write(json_encode($songs));
  });

  $app->get('/songs/:url.json', $acl_middleware(), function ($url) use ($app) {
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

  $app->group('/songs', $acl_middleware(), function () use ($app) {
    $app->get('/', function () use ($app) {
      $songs = Model::factory('Song')->select_many('id','url','title')->find_many();
      $app->render('songs/list.php', array(
        'songs' => $songs
      ));
    });

    $app->get('/new', function () use ($app) {
      $app->render('songs/edit.php');
    });


    $app->post('/new', function () use ($app) {
      $req = $app->request();
      $song = Model::factory('Song')->create();
      $song->title = $req->params('title');
      $song->chords = $req->params('chords');
      $song->key = $req->params('key');
      $song->artist = $req->params('artist');
      $song->copyright = $req->params('copyright');
      $song->spotify_id = $req->params('spotify_id');
      if ($song->save()) {
        $app->flash('success', 'Song was successfully added!');
        $app->redirect('/songs/'.$song->url);
      } else {
        $app->flash('error', 'Song save failed.');
        $app->redirect('/songs/new');
      }
    });

    $app->get('/:id/edit', function ($id) use ($app) {
      $req = $app->request();

      $song = Model::factory('Song')->find_one($id);
      if ($song) {
        $app->render('songs/edit.php', array(
          'song' => $song
        ));
      } else {
        $app->flash('error', 'Song does not exist.');
        $app->redirect('/');
      }
    });

    $app->put('/:id', function ($id) use ($app) {
      $req = $app->request();

      $song = Model::factory('Song')->find_one($id);
      if ($song) {
        $song->title = $req->params('title');
        $song->chords = $req->params('chords');
        $song->key = $req->params('key');
        $song->copyright = $req->params('copyright');
        $song->artist = $req->params('artist');
        $song->spotify_id = $req->params('spotify_id');
        if ($song->save()) {
          $app->flash('success', 'Song was successfully edited!');
          $app->redirect('/songs/'.$song->url);
        } else {
          $app->flash('error', 'Song save failed.');
          $app->redirect('/songs/'.$song->url);
        }
      } else {
        $app->flash('error', 'Song does not exist.');
        $app->redirect('/songs/new');
      }
    });

    $app->delete('/:id', function ($id) use ($app) {
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

    $app->get('/:url', function ($url) use ($app) {
      $song = Model::factory('Song')->where('url', $url)->find_one();

      if ($song) {
        $app->render('songs/view.php', array(
          'song' => $song
        ));
      } else {
        $app->flash('error', 'Song was not found!');
        $app->redirect('/');
      }
    });

    $app->get('/:url/chords', function ($url) use ($app) {
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

    $app->get('/:url/lyrics', function ($url) use ($app) {
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
  });
?>