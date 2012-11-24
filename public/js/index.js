/* Author: Varoot Phasuthadol, Chris Baik */
function convertLyrics() {
	var key = parseInt($('#original_key').val());
	var txt = $('#input textarea').val();
	var output = $('#output');
	
	var outText = txt.replace(/^([^\[]*)/gm,
		'<span class="phrase">\
			<span class="chord"></span>\
			<span class="lyrics">$1</span>\
		</span>');
	outText = $('<div>'+outText.replace(/\[([^\]]+)\]([^\[]*)(?=(\s|\[|$))/g,
		'<span class="phrase">\
			<span class="chord">\
				<b>$1</b>\
			</span>\
			<span class="lyrics">$2</span>\
		</span>').replace(/\n/g, '&nbsp;</div><div>')+'</div>');

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
	
	output.html(outText);
	
	$('#transposed_key').val(key);
	$('#transpose').show();
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
		$(this).text(Mustache.render($(this).data('mustache'), chordList));
		$(this).attr('data-chord', ($(this).data('firstChord')+key) % 12);
	});
}

function loadAllSongs() {
	$.get('/songs.json', function (data) {
		var songs = JSON.parse(data);
		songs.forEach(function (song) {
			$('.songs-list').append('<li><a href="#" class="load-song" data-url="/song/'+song.url+'">'+song.title+'</a></li>')
		})
	})
}

function loadSong(url) {
	$.get(url, function (data) {
		$('div[role="main"]').html(data);
	});
}

$(function () {
	loadAllSongs();

	$('.load-song').live('click', function (e) {
		loadSong($(this).attr('data-url'));
	});

	$('#add-new-song').click(function (e) {
		$.get('/new', function (data) {
			$('div[role="main"]').html(data);
		});
	});

	$('#transpose').hide();
	$('#transposed_key').live('change', function() {
		transpose();
	});
	$('#convertBtn, .preview-button').live('click', function(e) {
		e.preventDefault();
		convertLyrics();
	});
});