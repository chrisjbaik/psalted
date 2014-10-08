$ = require('jquery')
$.mobile = require('jquery-mobile')

$(document).delegate "#setlists-edit", "pageinit", ->
  keyTexts = ["C", "D♭", "D", "E♭", "E", "F", "F♯", "G", "A♭", "A", "B♭", "B"]

  nextIndex = $('#setlists-new-songs li[data-id]').length

  checkSetlistLength = () ->
    songs = []
    $("#setlists-new-songs [data-id]").each (i,song) ->
      songs.push $(song).attr("data-id")
    $.getJSON "/setlists/length", { songs: songs }, (data) ->
      if data.error is ""
        $("#setlists-warning-pages-count").text data.pages
        $("#setlists-warning-pages").toggleClass "hidden", data.pages < 2

  checkSetlistLength()
  
  $("#setlists-new-song-choices").on "filterablebeforefilter", (e, data) ->
    $ul = $( this )
    $input = $( data.input )
    value = $input.val()
    html = ""
    $ul.html( "" )
    if value && value.length > 2
      $ul.html( "<li><div class='ui-loader'><span class='ui-icon ui-icon-loading'></span></div></li>" )
      $ul.listview( "refresh" )
      $.ajax
        url: "/search/song_titles/" + $input.val()
        dataType: "json"
      .then (response) ->
        $.each response, (i, val) ->
          if $("#setlists-new-songs li[data-id=#{val.id}]").length is 0
            html += """<li data-icon="false">
              <a href="#" class="song-label #{if val.artist is "" then "song-no-artist" else ""} " data-artist="#{val.artist}" data-id="#{val.id}" data-key="#{val.key}">
                <div class="song-label-key" data-chord="#{val.key}">#{keyTexts[+val.key]}</div>
                <h2 class="listview-heading">#{val.title}</h2>
                #{if val.artist isnt "" then "<span class=\"listview-footer\">#{val.artist}</span>" else ""}
              </a>
            </li>"""
        $ul.html(html)
        $ul.listview("refresh")
        $ul.trigger("updatelayout")

  $(document).on 'click', '#setlists-new-song-choices a[data-id]', (e) ->
    $('#setlists-song-chosen-by-popup').popup('open')
    $('#setlists-song-chosen-by-popup').attr('data-id', $(this).attr('data-id'))
    $('#setlists-song-chosen-by-popup').attr('data-title', $(this).find('h2').text())
    $('#setlists-song-chosen-by-popup').attr('data-artist', $(this).attr('data-artist'))
    $('#setlists-song-chosen-by-popup h2').text($(this).text())
    $('#setlists-songs-key').val($(this).attr('data-key') || 0)
    $('#setlists-songs-key').selectmenu('refresh')

  $(document).on 'click', '#setlist-chosen-by-submit', (e) ->
    $chosenSong = $('#setlists-song-chosen-by-popup')

    if $('#setlists-new-songs li[data-id=' + songID + ']').length is 0
      $('#setlists-new-songs-empty').addClass('hidden')
      
      songID = $chosenSong.attr('data-id')
      title = $chosenSong.attr('data-title')
      artist = $chosenSong.attr('data-artist')
      chosenBy = $('#setlists-song-chosen-by-select').val()
      setlistKey = +$('#setlists-songs-key').val()
      setlistKeyText = keyTexts[setlistKey]
      
      $('#setlists-new-songs').append """
        <li data-id="#{songID}" class="setlist-view-song">
          <a href="#" class="song-label #{if artist is "" then "song-no-artist" else ""} ">
            <div class="song-label-key" data-chord="#{setlistKey}">#{setlistKeyText}</div>
            <h2 class="listview-heading">#{title}</h2>
            #{if artist isnt "" then "<span class=\"listview-footer\">#{artist}</span>" else ""}
          </a>
          <a href="#" class="remove-song">Remove Song</a>
          <input type="hidden" name="songs[#{nextIndex}][id]" value="#{songID}">
          <input type="hidden" name="songs[#{nextIndex}][chosen_by]" value="#{chosenBy}">
          <input type="hidden" name="songs[#{nextIndex}][key]" value="#{setlistKey}">
        </li>"""

      $('#setlists-new-songs').listview('refresh')
      $('#setlists-new-song-choices-box .ui-input-clear').click()
      $('#setlists-new-song-choices').html('')
      $chosenSong.popup('close')
      nextIndex++

    checkSetlistLength()

  $(document).on 'click', '.remove-song', (e) ->
    $(this).closest('li').remove()
    if $('#setlists-new-songs li[data-id]').length is 0
      $('#setlists-new-songs-empty').removeClass('hidden')
    $('#setlists-new-songs').listview('refresh')
    checkSetlistLength()

  submitText = $('#setlist-submit').attr('value') 
  $(document).on 'input', '#setlist-title', (e) ->
    if $('#setlist-title').val() is ''
      $('#setlist-submit').val('Walau-eh! Need a Setlist name').button('refresh')
      $('#setlist-submit').button('disable','refresh')
    else 
      $('#setlist-submit').button('enable','refresh')
      $('#setlist-submit').val(submitText).button('refresh')