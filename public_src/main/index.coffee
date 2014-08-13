$ = require('jquery')
$.mobile = require('jquery-mobile')

#$(document).delegate "#index", "pageinit", ->
  #alert 'Page INDEX OPENED!'

$(document).delegate "#index", "pagehide", ->
	# Remove login page to protect password
	$(this).remove()