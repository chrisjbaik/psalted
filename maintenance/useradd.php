<?php

  if (count($argv) != 3) {
      print "Usage: useradd.php <email> <password>\n";
      exit(1);
  }

  require_once __DIR__ . '/../vendor/autoload.php';
  require_once __DIR__ . '/../config/database.php';

  /*
   * Database/Models/Libs Setup
   */
  ORM::configure("sqlite:../db/{$db_name}");

  function autoload_models($class_name) {
    $class_name = strtolower($class_name);
    $file = realpath(__DIR__ . "/../models/{$class_name}.php");
    if (file_exists($file)) {
      require_once($file);
    }
  }
  function autoload_libs($class_name) {
    $class_name = strtolower($class_name);
    $file = realpath(__DIR__ . "/../lib/{$class_name}.php");
    if (file_exists($file)) {
      require_once($file);
    }
  }
  spl_autoload_register('autoload_models');
  spl_autoload_register('autoload_libs');

  $user = Model::factory('User')->create();
  $user->first_name = 'Test';
  $user->last_name = 'User';
  $user->email = $argv[1];
  $user->password = password_hash($argv[2], PASSWORD_BCRYPT);
  $user->roles = serialize(array('member','admin'));

  if ($user->save()) {
      print "Successfully added new user.\n";
  } else {
      print "Error adding new user.\n";
  }
?>
