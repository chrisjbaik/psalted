$ = require('jquery')
$.mobile = require('jquery-mobile')

$(document).delegate "#songs-list", "pagecreate", ->
  $('input[name="checked_songs[]"]').on 'change', ->
    if (count = $('input[name="checked_songs[]"]:checked').length) > 0
      $('.ui-footer-fixed').removeClass('hidden')
      $('.songs-list-selected-count').text(count)
      songlist = ""
      $('input[name="checked_songs[]"]:checked').each ->
        songlist += $(this).val() + ","
      songlist = songlist.substr(0, songlist.length-1)
      $('#song-setlist-popup-form input[name=songs]').val(songlist)
      $('#song-setlist-popup-new').attr('href', '/setlists/new?songs='+songlist)
    else
      $('.ui-footer-fixed').addClass('hidden')