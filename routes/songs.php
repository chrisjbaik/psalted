<?php
  /*$app->get('/songs.json', $acl_middleware(), function () use ($app) {
    $res = $app->response();
    $songs = Model::factory('Song')->select_many('id','url','title')->find_array();
    $res->write(json_encode($songs));
  });

  $app->get('/songs/:song_url.json', $acl_middleware(), function ($song_url) use ($app) {
    $res = $app->response();

    $song = Model::factory('Song')->where('url', $song_url)->find_one();
    if ($song_url) {
      $res['Content-Type'] = 'application/json';
      $res->write(
        json_encode($song_url)
      );
    } else {
      $res->write('{}');
    }
  })->name("/songs/views");*/

  $app->group('/songs', $acl_middleware(), function () use ($app) {
    $app->get('/', function () use ($app) {
      $user = $_SESSION['user'];
      $songs = Model::factory('Song')->select_many('id','url','title','artist','certified')->order_by_asc('title')->find_many();
      $groups = $user->groups()->select('id')->find_many();
      if (!empty($groups) && is_array($groups)) {
        $reduce_groups = function($result, $group) {
          return $result . "\"".$group->id."\"".",";
        };
        $groups = array_reduce($groups, $reduce_groups, "(");
        $groups = substr($groups, 0, strlen($groups)-1);
        $groups = $groups.")";
      } else {
        $groups = "()";
      }
      $setlists = ORM::for_table('setlist')
        ->raw_query("SELECT S.id, S.title, G.name AS group_name FROM setlist S LEFT JOIN `group` G ON S.group_id = G.id WHERE S.group_id IN ". $groups . " OR S.user_id = ". $user->id ." ORDER BY updated_at DESC")->find_many();
      $app->render('songs/list.php', array(
        'songs' => $songs,
        'page_title' => 'Browse Songs',
        'page_cache' => true,
        'setlists' => $setlists
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

    $app->get('/:song_url/edit', function ($song_url) use ($app) {
      $req = $app->request();

      $song = Model::factory('Song')->where('url', $song_url)->find_one();
      if ($song) {
        $tags = $song->tags()->find_many();
        $app->render('songs/edit.php', array(
          'song' => $song,
          'tags' => $tags,
          'right_panel' => true,
        ));
      } else {
        $app->flash('error', 'Song does not exist.');
        $app->redirect('/');
      }
    });

    $app->get('/:song_url/certify', function ($song_url) use ($app) {
      $req = $app->request();

      $song = Model::factory('Song')->where('url', $song_url)->find_one();
      if ($song) {
        $song->certified = true;
        if ($song->save()) {
          $app->flash('success', 'Great Success! Song is certified!');
          $app->redirect('/songs/'.$song->url);
        } else {
          $app->flash('error','Cannot certify lah!');
          $app->redirect('/');
        }
      }
    });

    $app->get('/:song_url/decertify', function ($song_url) use ($app) {
      $req = $app->request();

      $song = Model::factory('Song')->where('url', $song_url)->find_one();
      if ($song) {
        $song->certified = false;
        if ($song->save()) {
          $app->flash('success', 'Great Success! Song is decertified!');
          $app->redirect('/songs/'.$song->url);
        } else {
          $app->flash('error','Cannot decertify lah!');
          $app->redirect('/');
        }
      }
    });

    $app->put('/:song_url', function ($song_url) use ($app) {
      $req = $app->request();

      $song = Model::factory('Song')->where('url', $song_url)->find_one();
      if ($song) {
        $song->title = $req->params('title');
        $song->chords = $req->params('chords');
        $song->key = $req->params('key');
        $song->copyright = $req->params('copyright');
        $song->artist = $req->params('artist');
        $song->spotify_id = $req->params('spotify_id');
        if ($song->save()) {

          Model::factory('SongTag')->where('song_id', $song->id)->delete_many();
          $tags = $req->params('tags');
          if (!empty($tags)) {
            foreach ($tags as $tag) {
              $song_tag = Model::factory('SongTag')->create();
              $song_tag->song_id = $song->id;
              $song_tag->tag_id = $tag;
              $song_tag->added_by = $_SESSION['user']->id;
              if (!($song_tag->save())) {
                $app->flash('error', 'Tag save failed.');
                $app->redirect('/songs/'.$song->url);
              }
            }
          }

          $newTags = $req->params('new_tags');
          if (!empty($newTags)) {
            foreach ($newTags as $tagName) {
              $new_tag = Model::factory('Tag')->create();
              $new_tag->name = $tagName;
              if (!($new_tag->save())) {
                $app->flash('error', 'Tag save failed.');
                $app->redirect('/songs/'.$song->url);
              }
            }
            foreach ($newTags as $tagName) {
              $tag = Model::factory('Tag')->where('name', $tagName)->find_one();
              $song_tag = Model::factory('SongTag')->create();
              $song_tag->song_id = $song->id;
              $song_tag->tag_id = $tag->id;
              $song_tag->added_by = $_SESSION['user']->id;
              if (!($song_tag->save())) {
                $app->flash('error', 'Tag save failed.');
                $app->redirect('/songs/'.$song->url);
              }              
            }
          }

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

    $app->delete('/:song_url', function ($song_url) use ($app) {
      $req = $app->request();

      $song = Model::factory('Song')->where('url', $song_url)->find_one();
      if ($song) {
        $song->delete();
        $app->flash('success', 'Song was successfully deleted!');
        $app->redirect('/songs');
      } else {
        $app->flash('error', 'Song does not exist.');
        $app->redirect('/songs');
      }
    });

    $app->get('/:song_url', function ($song_url) use ($app) {
      $song = Model::factory('Song')->where('url', $song_url)->find_one();

      if ($song) {
        $app->render('songs/view.php', array(
          'song' => $song,
          'right_panel' => true,
          'page_title' => $song->title . ' (' . $song->keyToString() . ')'
        ));
      } else {
        $app->flash('error', 'Song was not found!');
        $app->redirect('/');
      }
    });

    $app->get('/:song_url/chords', function ($song_url) use ($app) {
      $res = $app->response();

      $song = Model::factory('Song')->where('url', $song_url)->find_one();

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

    $app->get('/:song_url/lyrics', function ($song_url) use ($app) {
      $res = $app->response();

      $song = Model::factory('Song')->where('url', $song_url)->find_one();

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

    $app->post('/addtosetlist', function () use ($app) {
      $req = $app->request();

      $songs = $req->params('songs');
      $setlist = $req->params('setlist');
      $setlist = Model::factory('Setlist')->find_one($setlist);
      if (empty($setlist)) {
        $app->flash('error', 'Could not find setlist.');
        $app->redirect('/songs');
      }

      if ($setlist->group_id) {
        $group = $setlist->group()->find_one();
        if (empty($group)) {
          $app->flash('error', 'Could not find group associated to setlist.');
          $app->redirect('/songs');
        }
      }

      $songs = explode(",", $songs);

      $max_priority = ORM::for_table('setlist_song')
        ->raw_query('SELECT MAX(priority) AS max_priority FROM setlist_song WHERE setlist_id = ' . $setlist->id)->find_one()->max_priority;
      $success = true;
      foreach ($songs as $index => $song) {
        $setlist_song = Model::factory('SetlistSong')->where('setlist_id', $setlist->id)
          ->where('song_id', $song)->find_one();
        if ($setlist_song) {
          continue;
        }
        $setlist_song = Model::factory('SetlistSong')->create();
        $setlist_song->song_id = $song;
        $setlist_song->setlist_id = $setlist->id;
        $setlist_song->chosen_by = $_SESSION['user']->id;
        $setlist_song->priority = $max_priority + $index;
        $success = $setlist_song->save();
        if (!$success) { break; }
      }
      if ($success) {
        $app->flash('success', 'Successfully added songs to setlist.');
        if (!empty($group)) {
          $app->redirect('/groups/'. $group->url . '/' . $setlist->url);
        } else {
          $app->redirect('/personal/'.$setlist->url);
        }
      } else {
        $app->flash('error', 'Error saving songs to setlist!');
        $app->redirect('/songs');
      }
    });
  });
?>