<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Psalted</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="//fonts.googleapis.com/css?family=PT+Sans:400,700" rel="stylesheet" type="text/css">
    <link href="//fonts.googleapis.com/css?family=PT+Serif:400,700" rel="stylesheet" type="text/css">

    <script src="<?= $base_url ?>/js/lib.js"></script>
    <script src="<?= $base_url ?>/js/main.js"></script>
    <link rel="stylesheet" href="<?= $base_url ?>/css/jquery.mobile-1.4.3.css">
    <link rel="stylesheet" type="text/css" href="<?= $base_url ?>/css/style.css" media="all">
  </head>

<body>
  <div data-role="page" id="<?= $page_id ?>" <?= (isset($page_cache) and $page_cache) ? 'data-dom-cache="true"' : '' ?>>
    <div data-role="panel" id="left-panel">
      <ul data-role="listview">
        <li data-icon="search"><form action='/search' type='GET'><input type="search" placeholder="Search..." name='q'></form></li>
        <li data-icon="home"><a href="/home">Home</a></li>
        <li data-icon="search"><a href="/songs">Browse Songs</a></li>
        <li data-icon="gear"><a href="/settings">Settings</a></li>
        <li><a href="/aboutus">About Us</a></li>
        <?php 
        if ($isAdmin) {
          echo '<li data-icon="gear"><a href="/admin">System Admin</a></li>';
        }
        ?>
        <li><a href="/logout" data-ajax='false'>Log Out</a></li>
      </ul>
    </div>
    <div data-role="header" data-theme="c">
      <?php
        if (!empty($_SESSION['user'])) {
          echo '<a href="#left-panel" class="ui-icon-nodisc" data-theme="a" data-icon="bars" data-iconshadow="false" data-iconpos="notext"> </a>';
          if (!empty($right_panel)) {
            echo '<a href="#right-panel" class="ui-icon-nodisc" data-theme="a" data-icon="gear" data-iconshadow="false" data-iconpos="notext"> </a>';
          }
        }
      ?>
      <h3><?php if (!empty($page_title)) { echo $page_title; } else { echo 'Psalted'; } ?></h3>
      <?php
        if (isset($flash['success']) || isset($flash['error']) || isset($flash['info'])) {
          echo '<div class="alert-bar">';
            if (isset($flash['success'])) {
              echo "<div class='alert alert-success ui-bar ui-bar-b'>".$flash['success']."</div>";
            }
            if (isset($flash['error'])) {
              echo "<div class='alert alert-error ui-bar'>".$flash['error']."</div>";
            }
            if (isset($flash['info'])) {
              echo "<div class='alert alert-info ui-bar ui-bar-c'>".$flash['info']."</div>";
            }
          echo '</div>';
        }
      ?>
    </div>