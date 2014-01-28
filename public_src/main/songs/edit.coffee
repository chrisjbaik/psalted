$ = require('jquery')
$.mobile = require('jquery-mobile')
songutils = require('libs/song_utils')

$(document).delegate "#songs-edit", "pagecreate", ->
  if key = $('select[name=key]').attr('data-key')
    $('select[name=key] option[value=' + key + ']').attr('selected', 'selected')
    $('select[name=key]').selectmenu('refresh')

$(document).delegate "#songs-edit", "pageinit", ->
  updateSpotifyPreview = ->
    songUrl = $('#spotify').val()
    if (songUrl)
      $('#spotify-preview').html('<iframe src="https://embed.spotify.com/?uri=' +songUrl + '" width=100%"'+$('#play').width()+'" height="80" frameborder="0" allowtransparency="true"></iframe>')
    else
      $('#spotify-preview').html('')

  updateSpotifyOptions = (title, artist, preload_id) ->
    songutils.getSpotifySelectOptions $('#song-edit-title-input').val(), $('#song-edit-artist-input').val(), preload_id, (options) ->
      $('#spotify').html(options)
      if (preload_id)
        $('#spotify').val(spotify_id)
      $('#spotify').selectmenu('refresh')
      updateSpotifyPreview()

  spotifySearchTimeout = null

  $('.songs-delete-link').on 'click', (e) ->
    $('#song-delete-form').attr('action', '/songs/' + $(this).attr('data-id'))

  if spotify_id = $('#spotify').attr('data-spotify-id')
    updateSpotifyOptions($('#song-edit-title-input').val(), $('#song-edit-artist-input').val(), spotify_id)

  $('.song-preview').click (e) ->
    $('#song-chords').attr('data-original-key', $('#original-key').val()).chordsify().chordsify('replace', $('#chord-lyrics').val())

  $('#spotify').change (e) ->
    updateSpotifyPreview()

  $('#song-edit-title-input, #song-edit-artist-input').keyup (e) ->
    clearTimeout(spotifySearchTimeout) if spotifySearchTimeout
    spotifySearchTimeout = setTimeout ->
      updateSpotifyOptions $('#song-edit-title-input').val(), $('#song-edit-artist-input').val()
    , 150

  $("#new-tag-choices").on "filterablebeforefilter", (e, data)->
    $ul = $(this)
    $input = $(data.input)
    value = $input.val()
    tag_exists = false
    html = ''
    $ul.html ''
    if value?.length > 1 # at least two character
      $ul.html '<li><div class="ui-loader"><span class="ui-icon ui-icon-loading"></span></div>'
      $ul.listview 'refresh'
      $.ajax
        url: "/search/tags/#{value}"
        dataType: "json"
      .then (response)->
        $.each response, (i, val)->
          if $("#song-tags li[data-id=#{val.id}]").length is 0
            html += "<li><a href=\"#\" data-id=\"#{val.id}\">#{val.name}</a>"
          if val.name == value
            tag_exists = true
        if !tag_exists #Give option to add new tag if doesn't exist
          html += "<li><a href=\"#\" new-tag=\"#{value}\">Add \"#{value}\" as a new tag</a>"
        $ul.html html
        $ul.listview "refresh"
        $ul.trigger "updatelayout"

  $(document).on 'click', '#new-tag-choices a[data-id]', (e)->
    $this = $(this)
    if $('#song-tags li[data-id=' + $this.attr('data-id') + ']').length is 0
      $('#song-tags').append("<li data-id=\"#{$this.attr('data-id')}\"><a href=\"#\">#{$this.text()}</a><a data-theme=\"b\" href=\"#\" class=\"remove-tag\">X</a><input type=\"hidden\" name=\"tags[]\" value=\"#{$this.attr('data-id')}\">")
      .listview('refresh')
      $('#new-tag-choices-box .ui-input-clear').click()
      $('#new-tag-choices').html ''

  $(document).on 'click', '#new-tag-choices a[new-tag]', (e)-> #add new tag
    $this = $(this)
    if $('#song-tags li[value="' + $this.attr('new-tag') + '"]').length is 0
      $('#song-tags').append("<li value=\"#{$this.attr('new-tag')}\"><a href=\"#\">#{$this.attr('new-tag')}</a><a data-theme=\"b\" href=\"#\" class=\"remove-tag\">X</a><input type=\"hidden\" name=\"new_tags[]\" value=\"#{$this.attr('new-tag')}\">")
      .listview('refresh')
      $('#new-tag-choices-box .ui-input-clear').click()
      $('#new-tag-choices').html ''

  $(document).on 'click', '.remove-tag', (e)->
    $(this).closest('li').remove()