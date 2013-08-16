<?php
$app->group('/search', $acl_middleware(), function () use ($app) {
  $app->get('/', function () use ($app) {
    $req = $app->request();
    $query = $req->params('q');
    $songs = Model::factory('Song')->raw_query("SELECT * FROM `song_fts` WHERE song_fts MATCH :query", array('query' => $query))->find_many();
    $like_query = '%' . $query . '%';
    $users = Model::factory('User')->raw_query('SELECT id, first_name, last_name FROM `user` WHERE first_name LIKE :like_query OR last_name LIKE :like_query OR email LIKE :like_query', array('like_query' => $like_query))->find_many();
    $user = $_SESSION['user'];
    $setlists = $user->setlists()->raw_query('SELECT id, title FROM `setlist` WHERE title LIKE :like_query', array('like_query' => $like_query))->find_many();
    $groups = $user->groups()->raw_query('SELECT id, name FROM `group` WHERE name LIKE :like_query', array('like_query' => $like_query))->find_many();
    $app->render('search/list.php', array(
      'songs' => $songs,
      'users' => $users,
      'groups' => $groups,
      'setlists' => $setlists
    ));
  });

  $app->get('/songs/:query', function ($query) use ($app) {
    $res = $app->response();
    $songs = Model::factory('Song')->raw_query("SELECT * FROM `song_fts` WHERE song_fts MATCH :query", array('query' => $query))->find_array();
    $res->write(json_encode($songs));
  });

  $app->get('/users/:query', function ($query) use ($app) {
    $res = $app->response();
    $query = '%' . $query . '%';
    $users = Model::factory('User')->raw_query('SELECT id, first_name, last_name FROM `user` WHERE first_name LIKE :query OR last_name LIKE :query OR email LIKE :query', array('query' => $query))->find_array();
    $res->write(json_encode($users));
  });
});