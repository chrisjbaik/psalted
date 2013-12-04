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
    $('#song-chords').chordsify('destroy') if $('#song-chords').data('chordsify')?

    $('#song-chords').html($('#chord-lyrics').val()).chordsify()

  $('#spotify').change (e) ->
    updateSpotifyPreview()

  $('#song-edit-title-input, #song-edit-artist-input').keyup (e) ->
    clearTimeout(spotifySearchTimeout) if spotifySearchTimeout
    spotifySearchTimeout = setTimeout ->
      updateSpotifyOptions $('#song-edit-title-input').val(), $('#song-edit-artist-input').val()
    , 150