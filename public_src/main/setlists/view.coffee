$ = require('jquery')
$.mobile = require('jquery-mobile')

$(document).delegate "#setlists-view", "pagecreate", ->
  $('.setlists-delete-link').on 'click', (e) ->
    if $(this).attr('data-group-url')
      $('#setlist-delete-form').attr('action', '/groups/' + $(this).attr('data-group-url') + '/' + $(this).attr('data-setlist-url'))
    else
      $('#setlist-delete-form').attr('action', '/personal/' + $(this).attr('data-setlist-url'))
  $("#btn-pdf-save").on 'click', (e) ->
    $.get $(e.currentTarget).attr('data-url'), (data) ->
      return alert(data.error) if data.error
      # sheet = new Songsheet()
      # sheet.addSongs(data.songs).render().save($(e.currentTarget).attr('data-pdf'))
    , 'json'