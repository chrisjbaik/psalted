<?php include_once('../views/includes/header_jqm.php'); ?>
<div data-role="content">
  <form id="setlists-new" method="post" data-ajax='false'>
    <label for="setlist-title" class="ui-hidden-accessible">Setlist Name</label>
    <input type="text" name="title" id="setlist-title" placeholder="Setlist Name" value="">
    <label for="setlist-date" class="ui-hidden-accessible">Setlist Date</label>
    <input type="date" name="date" id="setlist-date" value="<?php if (!empty($setlist->date)) { echo date('Y-m-d', $setlist->date); } else { echo date('Y-m-d', time()); } ?>">
    <ul id='setlists-new-songs' data-role="listview" data-inset="true" data-divider-theme="b" data-split-icon="delete" data-split-theme="c">
      <li data-role="list-divider" role="heading">Songs</li>
    </ul>
    <div id="setlists-new-song-choices-box" style="padding: 15px 0;"> 
      <ul id="setlists-new-song-choices" data-filter-reveal="true" data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="Type a song title..." data-filter-theme="d">
      </ul>
    </div>
    <input type="submit" value="Add Setlist" data-theme="b" data-role="button" />
  </form>
  <div data-role="popup" id="setlists-song-chosen-by-popup" data-overlay-theme="a" data-theme="c" data-dismissible="false" class="ui-corner-all">
    <div data-role="header" data-theme="a" class="ui-corner-top">
      <h1>Add a song</h1>
    </div>
    <div data-role="content" data-theme="d" class="ui-corner-bottom ui-content">
      <h2>Song Title</h2>
      <label for="key">Key sung in:</label>
      <select id="setlists-songs-key">
        <option value="0">C
        <option value="1">C♯ / D♭
        <option value="2">D
        <option value="3">D♯ / E♭
        <option value="4">E
        <option value="5">F
        <option value="6">F♯ / G♭
        <option value="7">G
        <option value="8">G♯ / A♭
        <option value="9">A
        <option value="10">A♯ / B♭
        <option value="11">B
      </select>
      <label for="setlists-song-chosen-by-select">Chosen by:</label>
      <select id="setlists-song-chosen-by-select">
        <?php
          foreach ($users as $user) {
            echo "<option value='{$user->id}'>{$user->first_name} {$user->last_name}</option>";
          }
        ?>
      </select>
      <a href='#' id='setlist-chosen-by-submit' data-theme='b' data-role='button'>Add Song</a>
    </div>
  </div>
  <script>
    $("#setlists-new-song-choices").on("listviewbeforefilter", function ( e, data ) {
      var $ul = $( this ),
          $input = $( data.input ),
          value = $input.val(),
          html = "";
      $ul.html( "" );
      if ( value && value.length > 2 ) {
        $ul.html( "<li><div class='ui-loader'><span class='ui-icon ui-icon-loading'></span></div></li>" );
        $ul.listview( "refresh" );
        $.ajax({
          url: "/search/song_titles/" + $input.val(),
          dataType: "json"
        })
        .then( function ( response ) {
          $.each( response, function ( i, val ) {
            if ($('#setlists-new-songs li[data-id=' + val.id + ']').length === 0) {
              html += "<li><a href='#' data-artist='" + val.artist + "' data-id='" + val.id + "' data-key='" + val.key + "'>" + val.title + "</a></li>";
            }
          });
          $ul.html( html );
          $ul.listview( "refresh" );
          $ul.trigger( "updatelayout");
        });
      }
    });
    $(document).on('click', '#setlists-new-song-choices a[data-id]', function (e) {
      $('#setlists-song-chosen-by-popup').popup('open');
      $('#setlists-song-chosen-by-popup').attr('data-id', $(this).attr('data-id'));
      $('#setlists-song-chosen-by-popup').attr('data-title', $(this).text());
      $('#setlists-song-chosen-by-popup').attr('data-artist', $(this).attr('data-artist'));
      $('#setlists-song-chosen-by-popup h2').text($(this).text());
      $('#setlists-songs-key').val($(this).attr('data-key') || 0);
      $('#setlists-songs-key').selectmenu('refresh');
    });
    $(document).on('click', '#setlist-chosen-by-submit', function (e) {
      if ($('#setlists-new-songs li[data-id=' + $('#setlists-song-chosen-by-popup').attr('data-id') + ']').length === 0) {
        var nextIndex = $('#setlists-new-songs li[data-id]').length;
        $('#setlists-new-songs').append(
          "<li data-theme='c' data-id='" + $('#setlists-song-chosen-by-popup').attr('data-id') + "'>"
          + "<a href='#'>" + $('#setlists-song-chosen-by-popup').attr('data-title')
          + " (" + $('#setlists-song-chosen-by-popup').attr('data-artist') + ") </a>"
          + "<a href='#' class='remove-song'>Remove Song</a>"
          + "<input type='hidden' name='songs[" + nextIndex + "][id]' value='"
          + $('#setlists-song-chosen-by-popup').attr('data-id')
          + "' /><input type='hidden' name='songs[" + nextIndex + "][chosen_by]' value='"
          + $('#setlists-song-chosen-by-select').val() + "' />"
          + "<input type='hidden' name='songs[" + nextIndex + "][key]' value='"
          + $('#setlists-songs-key').val() + "' /></li>");
        $('#setlists-new-songs').listview('refresh');
        $('#setlists-new-song-choices-box .ui-input-clear').click();
        $('#setlists-new-song-choices').html('');
        $('#setlists-song-chosen-by-popup').popup('close');
      }
    });
    $(document).on('click', '.remove-song', function (e) {
      $(this).closest('li').remove();
    });
  </script>
</div>
<?php include_once('../views/includes/footer_jqm.php'); ?>