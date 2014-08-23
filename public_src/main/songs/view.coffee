$ = require('jquery')
$.mobile = require('jquery-mobile')

$(document).delegate "#songs-view", "pagecreate", ->
  $chordsify = $('.chordsify-raw')
  if $chordsify.length > 0
  	$chordsify.chordsify()
  
  $('#select-transpose').on 'change', (e) ->
    key = $(e.currentTarget).val()
    $('#song-chords').chordsify('transpose', key)

  $(":mobile-pagecontainer").on "pagecontainershow", (event,ui) ->
    $chordsify = $('.chordsify:not(.chordsify-raw)')
    if $chordsify.length > 0
    	$chordsify.chordsify('position')