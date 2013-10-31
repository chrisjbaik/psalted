$ = require('jquery')
$.mobile = require('jquery-mobile')

$(document).delegate "#groups-view", "pageinit", ->
  $('.groups-delete-link').on 'click', (e) ->
    $('#group-delete-form').attr('action', '/groups/' + $(this).attr('data-id'))