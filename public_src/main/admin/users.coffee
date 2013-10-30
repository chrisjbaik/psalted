$ = require('jquery')
$.mobile = require('jquery-mobile')

$(document).delegate "#admin-users", "pageinit", ->
  $('.admin-users-delete-link').on 'click', (e) ->
    $('#admin-users-delete-form').attr('action', '/admin/users/' + $(this).attr('data-id'))
    popup = $(e.currentTarget).closest('[data-role=popup]')
    popup.on 'popupafterclose', ->
      setTimeout ->
        $($(e.currentTarget).attr('href')).popup('open')
        popup.off 'popupafterclose'
      , 100
    popup.popup('close')