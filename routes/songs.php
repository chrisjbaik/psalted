<?php
  $app->get('/songs.json', function () use ($app) {
    $res = $app->response();
    $songs = Model::factory('Song')->select_many('id','url','title')->find_array();
    $res->write(json_encode($songs));
  });

  $app->get('/songs/:url.json', function ($url) use ($app) {
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

  $app->group('/songs', function () use ($app) {
    $app->get('/new', function () use ($app) {
      $app->render('song.php', array(
        'page_title' => 'New Song'
      ));
    });


    $app->post('/new', function () use ($app) {
      $req = $app->request();
      $song = Model::factory('Song')->create();
      $song->title = $req->params('title');
      $song->chords = $req->params('chords');
      $song->key = $req->params('original_key');
      $song->artist = $req->params('artist');
      $song->copyright = $req->params('copyright');
      $song->spotify_id = $req->params('spotify_id');
      if ($song->save()) {
        $app->flash('success', 'Song was successfully added!');
        $app->redirect('/songs/'.$song->url);
      } else {
        $app->flash('error', 'Song save failed.');
        $app->redirect('/new');
      }
    });

    $app->put('/:id', function ($id) use ($app) {
      $req = $app->request();

      $song = Model::factory('Song')->find_one($id);
      if ($song) {
        $song->title = $req->params('title');
        $song->chords = $req->params('chords');
        $song->key = $req->params('original_key');
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
        $app->redirect('/new');
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
        $app->render('song.php', array(
          'page_title' => 'Edit Song', 
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