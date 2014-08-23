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

    $app->delete('/:group_url', function ($group_url) use ($app) {
      $group = Model::factory('Group')->where('url', $group_url)->find_one();
      if ($group) {
        $group->delete();
        $app->flash('success', 'Group was successfully deleted!');
        $app->redirect('/');
      } else {
        $app->flash('error', 'Group '.htmlspecialchars($id).' does not exist.');
        $app->redirect('/');
      }
    });
    
    $app->get('/:group_url/edit', function ($group_url) use ($app) {
      $req = $app->request();

      $group = Model::factory('Group')->where('url', $group_url)->find_one();
      if ($group) {
        $members = $group->users()->find_many();
        $app->render('groups/edit.php', array(
          'group' => $group,
          'members' => $members
        ));
      } else {
        $app->flash('error', 'Group '.htmlspecialchars($group_url).' does not exist.');
        $app->redirect('/');
      }
    });

    $app->post('/:group_url/edit', function ($group_url) use ($app) {
      function errorHandler($app, $group_url) {
        $app->flash('error', 'Group save failed.');
        $app->redirect('/groups/' + $group_url + '/edit');
      }
      $req = $app->request();
      $group = Model::factory('Group')->where('url', $group_url)->find_one();
      if ($group) {
        $group->name = $req->params('name');
        if ($group->save()) {
          Model::factory('GroupUser')->where('group_id', $group->id)->delete_many();
          $members = $req->params('members');
          foreach ($members as $member) {
            $groups_user = Model::factory('GroupUser')->create();
            $groups_user->group_id = $group->id;
            $groups_user->user_id = $member;
            if (!($groups_user->save())) { return errorHandler($app, $group_url); }
          }
          $app->flash('success', 'Your changes have been saved!');
          $app->redirect('/groups/' . $group->url);
        } else { return errorHandler($app, $group_url); } // check
      } else {
        $app->flash('error', 'Group '.htmlspecialchars($group_url).' does not exist.');
        $app->redirect('/');
      }
    });

    $app->get('/:group_url/new', function ($group_url) use ($app) {
      $groups = $_SESSION['user']->groups()->find_many();
      $group = Model::factory('Group')->where('url', $group_url)->find_one();
      if ($group) {
        $users = $group->users()->find_many();
        $app->render('setlists/edit.php', array(
          'users' => $users,
          'groups' => $groups,
          'group' => $group
        ));
      } else {
        $app->flash('error', 'Group '.htmlspecialchars($group_url).' was not found!');
        $app->redirect('/');
      }
    });

    $app->post('/:group_url/new', function ($group_url) use ($app) {
      function errorHandler($app, $group_url) {
        $app->flash('error', 'Setlist save failed.');
        $app->redirect('/groups/'. $group_url . '/new');
      }
      $req = $app->request();
      $group = Model::factory('Group')->where('url', $group_url)->find_one();
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
              if (!($setlist_song->save())) { return errorHandler($app, $group_url); }
            }
          }
          $app->flash('success', 'Setlist was successfully added!');
          $app->redirect('/groups/' . $group->url . '/' . $setlist->url);
        } else { return errorHandler($app, $group_url); }
      } else {
        $app->flash('error', 'Group was not found!');
        $app->redirect('/');
      }
    });

    $app->get('/:group_url', function ($group_url) use ($app) {
      $group = Model::factory('Group')->where('url', $group_url)->find_one();

      if ($group) {
        $users = $group->users()->find_many();
        $setlists = $group->setlists()->order_by_desc('created_at')->find_many();
        $app->render('groups/view.php', array(
          'users' => $users,
          'group' => $group,
          'setlists' => $setlists,
          'right_panel' => true,
          'page_title' => $group->name
        ));
      } else {
        $app->flash('error', 'Group '.htmlspecialchars($group_url).' was not found!');
        $app->redirect('/');
      }
    });

    $app->get('/:group_url/:setlist_url/edit', function ($group_url, $setlist_url) use ($app) {
      $groups = $_SESSION['user']->groups()->find_many();
      $group = Model::factory('Group')->where('url', $group_url)->find_one();

      if ($group) {
        $setlist = $group->setlists()->where('url', $setlist_url)->find_one();
        if ($setlist) {
          $songs = ORM::for_table('song')
            ->select('song.*')
            ->select('setlist_song.*')
            ->join('setlist_song', array('setlist_song.song_id', '=', 'song.id'))
            ->where('setlist_song.setlist_id', $setlist->id)
            ->find_many();
          $users = $group->users()->find_many();
          $app->render('setlists/edit.php', array(
            'groups' => $groups,
            'group' => $group,
            'setlist' => $setlist,
            'songs' => $songs,
            'users' => $users
          ));
        } else {
          $app->flash('error', 'Setlist was not found!');
          $app->redirect('/'); 
        }
      } else {
        $app->flash('error', 'Group '.htmlspecialchars($group_url).' was not found!');
        $app->redirect('/');
      }
    });

    $app->post('/:group_url/:setlist_url/edit', function ($group_url, $setlist_url) use ($app) {
      function errorHandler($app, $group_url) {
        $app->flash('error', 'Setlist save failed.');
        $app->redirect('/groups/'. $group_url . '/new');
      }
      $req = $app->request();
      $group = Model::factory('Group')->where('url', $group_url)->find_one();
      if ($group) {
        $setlist = Model::factory('Setlist')->where('url', $setlist_url)->find_one();
        if ($setlist) {
          $setlist->title = $req->params('title');

          $user = $_SESSION['user'];
          // allow users to move setlist from group to group
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
          else {
            $setlist->user_id = $user->id;
            $setlist->group_id = 0;
          }
          
          $setlist->created_by = $_SESSION['user']->id;
          $setlist->updated_by = $_SESSION['user']->id;
          $setlist->date = strtotime($req->params('date'));
          if ($setlist->save()) {
            $songs = $req->params('songs');
            Model::factory('SetlistSong')->where('setlist_id', $setlist->id)->delete_many();
            if (!empty($songs)) {
              foreach ($songs as $index => $song) {
                $setlist_song = Model::factory('SetlistSong')->create();
                $setlist_song->setlist_id = $setlist->id;
                $setlist_song->song_id = $song['id'];
                $setlist_song->chosen_by = $song['chosen_by'];
                $setlist_song->priority = $index;
                if (!($setlist_song->save())) { return errorHandler($app, $group_url); }
              }
            }
            $app->flash('success', 'Setlist was successfully edited!');
            $app->redirect('/groups/' . $group->url . '/' . $setlist->url);
          } else { return errorHandler($app, $group_url); }
        } else { return errorHandler($app, $group_url); }
      } else {
        $app->flash('error', 'Group '.htmlspecialchars($group_url).' was not found!');
        $app->redirect('/');
      }
    });

    $app->delete('/:group_url/:setlist_url', function ($group_url, $setlist_url) use ($app) {
      $group = Model::factory('Group')->where('url', $group_url)->find_one();
      if ($group) {
        $setlist = Model::factory('Setlist')->where('url', $setlist_url)->find_one();
        if ($setlist) {
          $setlist->delete();
          $app->flash('success', 'Setlist was successfully deleted!');
          $app->redirect('/groups/'.$group_url);
        }
      } else {
        $app->flash('error', 'Group does not exist.');
        $app->redirect('/');
      }
    });

    $app->get('/:group_url/:setlist_url', function ($group_url, $setlist_url) use ($app) {
      $group = Model::factory('Group')->where('url', $group_url)->find_one();

      if ($group) {
        $setlist = $group->setlists()->where('url', $setlist_url)->find_one();
        if ($setlist) {
          $songs = $setlist->songs()->find_many();
          $pdf_file = $setlist->pdfName();
          $app->render('setlists/view.php', array(
            'setlist' => $setlist,
            'songs' => $songs,
            'group' => $group,
            'right_panel' => true,
            'page_title' => $setlist->title,
            'page_cache' => true,
            'pdf_file' => $pdf_file,
            'pdf_url' => "/groups/$group_url/$setlist_url/$pdf_file",
          ));
        } else {
          $app->flash('error', 'Setlist '.htmlspecialchars($setlist_url).' was not found!');
          $app->redirect('/'); 
        }
      } else {
        $app->flash('error', 'Group '.htmlspecialchars($group_url).' was not found!');
        $app->redirect('/');
      }
    });

    $app->get('/:group_url/:setlist_url/songs', function ($group_url, $setlist_url) use ($app) {
      $group = Model::factory('Group')->where('url', $group_url)->find_one();

      if (!$group) {
        $app->flash('error', 'Group '.htmlspecialchars($group_url).' was not found!');
        $app->redirect('/');
      }

      $setlist = $group->setlists()->where('url', $setlist_url)->find_one();
      if (!$setlist) {
        $app->flash('error', 'Setlist '.htmlspecialchars($setlist_url).' was not found!');
        $app->redirect('/'); 
      }

      $songs = $setlist->songs()->find_many();
      $app->render('setlists/songs.php', array('songs' => $songs));
    });

    $app->get('/:group_url/:setlist_url/:pdfname.pdf', function ($group_url, $setlist_url, $pdfname) use ($app) {
      $group = Model::factory('Group')->where('url', $group_url)->find_one();

      if (!$group) {
        $app->flash('error', 'Group '.htmlspecialchars($group_url).' was not found!');
        $app->redirect('/');
      }

      $setlist = $group->setlists()->where('url', $setlist_url)->find_one();
      if (!$setlist) {
        $app->flash('error', 'Setlist '.htmlspecialchars($setlist_url).' was not found!');
        $app->redirect('/'); 
      }

      $app->response->headers->set('Content-Type', 'application/pdf');
      $setlist->pdfOutput();
    });

    $app->get('/:group_url/:setlist_url/settings', function ($group_url, $setlist_url) use ($app) {
      $group = Model::factory('Group')->where('url', $group_url)->find_one();

      if (!$group) {
        $app->flash('error', 'Group '.htmlspecialchars($group_url).' was not found!');
        $app->redirect('/');
      }

      $setlist = $group->setlists()->where('url', $setlist_url)->find_one();
      if (!$setlist) {
        $app->flash('error', 'Setlist '.htmlspecialchars($setlist_url).' was not found!');
        $app->redirect('/'); 
      }

      $app->render('setlists/settings.php', array(
        'use_group' => ! $setlist->settings_id,
        'group_type' => 'group',
        'group_name' => $group->name,
        'settings' => $setlist->settings(),
        'group_settings' => $group->settings(),
      ));
    });

    $app->post('/:group_url/:setlist_url/settings', function ($group_url, $setlist_url) use ($app) {
      $group = Model::factory('Group')->where('url', $group_url)->find_one();

      if (!$group) {
        $app->flash('error', 'Group '.htmlspecialchars($group_url).' was not found!');
        $app->redirect('/');
      }

      $setlist = $group->setlists()->where('url', $setlist_url)->find_one();
      if (!$setlist) {
        $app->flash('error', 'Setlist '.htmlspecialchars($setlist_url).' was not found!');
        $app->redirect('/'); 
      }

      $error = true;

      if ($app->request->post('use_group')) {
        $group->settings($app->request->post('settings'));
        $setlist->settings_id = NULL;
        if ($group->save() and $setlist->save()) {
          $error = false;
          $app->flash('success', 'Group settings were successfully saved!');
        } else {
          $app->flash('error', 'Failed to save group settings!');
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
        $app->redirect("/groups/$group_url/$setlist_url/settings");
      } else {
        $app->redirect("/groups/$group_url/$setlist_url");
      }
    });

  });
?>