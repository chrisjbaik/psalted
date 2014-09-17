<?php
  $app->get('/home', $acl_middleware(), function () use ($app) {
    $user = $_SESSION['user'];
    $groups = $user->groups()->find_many();
    $app->render('users/home.php', array(
      'groups' => $groups,
      'page_title' => 'Home',
      'page_id' => 'home'
    ));
  });

  $app->group('/settings', $acl_middleware(), function () use ($app) {
    $app->get('/', function () use ($app) {
      $hybridauths = Model::factory('Hybridauth')->where('user_id', $_SESSION['user']->id)->find_many();
      $app->render('users/settings.php', array(
        'hybridauths' => $hybridauths
      ));
    });
    $app->get('/password', function () use ($app) {
      $app->render('users/password.php', array());
    });
    $app->post('/password', function () use ($app) {
      $req = $app->request();

      $user = Model::factory('User')->where('id', $_SESSION['user']->id)->find_one();
      $user->password = password_hash($req->params('password'), PASSWORD_BCRYPT);

      if ($user->save()) {
        $_SESSION['user'] = $user;
        $app->flash('success', 'Password changed successfully.');
        $app->redirect('/settings');
      } else {
        $app->flashNow('error', 'Password change failed. Try again or contact admin.');
        $app->render('users/password.php', array());
      }
    });
  });

  $app->group('/personal', $acl_middleware(), function () use ($app) {
    $app->get('/', function () use ($app) {
      $user = $_SESSION['user'];
      $setlists = $user->setlists()->find_many();
      $app->render('users/setlists.php', array(
        'setlists' => $setlists,
        'page_title' => 'Personal Setlists'
      ));
    });
    $app->get('/new', function () use ($app) {
      $app->render('setlists/edit.php', array(
        'users' => array($_SESSION['user'])
      ));
    });
    $app->post('/new', function () use ($app) {
      function errorHandler($app) {
        $app->flash('error', 'Setlist save failed.');
        $app->redirect('/personal/new');
      }
      $req = $app->request();
      $user = $_SESSION['user'];
      $setlist = Model::factory('Setlist')->create();
      $setlist->title = $req->params('title');
      $setlist->user_id = $user->id;
      $setlist->created_by = $user->id;
      $setlist->updated_by = $user->id;
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
            if (!($setlist_song->save())) { return errorHandler($app); }
          }
        }
        $app->flash('success', 'Setlist was successfully added!');
        $app->redirect('/personal/' . $setlist->url);
      } else { return errorHandler($app); }
    });
    $app->get('/:url', function ($url) use ($app) {
      $setlist = $_SESSION['user']->setlists()->where('url', $url)->find_one();
      if ($setlist) {
        $songs = $setlist->songs()->find_many();
        $pdf_file = $setlist->pdfName();
        $app->render('setlists/view.php', array(
          'setlist' => $setlist,
          'songs' => $songs,
          'right_panel' => true,
          'page_title' => $setlist->title,
          'pdf_file' => $pdf_file,
          'pdf_url' => "/personal/{$setlist->url}/$pdf_file",
        ));
      } else {
        $app->flash('error', 'Setlist was not found!');
        $app->redirect('/'); 
      }
    });

    $app->get('/:url/songs', function ($url) use ($app) {
      $user = $_SESSION['user'];
      $groups = $_SESSION['user']->groups()->find_many();
      $setlist = $user->setlists()->where('url', $url)->find_one();
      if (!$setlist) {
        $app->flash('error', 'Setlist '.htmlspecialchars($setlist_url).' was not found!');
        $app->redirect('/'); 
      }

      $songs = $setlist->songs()->find_many();
      $app->render('setlists/songs.php', array('songs' => $songs));
    });

    $app->get('/:url/:pdfname.pdf', function ($url, $pdfname) use ($app) {
      $user = $_SESSION['user'];
      $groups = $_SESSION['user']->groups()->find_many();
      $setlist = $user->setlists()->where('url', $url)->find_one();
      if ($setlist) {
        $app->response->headers->set('Content-Type', 'application/pdf');
        $setlist->pdfOutput();
      } else {
        $app->flash('error', 'Setlist was not found!');
        $app->redirect('/'); 
      }
    });
    
    $app->get('/:url/edit', function ($url) use ($app) {
      $user = $_SESSION['user'];
      $groups = $_SESSION['user']->groups()->find_many();
      $setlist = $user->setlists()->where('url', $url)->find_one();
      if ($setlist) {
        $songs = $setlist->songs()->find_many();
        $users = array($user);
        $app->render('setlists/edit.php', array(
          'setlist' => $setlist,
          'songs' => $songs,
          'users' => $users,
          'groups' => $groups
        ));
      } else {
        $app->flash('error', 'Setlist was not found!');
        $app->redirect('/'); 
      }
    });

    $app->post('/:url/edit', function ($url) use ($app) {
      function errorHandler($app, $url) {
        $app->flash('error', 'Setlist save failed.');
        $app->redirect('/groups/'. $url . '/new');
      }
      $req = $app->request();
      $user = $_SESSION['user'];
      $setlist = Model::factory('Setlist')->where('url', $url)->find_one();
      if ($setlist) {
        $setlist->title = $req->params('title');
        $setlist->user_id = $user->id;
        
        // allow users to move setlist from personal to group
        $group_id = (int) $req->params('group');
        if ($group_id != 0) {
          //check if user belongs to group
          if ($group = Model::factory('Group')->where('id', $group_id)->find_one()) {
            if ($group->users()->where('id', $user->id)->find_one()) {
              $setlist->user_id = 0;
              $setlist->group_id = $group_id;
            }
          }
        }
        $setlist->created_by = $user->id;
        $setlist->updated_by = $user->id;
        $setlist->date = strtotime($req->params('date'));
        if ($setlist->save()) {
          $songs = $req->params('songs');
          Model::factory('SetlistSong')->where('setlist_id', $setlist->id)->delete_many();
          if (!empty($songs)) {
            foreach ($songs as $index => $song) {
              $setlist_song = Model::factory('SetlistSong')->create();
              $setlist_song->setlist_id = $setlist->id;
              $setlist_song->song_id = $song['id'];
              $setlist_song->key = $song['key'];
              $setlist_song->chosen_by = $song['chosen_by'];
              $setlist_song->priority = $index;
              if (!($setlist_song->save())) { return errorHandler($app, $url); }
            }
          }
          $app->flash('success', 'Setlist was successfully edited!');
          if ($setlist->group_id != 0) {
            $app->redirect('/groups/' . $setlist->group()->find_one()->url . '/' . $setlist->url);
          }
          else {
            $app->redirect('/personal/' . $setlist->url);
          }
        } else { return errorHandler($app, $url); }
      } else { return errorHandler($app, $url); }
    });

    $app->get('/:url/settings', function ($setlist_url) use ($app) {
      $user = $_SESSION['user'];
      $setlist = $user->setlists()->where('url', $setlist_url)->find_one();

      if (!$setlist) {
        $app->flash('error', 'Setlist '.htmlspecialchars($setlist_url).' was not found!');
        $app->redirect('/'); 
      }

      $app->render('setlists/settings.php', array(
        'group_type' => 'user',
        'use_group' => ! $setlist->settings_id,
        'group_name' => $user->first_name.'’s personal setlists',
        'settings' => $setlist->settings(),
        'group_settings' => $user->settings(),
      ));
    });

    $app->post('/:url/settings', function ($setlist_url) use ($app) {
      $user = $_SESSION['user'];
      $setlist = $user->setlists()->where('url', $setlist_url)->find_one();

      if (!$setlist) {
        $app->flash('error', 'Setlist '.htmlspecialchars($setlist_url).' was not found!');
        $app->redirect('/'); 
      }

      $error = true;

      if ($app->request->post('use_group')) {
        $user->settings($app->request->post('settings'));
        $setlist->settings_id = NULL;
        if ($user->save() and $setlist->save()) {
          $error = false;
          $app->flash('success', 'User settings were successfully saved!');
        } else {
          $app->flash('error', 'Failed to save user settings!');
        }
      } else {
        $setlist->settings($app->request->post('settings'));
        if ($setlist->save()) {
          $error = false;
          $app->flash('success', 'Setlist settings were successfully saved!');
        } else {
          $app->flash('error', 'Failed to save setlist settings!');
        }
      }

      if ($error) {
        $app->redirect("/personal/$setlist_url/settings");
      } else {
        $app->redirect("/personal/$setlist_url");
      }
    });

    $app->delete('/:setlist_url', function ($setlist_url) use ($app) {
      $setlist = Model::factory('Setlist')->where('url', $setlist_url)->find_one();
      if ($setlist) {
        $setlist->delete();
        $app->flash('success', 'Setlist was successfully deleted!');
        $app->redirect('/personal');
      } else {
        $app->flash('error', 'Setlist could not be found.');
        $app->redirect('/');
      }
    });
  });
