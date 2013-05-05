<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo $song->title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet/less" type="text/css" href="/css/style.less">
    <script src="/js/libs/less-1.3.3.min.js"></script>
  </head>
  <body>
    <div id='to-print'><?php
      if ($type == 'chords') {
        echo $song->chords;
      } else {
        echo $song->lyrics;
      }
    ?></div>
    <script src="/js/libs/jquery-1.7.1.min.js"></script>
    <script src="/js/plugins.js"></script>
    <script src="/js/index.js"></script>
    <script>
      $(function () {
        var lyrics = convertLyrics(parseInt($('#original_key').val()), $('#to-print').html())
        $('#to-print').html(lyrics);
        window.print();
      });
    </script>
  </body>
</html>