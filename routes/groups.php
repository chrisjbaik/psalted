<?php
  $app->get('/groups', function () use ($app) {
    if (empty($_SESSION['user'])) {
      return $app->redirect('/');
    }

    $user = $_SESSION['user'];
    $groups = $user->groups()->find_many();
    foreach ($groups as $group) {
      echo $group->name;
    }
  });

  $app->group('/groups', function () use ($app) {
    $app->get('/new', function () use ($app) {
      echo 'New Group View goes here';
    });

    $app->post('/new', function () use ($app) {
      $group = Model::factory('Group')->create();
      $group->title = $req->params('name');
      if ($group->save()) {
        $app->flash('success', 'Group was successfully added!');
        $app->redirect('/groups/' . $group->url);
      } else {
        $app->flash('error', 'Song save failed.');
        $app->redirect('/new');
      }
    });

    $app->delete('/:id', function ($id) use ($app) {
      $req = $app->request();

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

    $app->get('/:url', function ($url) use ($app) {
      $group = Model::factory('Group')->where('url', $url)->find_one();

      if ($group) {
        $users = $group->users()->find_many();
        echo '<strong>Users:</strong><br />';
        foreach ($users as $user) {
          echo $user->first_name;
        }
        echo '<br /><br />';
        echo '<strong>Setlists:</strong><br />';
        $setlists = $group->setlists()->find_many();
        foreach ($setlists as $setlist) {
          echo $setlist->title;
        }
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
          echo $setlist->title;
          echo '<br /><br /><strong>Songs:</strong><br />';
          $songs = $setlist->songs()->find_many();
          foreach ($songs as $song) {
            echo $song->title;
          }
        } else {
          $app->flash('error', 'Setlist was not found!');
          $app->redirect('/'); 
        }
      } else {
        $app->flash('error', 'Group was not found!');
        $app->redirect('/');
      }
    });
  });
?>