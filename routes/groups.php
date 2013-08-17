<?php
  $app->group('/groups', $acl_middleware(), function () use ($app) {
    $app->get('/new', function () use ($app) {
      $app->render('groups/edit.php');
    });

    $app->post('/new', function () use ($app) {
      function errorHandler($app) {
        $app->flash('error', 'Group save failed.');
        $app->redirect('/groups/new');
      }
      $req = $app->request();
      $group = Model::factory('Group')->create();
      $group->name = $req->params('name');
      if ($group->save()) {
        $members = $req->params('members');
        foreach ($members as $member) {
          $groups_user = Model::factory('GroupUser')->create();
          $groups_user->group_id = $group->id;
          $groups_user->user_id = $member;
          if (!($groups_user->save())) { return errorHandler($app); }
        }
        $app->flash('success', 'Group was successfully added!');
        $app->redirect('/groups/' . $group->url);
      } else { return errorHandler($app); }
    });

    $app->delete('/:id', function ($id) use ($app) {
      $group = Model::factory('Group')->find_one($id);
      if ($group) {
        $group->delete();
        $app->flash('success', 'Group was successfully deleted!');
        $app->redirect('/');
      } else {
        $app->flash('error', 'Group does not exist.');
        $app->redirect('/');
      }
    });
    
    $app->get('/:url/edit', function ($url) use ($app) {
      $req = $app->request();

      $group = Model::factory('Group')->where('url', $url)->find_one();
      $members = $group->users()->find_many();
      if ($group) {
        $app->render('groups/edit.php', array(
          'group' => $group,
          'members' => $members
        ));
      } else {
        $app->flash('error', 'Group does not exist.');
        $app->redirect('/');
      }
    });

    $app->post('/:url/edit', function ($url) use ($app) {
      function errorHandler($app, $url) {
        $app->flash('error', 'Group save failed.');
        $app->redirect('/groups/' + $url + '/edit');
      }
      $req = $app->request();
      $group = Model::factory('Group')->where('url', $url)->find_one();
      if ($group) {
        $group->name = $req->params('name');
        if ($group->save()) {
          Model::factory('GroupUser')->where('group_id', $group->id)->delete_many();
          $members = $req->params('members');
          foreach ($members as $member) {
            $groups_user = Model::factory('GroupUser')->create();
            $groups_user->group_id = $group->id;
            $groups_user->user_id = $member;
            if (!($groups_user->save())) { return errorHandler($app, $url); }
          }
          $app->flash('success', 'Your changes have been saved!');
          $app->redirect('/groups/' . $group->url);
        } else { return errorHandler($app, $url); }
      } else {
        $app->flash('error', 'Group does not exist.');
        $app->redirect('/');
      }
    });

    $app->get('/:url/new', function ($url) use ($app) {
      $group = Model::factory('Group')->where('url', $url)->find_one();
      if ($group) {
        $users = $group->users()->find_many();
        $app->render('setlists/edit.php', array(
          'users' => $users
        ));
      } else {
        $app->flash('error', 'Group was not found!');
        $app->redirect('/');
      }
    });

    $app->post('/:url/new', function ($url) use ($app) {
      function errorHandler($app, $url) {
        $app->flash('error', 'Setlist save failed.');
        $app->redirect('/groups/'. $url . '/new');
      }
      $req = $app->request();
      $group = Model::factory('Group')->where('url', $url)->find_one();
      if ($group) {
        $setlist = Model::factory('Setlist')->create();
        $setlist->title = $req->params('title');
        $setlist->group_id = $group->id;
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
          $app->redirect('/groups/' . $group->url . '/' . $setlist->url);
        } else { return errorHandler($app, $url); }
      } else {
        $app->flash('error', 'Group was not found!');
        $app->redirect('/');
      }
    });

    $app->get('/:url', function ($url) use ($app) {
      $group = Model::factory('Group')->where('url', $url)->find_one();

      if ($group) {
        $users = $group->users()->find_many();
        $setlists = $group->setlists()->find_many();
        $app->render('groups/view.php', array(
          'users' => $users,
          'group' => $group,
          'setlists' => $setlists,
          'right_panel' => true
        ));
      } else {
        $app->flash('error', 'Group was not found!');
        $app->redirect('/');
      }
    });

    $app->get('/:url/:setlist_url', function ($url, $setlist_url) use ($app) {
      $group = Model::factory('Group')->where('url', $url)->find_one();

      if ($group) {
        $setlist = $group->setlists()->find_one();
        if ($setlist) {
          $songs = $setlist->songs()->find_many();
          $pdf_file = preg_replace('/^-+|-+$/', "", preg_replace('/-+/', "-", preg_replace('/[_|\s]+/', "-", strtolower($setlist->title))));
          $app->render('setlists/view.php', array(
            'setlist' => $setlist,
            'pdf_file' => $pdf_file,
            'songs_url' => "/groups/$url/$setlist_url/songs",
            'songs' => $songs
          ));
        } else {
          $app->flash('error', 'Setlist was not found!');
          $app->redirect('/'); 
        }
      } else {
        $app->flash('error', 'Group was not found!');
        $app->redirect('/');
      }
    });

    $app->get('/:url/:setlist_url/songs', function ($url, $setlist_url) use ($app) {
      $result = array('error'=>'unknown error');
      $group = Model::factory('Group')->where('url', $url)->find_one();

      if ($group) {
        $setlist = $group->setlists()->find_one();
        if ($setlist) {
          $songs = $setlist->songs()->find_many();
          $result['error'] = '';
          $result['songs'] = array();
          foreach ($songs as $song) {
            $s = array(
              'title' => $song->title,
              'lyrics' => $song->chords,
            );
            $result['songs'][] = $s;
          }
        } else {
          $result['error'] = 'setlist not found';
        }
      } else {
        $result['error'] = 'group not found';
      }

      $app->response->setBody(json_encode($result));
    });
  });
?>