<?php
  $app->group('/tags', $acl_middleware(), function () use ($app) {
    $app->get('/:url', function ($url) use ($app) {
      $req = $app->request();

      $tag = Model::factory('Tag')->where('url', $url)->find_one();
      $songs = $tag->songs()->find_many();

      $app->render('tags/view.php', array(
        'songs' => $songs,
        'tag' => $tag,
        'users' => array($_SESSION['user'])
      ));
    });
  });
?>
