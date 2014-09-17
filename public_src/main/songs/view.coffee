$ = require('jquery')
$.mobile = require('jquery-mobile')

$(document).delegate "#songs-view", "pagecreate", ->
  $page = $ this
  $transpose = $page.find('#select-transpose')
  $chordsify = $page.find('.chordsify-raw')
  if $chordsify.length > 0
    $chordsify.chordsify()
    $transpose.on 'change', (e) ->
      key = $(e.currentTarget).val()
      $chordsify.chordsify('transpose', key)

$(document).on "pagecontainershow", ":mobile-pagecontainer", (event,ui) ->
  $chordsify = $('.chordsify:not(.chordsify-raw)')
  if $chordsify.length > 0
    $chordsify.chordsify('position')