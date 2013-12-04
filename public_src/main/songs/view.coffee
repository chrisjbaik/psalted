$ = require('jquery')
$.mobile = require('jquery-mobile')

$(document).delegate "#songs-view", "pageinit", ->
  $('.chordsify-raw').chordsify()
  
  $('#select-transpose').on 'change', (e) ->
    key = $(e.currentTarget).val()
    $('#song-chords').chordsify('transpose', key)