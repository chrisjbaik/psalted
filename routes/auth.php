<?php
  $app->post('/', function () use ($app) {
    $req = $app->request();
    
    $email = $req->params('email');
    $user = Model::factory('User')->where('email', $email)->find_one();
    if ($user && password_verify($req->params('password'), $user->password)) {
      $_SESSION['user'] = $user;
      $app->flash('success', 'Welcome, ' . $user->first_name . '!');
      $app->redirect('/');
    } else {
      $app->flashNow('error', 'No user was found with that email and password. Please try again.');
      $app->render('index.php', array(
        'email' => $email
      ));
    }
  });

  $app->get('/register/:key', function ($key) use ($app) {
    $invite = Model::factory('invite')
          ->where('key', $key)
          ->where('admin_approved', 1)
          ->where('redeemed', 0)->find_one();
    if ($invite) {
      $app->render('register.php', array(
        'invite_id' => $invite->id
      ));
    } else {
      $app->flash('error', 'Your invite key is not valid.');
      $app->redirect('/');
    }
  });

  $app->post('/register', function () use ($app) {
    $req = $app->request();

    $user = Model::factory('User')->create();
    $user->first_name = $req->params('first_name');
    $user->last_name = $req->params('last_name');
    $user->email = $req->params('email');
    $user->password = password_hash($req->params('password'), PASSWORD_BCRYPT);

    $uid = $req->params('uid');
    $provider = $req->params('provider');

    $invite_id = $req->params('invite_id');
    if ($user->save()) {
      if (!empty($uid) && !empty($provider)) {
        $ha = Model::factory('Hybridauth')->create();
        $ha->uid = $uid;
        $ha->provider = $provider;
        $ha->user_id = $user->id;

        if (!$ha->save()) {
          $app->flashNow('error', 'Registration using '.$provider.' failed. Please try again later.');
          return $app->redirect('register.php', array(
            'user' => $user,
            'uid' => $uid,
            'provider' => $provider
          ));
        }
      }
      if (!empty($invite_id)) {
        $invite = Model::factory('Invite')->find_one($invite_id);
        $invite->redeemed_by = $user->id;
        $invite->redeemed = 1;
        $invite->save();
      }
      $_SESSION['user'] = $user;
      $app->flash('success', 'Thanks for signing up!');
      $app->redirect('/');
    } else {
      $app->flashNow('error', 'Registration failed. Please check all fields and try again.');
      $app->render('register.php', array(
        'user' => $user,
        'uid' => $uid,
        'provider' => $provider
      ));
    }
  });

  $app->get('/auth', function () use ($app) {
    $app->render('hybridauth.php');
  });

  $app->get('/login', function () use ($app) {
    if (!empty($_SESSION['user'])) {
      return $app->redirect('/home');
    }
    $req = $app->request();
    $provider = $req->params('provider');

    if (!empty($provider)) {
      // Social Login attempted
      try {
        $hybridauth = new Hybrid_Auth(__DIR__ . '/../config/hybridauth.php');
        $adapter = $hybridauth->authenticate($provider);
        $user_profile = $adapter->getUserProfile();
        $user_ha = Model::factory('Hybridauth')->where('uid', $user_profile->identifier)->find_one();
        if ($user_ha) {
          $user = $user_ha->user()->find_one();
          if ($user) {
            $_SESSION['user'] = $user;
            $app->flash('success', 'Welcome, ' . $user->first_name . '!');
            $app->redirect('/home');
          } else {
            $app->flashNow('error', 'Social login error: '. $e->getMessage());
            $app->render('index.php');
          }
        } else {
          // Register page
          $user = Model::factory('User')->create();
          $user->first_name = $user_profile->firstName;
          $user->last_name = $user_profile->lastName;
          $user->email = $user_profile->email;
          $app->flashNow('info', 'Register for Sawadicop with ' . $provider . '!');
          $app->render('register.php', array(
            'user' => $user,
            'uid' => $user_profile->identifier,
            'provider' => $provider
          ));
        }
      } catch (Exception $e) {
        $app->flashNow('error', 'Social login error: '. $e->getMessage());
        $app->render('index.php');
      }
    } else {
      $app->render('index.php');
    }
  });

  $app->get('/logout', function () use ($app) {
    $_SESSION = array();

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    $app->redirect('/');
  });

  $app->get('/unlink', $acl_middleware(), function () use ($app) {
    $req = $app->request();
    $hybridauth = Model::factory('Hybridauth')->where('user_id', $_SESSION['user']->id)->where('provider', $req->params('provider'))->find_one();
    if (!empty($hybridauth)) {
      $hybridauth->delete();
    }
    $app->redirect('/settings');
  });
?>