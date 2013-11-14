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
    if $('#setlists-new-songs li[data-id=' + $('#setlists-song-chosen-by-popup').attr('data-id') + ']').length is 0
      nextIndex = $('#setlists-new-songs li[data-id]').length
      appendHtml = "<li data-theme='c' data-id='" + $('#setlists-song-chosen-by-popup').attr('data-id') + "'>" +
        "<a href='#'>" + $('#setlists-song-chosen-by-popup').attr('data-title') +
        " (" + $('#setlists-song-chosen-by-popup').attr('data-artist') + ") </a>" +
        "<a href='#' class='remove-song'>Remove Song</a>" +
        "<input type='hidden' name='songs[" + nextIndex + "][id]' value='" +
        $('#setlists-song-chosen-by-popup').attr('data-id') +
        "' /><input type='hidden' name='songs[" + nextIndex + "][chosen_by]' value='" +
        $('#setlists-song-chosen-by-select').val() + "' />" +
        "<input type='hidden' name='songs[" + nextIndex + "][key]' value='" +
        $('#setlists-songs-key').val() + "' /></li>"
      $('#setlists-new-songs').append appendHtml
      $('#setlists-new-songs').listview('refresh')
      $('#setlists-new-song-choices-box .ui-input-clear').click()
      $('#setlists-new-song-choices').html('')
      $('#setlists-song-chosen-by-popup').popup('close')

  $(document).on 'click', '.remove-song', (e) ->
    $(this).closest('li').remove()

<<<<<<< HEAD
  $(document).on 'input', '#setlists-title', (e) ->
    if $('#setlist-title').val() is ''
      $('#setlist-submit').val('Walau-eh! Need a Setlist name').button('refresh')
      $('#setlist-submit').button('disable','refresh')
    else 
      $('#setlist-submit').button('enable','refresh')
=======

  
  submitText = $('#setlist-submit').val()
  $("#setlist-title").on 'input', (e) ->
    if $('#setlist-title').val() is ''
      $('#setlist-submit').val('Walau-eh! Need a Setlist name').button('refresh')
      $('#setlist-submit').button('disable','refresh')
    else
      $('#setlist-submit').val(submitText).button('refresh')
      $('#setlist-submit').button('enable','refresh')

>>>>>>> faf5c19e2ef62f6464c03a7a18e29873db46f88f
