<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Sawadicop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="<?= $base_url ?>/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $base_url ?>/css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,400,600,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet/less" type="text/css" href="<?= $base_url ?>/css/style.less">

    <script src="<?= $base_url ?>/js/libs/modernizr-2.5.3.min.js"></script>
    <script src="<?= $base_url ?>/js/libs/less-1.3.3.min.js"></script>

  </head>

  <body>
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="/">Sawadicop</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="#">Home</a></li>
              <!--<li><a href="#about">About</a></li>
              <li><a href="#contact">Contact</a></li>-->
            </ul>
            <?php
              if (!empty($session['user'])) {
                echo '<span class="pull-right" id="navbar-welcome">Welcome, '. $session['user']->first_name .'!</span>';
              } else {
                echo '<div class="pull-right">
                  <a href="/register" class="btn btn-primary">Sign Up</a>
                  <a href="/login" class="btn">Login</a>
                </div>';
              }
            ?>
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span2">
          <a id="add-new-song" class='btn btn-primary btn-block' href='<?= $base_url ?>/new'>Add New Song</a>
          <div class="sidebar-nav">
            <ul class="nav nav-list songs-list">
              <li class="nav-header">All Songs</li>
            </ul>
          </div>
        </div>
        <div class="span10">
          <div class="row-fluid alerts">
          <?php
            if (isset($flash['success'])) {
              echo "<div class='alert alert-success'>".$flash['success']."</div>";
            }
            if (isset($flash['error'])) {
              echo "<div class='alert alert-error'>".$flash['error']."</div>";
            }
            if (isset($flash['info'])) {
              echo "<div class='alert alert-info'>".$flash['info']."</div>";
            }
          ?>
          </div>
          <!-- MAIN LAYOUT CONTENT PLACED HERE -->
          <div class="row-fluid" role="main">