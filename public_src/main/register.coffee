$ = require('jquery')
$.mobile = require('jquery-mobile')

$(document).delegate "#register", "pageinit", ->

  $(document).on 'change', '#password, #password-confirm', (e) ->
    password = $('#password').val()
    password_confirm = $('#password-confirm').val()
    if password is password_confirm
      $('#submit-form').val('Register').button('refresh')
      $('#submit-form').button('enable','refresh')
    else
      $('#submit-form').val("Why u so like dat one? Passwords don't match lah").button('refresh')
      $('#submit-form').button('disable','refresh')