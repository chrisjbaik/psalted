$ = require('jquery')
$.mobile = require('jquery-mobile')

$(document).delegate "#register, #users-password", "pageinit", ->

  $(document).on 'change', '#password, #password-confirm', (e) ->
    password = $('#password').val()
    password_confirm = $('#password-confirm').val()
    $btn = $('#submit-form')
    if password isnt "" and password_confirm isnt ""
      if password is password_confirm
        $btn.val($btn.attr('data-valid-label'))
          .button('refresh')
          .button('enable','refresh')
      else
        $btn.val("Why u so like dat one? Passwords don't match lah")
          .button('refresh')
          .button('disable','refresh')