$ = require('jquery')
$.mobile = require('jquery-mobile')

$(document).delegate "#songs-list", "pagecreate", ->
  $(this).find('[name="view"]').change ()->
    view = $(this).val()

    $list = $('#songs-list-songs')
    $children = $list.children('.listview-checkbox').removeClass('hidden ui-last-child')
    
    if view is 'certified'
      $children.filter('[data-certified="0"]').addClass('hidden')
      $children.filter('[data-certified="1"]').last().addClass('ui-last-child')
    else if view is 'chords'
      $children.filter('[data-chords="0"]').addClass('hidden')
      $children.filter('[data-chords="1"]').last().addClass('ui-last-child')
    else
      $children.last().addClass('ui-last-child')

  $(this).find('[name="sortby"]').change ()->
    sortby = $(this).val()
    order = if sortby is 'pop' then -1 else 1
    $list = $('#songs-list-songs')
    $children = $list.children('.listview-checkbox')
    $children.last().removeClass('ui-last-child')

    # http://trentrichardson.com/2013/12/16/sort-dom-elements-jquery/
    $children.sort (a, b)->
      aText = $(a).attr('data-'+sortby)
      bText = $(b).attr('data-'+sortby)
      if sortby is 'key'
        aText = +aText
        bText = +bText
      if aText > bText
        return order
      if bText > aText
        return -order
      return 0

    $children.detach().appendTo $list
    $children.last().addClass('ui-last-child')

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