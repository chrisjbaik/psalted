<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Sawadicop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="css/libs/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }
    </style>
    <link href="css/libs/bootstrap-responsive.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,400,600,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet/less" type="text/css" href="css/style.less">

    <script src="js/libs/modernizr-2.5.3.min.js"></script>
    <script src="js/libs/less-1.3.0.min.js"></script>

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
          <a class="brand" href="#">Sawadicop</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="#">Home</a></li>
              <!--<li><a href="#about">About</a></li>
              <li><a href="#contact">Contact</a></li>-->
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span2">
          <div class="well sidebar-nav">
            <ul class="nav nav-list songs-list">
              <li class="nav-header">All Songs</li>
              <!--<li class="active"><a href="#">Link</a></li>-->
            </ul>
            <button id="add-new-song" class='btn btn-primary'>Add New Song</button>
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span10">
          <div class="row-fluid alerts">
          <?php
            if (isset($flash['success'])) {
              echo "<div class='alert alert-success'>".$flash['success']."</div>";
            }
          ?>
          </div>
          <div class="row-fluid" role="main"></div>
        </div><!--/span-->
      </div><!--/row-->

      <hr>

      <footer>
        <p>&copy; 2012</p>
      </footer>

    </div><!--/.fluid-container-->

    <script src="js/libs/jquery-1.7.1.min.js"></script>
    <script src="js/libs/bootstrap.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/index.js"></script>
  </body>
</html>
