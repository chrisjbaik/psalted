$ = require('jquery')
$.mobile = require('jquery-mobile')

$(document).delegate "#setlists-view", "pagecreate", ->
  # remove other setlists from DOM
  self = this
  $('#setlists-view').each (i,e) ->
    e.remove() if e isnt self
  $("#btn-pdf-save").on 'click', (e) ->
    $.get $(e.currentTarget).attr('data-url'), (data) ->
      return alert(data.error) if data.error
      # sheet = new Songsheet()
      # sheet.addSongs(data.songs).render().save($(e.currentTarget).attr('data-pdf'))
    , 'json'
