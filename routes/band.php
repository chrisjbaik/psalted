<?php
  $app->group('/band', $acl_middleware('band'), function () use ($app) {
    $app->get('/', function () use ($app) {
      $app->render('band/index.php', array(
        'page_title' => 'Band Songbook'
      ));
    });
    $app->group('/letters', function () use ($app) {
      $app->get('/', function () use ($app) {
        $app->render('band/letters.php', array(
          'page_title' => 'Band Songbook - By Letter'
        ));
      });
      $app->get('/:letter.pdf', function ($letter) use ($app) {
        $letter = strtoupper($letter);
        $songs = Model::factory('Song')->select_many('id','url','title','chords','lyrics', 'song_code')->where_like('song_code', $letter.'%')->order_by_asc('song_code')->find_many();

        $app->response->headers->set('Content-Type', 'application/pdf');
        
        $song_code_fn = function ($i, $col, $songData) use ($songs) {
          return $songs[$i-1]->song_code;
        };
        $settings = array(
          'copies'     => 1,
          'pagenumber' => 'off',
          'size'       => 'Letter',
          'songnumber' => 'off',
          'style'      => 'chords',
          'autonumber' => $song_code_fn
        );

        $setlist = Model::factory('Setlist')->create();
        $setlist->title = $letter;
        $setlist->pdfOutput($songs, $settings);
      });
    });
    $app->get('/songindexes', function () use ($app) {
      $app->render('band/songindexes.php', array(
        'page_title' => 'Band Songbook - Song Indexes'
      ));
    });
  });
?>