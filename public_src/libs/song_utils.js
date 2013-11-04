var Mustache = require('mustache');

module.exports = {
  getSpotifySelectOptions: function (title, artist, preload_id, cb) {
    var searchURL = 'http://ws.spotify.com/search/1/track.json?q=' + title + '+'+ artist;
    jQuery.get(searchURL, function (data, textStatus, jqXHR) {
      var options = '';
      var songIdTable = {};
      for (var i=0; i < Math.min(5, data.tracks.length); i++) {
        if (data.tracks[i]['external-ids'] && data.tracks[i]['external-ids'][0]) {
          var id = data.tracks[i]['external-ids'][0].id;
          if (! songIdTable[id]) {
            songIdTable[id] = true;
            options += '<option value="'+data.tracks[i].href+'">'+data.tracks[i].name +' (' +data.tracks[i].artists[0].name + ')';
          }
        }
      }
      options += '<option value="">(None of the above)';
      cb(options)
    }, 'json' )
  },
  convertLyrics: function (key, input) {
    var outText = $('<div class="song-chords"></div>');
    var inText = input.replace(/\\r\\n/g, '\n').split('\n');
    var lastSection;
    for (var i = 0; i < inText.length; i++) {
      var line = inText[i].trim();
      // Detect lyric section
      var matches = line.match(/^\[(?:([a-z]+)(?:\s*)(?:[0-9]*))+\]$/i);
      if (matches) {
        if (lastSection) {
          // Check out last empty line
          var lastChild = lastSection.children('.song-line').last();
          if (lastChild.html() == '&nbsp;') {
            lastChild.removeClass('song-line').addClass('song-section-break').appendTo(outText);
          }
        }
        lastSection = $('<div class="song-section '+matches[1]+'"></div>').appendTo(outText);
        continue;
      }

      if (! lastSection) {
        lastSection = $('<div class="song-section"></div>').appendTo(outText);
      }

      var l = $('<div class="song-line"></div>');
      words = line.split(/(\[[A-G]\S*\])/);
      j = 0;
      while (j < words.length) {
        var word = words[j];
        if (word == '') {
          j++;
          continue;
        }

        var chord = word.match(/\[([A-G]\S*)\]/);
        var lyric = '';
        if (chord) {
          if (j < words.length) {
            if (!words[j+1].match(/\[([A-G]\S*)\]/)) {
              lyric = words[j+1];
              j++;
            }
          }
          chord = chord[1];
        } else {
          lyric = word;
          chord = '';
        }
        lyric = lyric.replace(/ $/, '&nbsp;');
        l.append('<span class="phrase"><span class="chord"><b>'+chord+'</b></span><span class="lyrics">'+lyric+'</span></span>');
        j++;
      }

      if (l.html() == '') {
        l.html('&nbsp;');
      }

      lastSection.append(l);
    }

    // Restyle sections with no chords
    outText.find('.song-section').each(function(i,section) {
      var chords = $(section).find('.chord');
      if (chords.text() == '') {
        chords.remove();
        $(section).addClass('no-chord');
      }
    });

  /*
    var outText = input.replace(/^([^\[\n]*)\[/gm,
      '<span class="phrase">\
        <span class="chord"></span>\
        <span class="lyrics">$1</span>\
      </span>[');
    outText = outText.replace(/\[([^\]]+)\]([^\[\n]*)(?=(\s|\[|$))/g,
      '<span class="phrase">\
        <span class="chord">\
          <b>$1</b>\
        </span>\
        <span class="lyrics">$2</span>\
      </span>')
    outText = $('<div>' + outText.replace(/\n/g, '&nbsp;</div><div>') +'</div>');
  */
    var chordNumber = function(match, p1) {
      var chordTranslate = { 67:0, 68:2, 69:4, 70:5, 71:7, 65:9, 66:11 };
      var num = chordTranslate[match.charCodeAt(0)];
      if (p1 == '♯') {
        num++;
      } else if (p1 == '♭') {
        num--;
      }
      num = (num - key);
      
      while (num < 0) num+=12;
      
      num %= 12;
      
      return '{{'+num+'}}';
    };
    
    outText.find('.chord').each(function() {
      $(this).html($(this).html().replace(/\s+/g, '</b><b>'));
    });
    
    outText.find('.chord b').each(function() {
      var chord = $(this).text().replace('#','♯').replace('b','♭');
      $(this).text(chord);
      chord = chord.replace(/[A-G](\u266F|\u266D)?/g, chordNumber);
      var match = chord.match(/{{([0-9]+)}}/);
      if (match) {
        var firstChord = parseInt(match[1]);
        $(this).data('firstChord', firstChord);
        $(this).attr('data-chord', (firstChord+key) % 12);
      }
      $(this).data('mustache', chord);
    });
    return outText;
  },

  transpose: function (key, output) {
    var sharpChordList = {
      0: 'C',   1: 'C♯',  2: 'D',   3: 'D♯',  4: 'E',   5: 'F',
      6: 'F♯',  7: 'G',   8: 'G♯',  9: 'A',  10: 'A♯', 11: 'B'
    };

    var flatChordList = {
      0: 'C',   1: 'D♭',  2: 'D',   3: 'E♭',  4: 'E',   5: 'F',
      6: 'G♭',  7: 'G',   8: 'A♭',  9: 'A',  10: 'B♭', 11: 'B'
    };

    var chordSource = sharpChordList;
    if ([0, 5, 10, 3, 8, 1].indexOf(key) >= 0) {
      chordSource = flatChordList;
    }
    
    var chordList = {};
    for (var i = 0; i < 12; i++) {
      chordList[i] = chordSource[(i + key) % 12];
    }
    
    $(output).find('.chord b').each(function() {
      if ($(this).text() !== '') {
        $(this).text(Mustache.render($(this).data('mustache'), chordList));
        $(this).attr('data-chord', ($(this).data('firstChord')+key) % 12);
      }
    });
  }
}