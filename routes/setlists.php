<?php
  $app->group('/setlists', $acl_middleware(), function () use ($app) {
    $app->get('/new', function () use ($app) {
      $req = $app->request();

      $groups = $_SESSION['user']->groups()->find_many();
      $songs = NULL;
      if ($req->params('songs')) {
        $songs_list = explode(',',$req->params('songs'));
        $songs = Model::factory('Song')->where_in('id', $songs_list)->find_many();
        $song_sort = function ($a, $b) use ($songs_list) {
          return array_search($a->id, $songs_list) - array_search($b->id, $songs_list);
        };
        usort($songs, $song_sort);
      }
      $app->render('setlists/edit.php', array(
        'songs' => $songs,
        'groups' => $groups,
        'users' => array($_SESSION['user'])
      ));
    });
    $app->post('/new', function () use ($app) {
      function errorHandler($app) {
        $app->flash('error', 'Setlist save failed.');
        $app->redirect('/setlists/new');
      }
      $req = $app->request();
      $group_option = $req->params('group');
      
      $setlist = Model::factory('Setlist')->create();
      if ($group_option != 'personal') {
        $group = Model::factory('Group')->where('id', $group_option)->find_one();
        $setlist->group_id = $group->id;
      } else {
        $setlist->user_id = $_SESSION['user']->id;
      }
      $setlist->title = $req->params('title');
      $setlist->created_by = $_SESSION['user']->id;
      $setlist->updated_by = $_SESSION['user']->id;
      $setlist->date = strtotime($req->params('date'));
      if ($setlist->save()) {
        $songs = $req->params('songs');
        if (!empty($songs)) {
          foreach ($songs as $index => $song) {
            $setlist_song = Model::factory('SetlistSong')->create();
            $setlist_song->setlist_id = $setlist->id;
            $setlist_song->song_id = $song['id'];
            $setlist_song->chosen_by = $song['chosen_by'];
            $setlist_song->priority = $index;
            if (!($setlist_song->save())) { return errorHandler($app, $url); }
          }
        }
        $app->flash('success', 'Setlist was successfully added!');
        if ($group_option == 'personal') {
          $app->redirect('/personal/'.$setlist->url);
        } else {
          $app->redirect('/groups/' . $group->url . '/' . $setlist->url);
        }
      } else { return errorHandler($app); }
    });
  });
?>
