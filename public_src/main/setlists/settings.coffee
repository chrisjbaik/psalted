$ = require('jquery')
$.mobile = require('jquery-mobile')

$(document).delegate "#setlists-settings", "pageinit", ->
  $page = $(this)
  $useGroup = $page.find '#setlist-use-group'
  settings = JSON.parse $page.find('#setlist-settings-target .settings-this').attr('data-settings')
  groupSettings = JSON.parse $page.find('#setlist-settings-target .settings-group').attr('data-settings')

  updateSettings = (settings) ->
    for key,value of settings
      $el = $page.find('[name="settings\\['+key+'\\]"]')
      settings[key] = $el.val()
    return settings

  applySettings = (settings) ->
    for key,value of settings
      $el = $page.find('[name="settings\\['+key+'\\]"]')
      $el.val(value)
      if $el.attr('data-role') is 'slider'
        $el.slider "refresh"
      else
        $el.selectmenu "refresh"

  $useGroup.change ->
    $section = $page.find('#setlist-settings-section')
    $section.fadeOut 200, () ->
      if $useGroup.val() is "1"
        settings = updateSettings settings
        applySettings groupSettings
        $page.find('#setlist-settings-target .settings-this').addClass "hidden"
        $page.find('#setlist-settings-target .settings-group').removeClass "hidden"
      else
        groupSettings = updateSettings groupSettings
        applySettings settings
        $page.find('#setlist-settings-target .settings-group').addClass "hidden"
        $page.find('#setlist-settings-target .settings-this').removeClass "hidden"
    $section.fadeIn 200