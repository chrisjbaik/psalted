<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo $song->title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet/less" type="text/css" href="<?= $base_url ?>/css/style.less">
    <script src="<?= $base_url ?>/js/libs/less-1.3.3.min.js"></script>
  </head>
  <body>
    <div id='to-print'><?php
      if ($type == 'chords') {
        echo $song->chords;
      } else {
        echo $song->lyrics;
      }
    ?></div>
    <script src="<?= $base_url ?>/js/libs/jquery-1.7.1.min.js"></script>
    <script src="<?= $base_url ?>/js/plugins.js"></script>
    <script src="<?= $base_url ?>/js/index.js"></script>
    <script>
      $(function () {
        var lyrics = convertLyrics(parseInt($('#original_key').val()), $('#to-print').html())
        $('#to-print').html(lyrics);
        window.checkWindowState = function () {           
          if(document.readyState == "complete") {
            window.close(); 
          } else {           
            setTimeout("checkWindowState()", 10)
          }
        }
        window.print();
        window.checkWindowState();
      });
    </script>
  </body>
</html>