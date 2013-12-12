$ = require('jquery')
$.mobile = require('jquery-mobile')

$(document).delegate "#setlists-edit", "pageinit", ->
  $("#setlists-new-song-choices").on "filterablebeforefilter", (e, data) ->
    $ul = $( this )
    $input = $( data.input )
    value = $input.val()
    html = ""
    $ul.html( "" )
    if value && value.length > 2
      $ul.html( "<li><div class='ui-loader'><span class='ui-icon ui-icon-loading'></span></div></li>" )
      $ul.listview( "refresh" )
      $.ajax
        url: "/search/song_titles/" + $input.val()
        dataType: "json"
      .then (response) ->
        $.each response, (i, val) ->
          if $('#setlists-new-songs li[data-id=' + val.id + ']').length is 0
            html += "<li><a href='#' data-artist='" + val.artist + "' data-id='" + val.id + "' data-key='" + val.key + "'>" + val.title + "</a></li>"
        $ul.html(html)
        $ul.listview("refresh")
        $ul.trigger("updatelayout")

  $(document).on 'click', '#setlists-new-song-choices a[data-id]', (e) ->
    $('#setlists-song-chosen-by-popup').popup('open')
    $('#setlists-song-chosen-by-popup').attr('data-id', $(this).attr('data-id'))
    $('#setlists-song-chosen-by-popup').attr('data-title', $(this).text())
    $('#setlists-song-chosen-by-popup').attr('data-artist', $(this).attr('data-artist'))
    $('#setlists-song-chosen-by-popup h2').text($(this).text())
    $('#setlists-songs-key').val($(this).attr('data-key') || 0)
    $('#setlists-songs-key').selectmenu('refresh')

  $(document).on 'click', '#setlist-chosen-by-submit', (e) ->
    chosenSong = $('#setlists-song-chosen-by-popup')
    artist = chosenSong.attr('data-artist')
    artist = if artist isnt '' then ' (' + artist + ')' else ''

    if $('#setlists-new-songs li[data-id=' + chosenSong.attr('data-id') + ']').length is 0
      $('#setlists-new-songs-empty').addClass('hidden')
      nextIndex = $('#setlists-new-songs li[data-id]').length
      appendHtml = "<li data-id='" + chosenSong.attr('data-id') + "'>" +
        "<a href='#'>" + chosenSong.attr('data-title') +
        artist + " </a>" +
        "<a href='#' class='remove-song'>Remove Song</a>" +
        "<input type='hidden' name='songs[" + nextIndex + "][id]' value='" +
        chosenSong.attr('data-id') +
        "' /><input type='hidden' name='songs[" + nextIndex + "][chosen_by]' value='" +
        $('#setlists-song-chosen-by-select').val() + "' />" +
        "<input type='hidden' name='songs[" + nextIndex + "][key]' value='" +
        $('#setlists-songs-key').val() + "' /></li>"
      $('#setlists-new-songs').append appendHtml
      $('#setlists-new-songs').listview('refresh')
      $('#setlists-new-song-choices-box .ui-input-clear').click()
      $('#setlists-new-song-choices').html('')
      chosenSong.popup('close')

  $(document).on 'click', '.remove-song', (e) ->
    $(this).closest('li').remove()
    if $('#setlists-new-songs li[data-id]').length is 0
      $('#setlists-new-songs-empty').removeClass('hidden')

  submitText = $('#setlist-submit').attr('value') 
  $(document).on 'input', '#setlist-title', (e) ->
    if $('#setlist-title').val() is ''
      $('#setlist-submit').val('Walau-eh! Need a Setlist name').button('refresh')
      $('#setlist-submit').button('disable','refresh')
    else 
      $('#setlist-submit').button('enable','refresh')
      $('#setlist-submit').val(submitText).button('refresh')