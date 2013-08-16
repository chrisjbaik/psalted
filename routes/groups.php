<?php
  $app->group('/groups', $acl_middleware(), function () use ($app) {
    $app->get('/new', function () use ($app) {
      $app->render('groups/new.php');
    });

    $app->post('/new', function () use ($app) {
      function errorHandler() {
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
          if (!($groups_user->save())) { return errorHandler(); }
        }
        $app->flash('success', 'Group was successfully added!');
        $app->redirect('/groups/' . $group->url);
      } else { return errorHandler(); }
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

    $app->get('/:url', function ($url) use ($app) {
      $group = Model::factory('Group')->where('url', $url)->find_one();

      if ($group) {
        $users = $group->users()->find_many();
        echo '<strong>Users:</strong><br />';
        foreach ($users as $user) {
          echo $user->first_name;
        }
        $setlists = $group->setlists()->find_many();
        $app->render('groups/view.php', array(
          'group' => $group,
          'setlists' => $setlists
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
          $app->render('setlists/view.php', array(
            'setlist' => $setlist,
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
  });
?>