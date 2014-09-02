$ = require('jquery')
$.mobile = require('jquery-mobile')

$(document).delegate "#setlists-songs", "pagecreate", ->
  $page = $(this)
  $chordsify = $('.chordsify-raw')
  if $chordsify.length > 0
    $chordsify.chordsify()

  $page.find('.transpose select').change (e) ->
    $select = $(e.currentTarget)
    key = $select.val()
    $song = $select.parents('.setlist-songs-song')
    $song.find('.chordsify').chordsify('transpose', key).chordsify('position')

  $page.find('.transpose-all').click (e) ->
    $song = $(e.currentTarget).parents('.setlist-songs-song')
    $select = $song.find('.transpose select')
    key = $select.val()
    index = $select[0].selectedIndex
    $page.find('.transpose select').each (i, sel) ->
      sel.selectedIndex = index
      $(sel).selectmenu 'refresh'
    $page.find('.chordsify').chordsify('transpose', key).chordsify('position')

  $(":mobile-pagecontainer").on "pagecontainershow", (event,ui) ->
    $chordsify = $('.chordsify:not(.chordsify-raw)')
    if $chordsify.length > 0
      $chordsify.chordsify('position')