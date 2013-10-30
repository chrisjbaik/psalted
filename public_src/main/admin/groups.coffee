$ = require('jquery')
$.mobile = require('jquery-mobile')

$(document).delegate "#admin-groups", "pageinit", ->
  $('.admin-groups-delete-link').on 'click', (e) ->
    $('#admin-groups-delete-form').attr('action', '/admin/groups/' + $(this).attr('data-id'))
    popup = $(e.currentTarget).closest('[data-role=popup]')
    popup.on 'popupafterclose', ->
      setTimeout ->
        $($(e.currentTarget).attr('href')).popup('open')
        popup.off 'popupafterclose'
      , 100
    popup.popup('close')