$ = require('jquery')
$.mobile = require('jquery-mobile')

$(document).delegate "#groups-edit", "pageinit", ->
  $("#groups-new-member-choices").on "filterablebeforefilter", (e, data)->
    $ul = $(this)
    $input = $(data.input)
    value = $input.val()
    html = ''
    $ul.html ''
    if value?.length > 2
      $ul.html '<li><div class="ui-loader"><span class="ui-icon ui-icon-loading"></span></div>'
      $ul.listview 'refresh'
      $.ajax
        url: "/search/users/#{value}"
        dataType: "json"
      .then (response)->
        $.each response, (i, val)->
          if $("#groups-new-members li[data-id=#{val.id}]").length is 0
            html += "<li><a href=\"#\" data-id=\"#{val.id}\">#{val.first_name} #{val.last_name}</a>"
        $ul.html html
        $ul.listview "refresh"
        $ul.trigger "updatelayout"

  $(document).on 'click', '#groups-new-member-choices a[data-id]', (e)->
    $this = $(this)
    if $('#groups-new-members li[data-id=' + $this.attr('data-id') + ']').length is 0
      $('#groups-new-members').append("<li data-id=\"#{$this.attr('data-id')}\">#{$this.text()}<input type=\"hidden\" name=\"members[]\" value=\"#{$this.attr('data-id')}\">")
      .listview('refresh')
      $('#groups-new-member-choices-box .ui-input-clear').click()
      $('#groups-new-member-choices').html ''

  $(document).on 'click', '.remove-member', (e)->
    $(this).closest('li').remove()
