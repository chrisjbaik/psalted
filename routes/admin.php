<?php
  use Guzzle\Http\Client;

  $app->group('/admin', $acl_middleware('admin'), function () use ($app) {
    $app->get('/', function () use ($app) {
      $app->render('admin/index.php', array(
        'page_title' => 'System Admin'
      ));
    });
    $app->group('/groups', function () use ($app) {
      $app->get('/', function () use ($app) {
        $groups = Model::factory('Group')->find_many();
        $app->render('admin/groups.php', array(
          'groups' => $groups
        ));
      });
      $app->delete('/:id', function ($id) use ($app) {
        $group = Model::factory('Group')->find_one($id);
        if ($group) {
          $group->delete();
          $app->flash('success', 'Group was successfully deleted!');
          $app->redirect('/admin/groups');
        } else {
          $app->flash('error', 'Group does not exist.');
          $app->redirect('/admin/groups');
        }
      });
    });
    $app->group('/users', function () use ($app) {
      $app->get('/', function () use ($app) {
        $users = Model::factory('User')->find_many();
        $app->render('admin/users.php', array(
          'users' => $users
        ));
      });
      $app->get('/:id/masquerade', function ($id) use ($app) {
        $user = Model::factory('User')->find_one($id);
        if ($user) {
          $_SESSION['user'] = $user;
          $app->redirect('/');
        } else {
          $app->flash('error', 'User does not exist.');
          $app->redirect('/admin/users');
        }
      });
      $app->delete('/:id', function ($id) use ($app) {
        $user = Model::factory('User')->find_one($id);
        if ($user) {
          $user->delete();
          $app->flash('success', 'User was successfully deleted!');
          $app->redirect('/admin/users');
        } else {
          $app->flash('error', 'User does not exist.');
          $app->redirect('/admin/users');
        }
      });
    });
    $app->group('/invites', function () use ($app) {
      $app->get('/', function () use ($app) {
        $invites = Model::factory('Invite')->where('admin_approved', 1)->where('redeemed', 0)->find_many();
        $app->render('admin/invites.php', array(
          'page_title' => 'Invites',
          'invites' => $invites
        ));
      });
      $app->post('/new', function () use ($app) {
        $req = $app->request;
        $invite = Model::factory('Invite')->create();
        $invite->email = $req->params('email');
        $user = Model::factory('User')->where('email', $invite->email)->find_one();
        if ($user) {
          $app->flash('error', 'There is already a user with that email address.');
          return $app->redirect('/admin/invites');
        }
        $invite->key = uniqid();
        $invite->admin_approved = true;
        if ($invite->save()) {
          $client = new Client();
          $inviter = $_SESSION['user'];
          /*$customer_request = $client->put('https://track.customer.io/api/v1/customers/'.$inviter->id, array(),
            array(
              'email' => $_SESSION['user']->email,
            )
          );
          $customer_request->setAuth($customer_io['site_id'], $customer_io['api_key']);
          $customer_response = $customer_request->send();
          if ($customer_response->isSuccessful()) {*/
          $customer_io = new CustomerIO();
          $send_invite = $customer_io->identify_and_send($inviter, array(
            'name' => 'invite_new_user',
            'data' => array(
              'invitee_email' => $invite->email,
              'invite_key' => $invite->key
            )
          ));
          if ($send_invite) {
            $app->flash('success', 'Invite created!');
          } else {
            $app->flash('error', 'Invite creation failed.');
          }
        } else {
          $app->flash('error', 'Invite creation failed.');
        }
        $app->redirect('/admin/invites');
      });
      $app->get('/:id/delete', function ($id) use ($app) {
        $invite = Model::factory('Invite')->find_one($id);
        if ($invite) {
          $invite->delete();
          $app->flash('success', 'Invite was successfully deleted!');
        } else {
          $app->flash('error', 'Invite does not exist.');
        }
        $app->redirect('/admin/invites');
      });
    });
    $app->group('/requests', function () use ($app) {
      $app->get('/', function () use ($app) {
        $requests = Model::factory('Invite')->where('admin_approved', 0)->where('redeemed', 0)->find_many();
        $app->render('admin/requests.php', array(
          'requests' => $requests
        ));
      });
      $app->get('/:id/approve', function ($id) use ($app) {
        $request = Model::factory('Invite')->find_one($id);
        if (!$request) {
          $app->flash('error', 'Request does not exist.');
          $app->redirect('/admin/invites');
        } else {
          $request->admin_approved = 1;
          if ($request->save()) {
            $app->flash('success', 'Request approved!');
          } else {
            $app->flash('error', 'Request approval failed.');
          }
          $app->redirect('/admin/requests');
        }
      });
    });
  });