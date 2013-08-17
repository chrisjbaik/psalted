<?php include_once('../views/includes/header_jqm.php'); ?>
<div data-role="content" id="page-setlist-view">
  <fieldset class="ui-grid-a">
    <div class="ui-block-a"><button id="btn-pdf-save" type="button" data-theme="c" <?php if (count($songs) == 0) echo 'disabled' ?>>Save PDF</button></div>
    <div class="ui-block-b"><button type="submit" data-theme="b">Add a song</button></div>
  </fieldset>
  <ul data-role="listview" data-divider-theme="b" data-inset="true">
    <?php
      foreach ($songs as $song) {
        echo "<li data-theme='c'>";
        echo "<a href='/songs/{$song->url}' data-transition='slide'>";
        echo $song->title;
        echo "</a></li>";
      }
    ?>
  </ul>
  <script>
    $( "#btn-pdf-save" ).click(function(event) {
      $.get('<?= $songs_url ?>', function(data) {
        console.log(data);
        if (data.error) {
          alert(data.error);
          return;
        }

        var sheet = new Songsheet();
        sheet.addSongs(data.songs).render().save('<?= $pdf_file ?>');
      }, 'json');
    });
  </script>
</div>

<?php include_once('../views/includes/footer_jqm.php'); ?>