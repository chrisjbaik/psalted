<?php
$app->get('/aboutus', function () use ($app) {
     $app->render('aboutus.php');
});

// <?php
//   $app = new \Slim\Slim();
//   $app->get('/aboutus' , function () use($app) {
//  
// });