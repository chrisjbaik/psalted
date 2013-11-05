$ = require('jquery')
$.mobile = require('jquery-mobile')
songutils = require('libs/song_utils')

$(document).delegate "#songs-view", "pageinit", ->
  chords = songutils.convertLyrics($('#song-chords').attr('data-key'), $('#song-chords').attr('data-chords-json'))
  $('#song-chords').html(chords);

  $('#select-transpose').on 'change', (e) ->
    key = $(e.currentTarget).val()
    songutils.transpose(key, '#song-chords')