/* Author: Varoot Phasuthadol, Chris Baik */
function convertLyrics(key, input) {
	var output = $('#output');
	
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
}

function transpose() {
	var key = parseInt($('#transposed_key').val());
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
	
	$('#output').find('.chord b').each(function() {
		if ($(this).text() !== '') {
			$(this).text(Mustache.render($(this).data('mustache'), chordList));
			$(this).attr('data-chord', ($(this).data('firstChord')+key) % 12);
		}
	});
}

function loadAllSongs() {
	$.get('/songs.json', function (data) {
		var songs = JSON.parse(data);
		songs.forEach(function (song) {
			$('.songs-list').append('<li><a href="/song/' + song.url + '" class="load-song">'+song.title+'</a></li>')
		})
	})
}

function loadPreview() {
	var key = parseInt($('#original_key').val());
	var lyrics = convertLyrics(key, $('#input textarea').val());
	$('#output').html(lyrics);
	$('#preview-area').show();
	$('#transposed_key').val(key);
	getMeta();
}

function getMeta(){
	var track = $('#title').val();
	var artist = $('#artist').val();
	var searchURL = 'http://ws.spotify.com/search/1/track.json?q=' + track + '+'+ artist;
    jQuery.get(searchURL, function(data, textStatus, jqXHR) {
    	var options = '';
    	for (var i=0; i < Math.min(4, data.tracks.length); i++) {
    		options += '<option value="'+data.tracks[i].href+'">'+data.tracks[i].name +'-' +data.tracks[i].artists[0].name;
    	}
    	options += '<option value="">(None of the above)';
    	$('#spotify_id').html(options);
    	updatePlayButton();

    }, 'json' )
}

function updatePlayButton(){
	var songUrl = $('#spotify_id').val();
	console.log(songUrl);
	if (songUrl == null || songUrl == "")
		$('#play').html('')
	else
   		$('#play').html('<iframe src="https://embed.spotify.com/?uri=' +songUrl + '" width="'+$('#play').width()+'" height="80" frameborder="0" allowtransparency="true"></iframe>');
}

$(function () {
	if ($('.songs-list').length > 0) {
		loadAllSongs();
	}
	$('#preview-area').hide();

	$('#transposed_key').live('change', function() {
		transpose();
	});
	$('.preview-button').live('click', function(e) {
		e.preventDefault();
		loadPreview();
	});
	$('#title, #artist').change(getMeta);
	$('#spotify_id').change(updatePlayButton);
});