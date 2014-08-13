$ = require('jquery')
$.mobile = require('jquery-mobile')

$(document).delegate "#songs-view", "pagecreate", ->
  $('.chordsify-raw').chordsify()
  
  $('#select-transpose').on 'change', (e) ->
    key = $(e.currentTarget).val()
    $('#song-chords').chordsify('transpose', key)

  $(":mobile-pagecontainer").on "pagecontainershow", (event,ui) ->
    $('.chordsify:not(.chordsify-raw)').chordsify('position')