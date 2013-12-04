/*
Chordsify 0.11
Last update: 2013-12-04
Author: Varoot Phasuthadol
*/
(function ( $ ) {
	var sharpKeys = ['C','C#','D','D#','E','F','F#','G','G#','A','A#','B'];
	var flatKeys = ['C','Db','D','Eb','E','F','Gb','G','Ab','A','Bb','B'];
	var keyMap = {
		'C' : 0,  'C#': 1,  'Db': 1,
		'D' : 2,  'D#': 3,  'Eb': 3,
		'E' : 4,
		'F' : 5,  'F#': 6,  'Gb': 6,
		'G' : 7,  'G#': 8,  'Ab': 8,
		'A' : 9,  'A#': 10, 'Bb': 10,
		'B' : 11
	};

	/*
	Helper functions
	*/
	function validKey(key) {
		return (keyNumber(key) != undefined);
	}

	function keyNumber(key) {
		if (key == undefined) return undefined;
		if (typeof key === 'number') return key % 12;
		if (! isNaN(parseInt(key))) return +key % 12;

		// Else, assume a string
		key = key.trim();
		// Apply uppercase to the first letter
		key = key.slice(0,1).toUpperCase() + key.slice(1);
		return keyMap[key];
	}

	function replaceFlatSharp(text, chars) {
		if (text == undefined) return undefined;
		return text.replace('b', chars.flat).replace('#', chars.sharp);
	}

	function restoreFlatSharp(text, chars) {
		if (text == undefined) return undefined;
		return text.replace(chars.flat, 'b').replace(chars.sharp, '#');
	}

	function detectChords(chordText, key, opts) {
		var result = $('<div>');
		var chordLetters = chordText.split('');
		var l = 0;
		while (l < chordLetters.length) {
			var thisChord = chordLetters[l];
			if (!validKey(thisChord))
			{
				result.append(thisChord);
				l++;
				continue;
			}

			var chordTag = $(opts.elements.chordTag).appendTo(result);
			if (l+1 < chordLetters.length) {
				var nextLetter = chordLetters[l+1];
				if (nextLetter == '#' || nextLetter == 'b')
				{
					if (validKey(thisChord + nextLetter)) {
						thisChord = thisChord + nextLetter;
						l++;
					}
				}
			}

			chordTag.text(replaceFlatSharp(thisChord, opts.chars)).attr(opts.dataAttr.chordRel, (keyMap[thisChord] - key + 12) % 12);
			
			l++;
		}

		return result.html();
	}

	/*
	Main class
	*/

	function Chords(element, opts) {
		this.element = element;
		this.options = opts;
		
		var $element = $(element);
		this.init($element.attr(opts.dataAttr.originalKey), $element.text());

		var transposeKey = $element.attr(opts.dataAttr.transposeKey);
		if (validKey(transposeKey)) {
			this.transpose(transposeKey);
		}
	}

	Chords.prototype.option = function(opts) {
		this.options = $.extend(this.options, opts);
		return this;
	};

	Chords.prototype.text = function() {
		var self = this;
		var element = $(this.element);
		var opts = this.options;

		var result = '';
		element.find('.'+opts.classes.block).each(function(i,block) {
			var $block = $(block);
			var blockType = $block.attr(opts.dataAttr.blockType);
			var blockNum = $block.attr(opts.dataAttr.blockNum);
			if (blockType != undefined && blockType != '') {
				result += '['+blockType+(blockNum > 0 ? ' '+blockNum : '')+']\n';
			}
			$block.find('.'+opts.classes.line).each(function(j,line) {
				$(line).find('.'+opts.classes.phrase).each(function(k,phrase) {
					$phrase = $(phrase);
					var chordText = restoreFlatSharp($phrase.find('.'+opts.classes.chord).text(), opts.chars);
					var lyricsText = $phrase.find('.'+opts.classes.lyrics).text();
					if (chordText != undefined && chordText != '') {
						result += '['+chordText+']';
					}
					result += lyricsText;
				});
				result += '\n';
			});
		});

		return result;
	};

	Chords.prototype.destroy = function() {
		var self = this;
		var element = $(this.element);
		var opts = this.options;

		element.html(self.text()).addClass(opts.classes.raw).removeData('chordsify').removeAttr(opts.dataAttr.transposeKey).attr(opts.dataAttr.originalKey, self.key);
		return self;
	};

	Chords.prototype.replace = function(text) {
		var self = this;
		var $element = $(this.element);
		var opts = this.options;

		return self.init($element.attr(opts.dataAttr.originalKey), text);
	}

	Chords.prototype.init = function(key, text) {
		var self = this;
		var $element = $(this.element);
		var opts = this.options;

		key = keyNumber(key);
		self.key = key;
		self.originalKey = key;
		$element.html('').removeClass(opts.classes.raw);

		var block = $(opts.elements.block).addClass(opts.classes.block).appendTo($element);
		$.each(text.trim().split('\n'), function(i,lineText) {
			lineText = lineText.trim();
			var matches = lineText.match(opts.blockRegEx);
			if (matches) {
				if (block.text() != '') {
					// Make a new block
					block = $(opts.elements.block).addClass(opts.classes.block).appendTo($element);
				}

				block.attr(opts.dataAttr.blockType, matches[1]);

				if (matches[2] != "") {
					block.attr(opts.dataAttr.blockNum, matches[2]);
				} else {
					block.removeAttr(opts.dataAttr.blockNum);
				}

				return;
			}

			var line = $(opts.elements.line).addClass(opts.classes.line).appendTo(block);
			if (lineText == '')
			{
				line.text('\xa0');
				return;
			}

			$.each(lineText.split('['), function(j, phraseText) {
				if (phraseText == '') return;

				var phrase = $(opts.elements.phrase).addClass(opts.classes.phrase).appendTo(line);
				var phraseItems = phraseText.split(']');
				var lyricsText = phraseItems.pop();
				var chordText = phraseItems.join('').trim();

				if (j > 0) {
					var lastPhrase = phrase.prev();
					if (lastPhrase && lastPhrase.find('.'+opts.classes.lyrics).text().slice(-1) != ' ' && lyricsText.slice(0,1) != ' ') {
						lastPhrase.addClass(opts.classes.phraseCond);
					}
				}

				if (chordText != '') {
					var chord = $(opts.elements.chord).addClass(opts.classes.chord).appendTo(phrase);
					var chordInner = $(opts.elements.chordInner).addClass(opts.classes.chordInner).appendTo(chord);

					if (key == undefined) {
						chordInner.text(chordText);
					} else {
						chordInner.html(detectChords(chordText, key, opts));

						// Find first chord
						var firstChord = chordInner.find('['+opts.dataAttr.chordRel+']').first().attr(opts.dataAttr.chordRel);
						if (firstChord != undefined) {
							firstChord = (+firstChord + key) % 12;
							chordInner.attr(opts.dataAttr.chord, firstChord);
						}
					}
				}

				if (lyricsText == '') {
					lyricsText = '\xa0';
				}

				var lyrics = $(opts.elements.lyrics).addClass(opts.classes.lyrics).appendTo(phrase).text(lyricsText);
			});
		});

		return self;
	};

	Chords.prototype.transpose = function(key) {
		var self = this;
		var element = $(this.element);
		var opts = this.options;

		key = keyNumber(key);
		var chordKeys = sharpKeys;
		if ([0, 5, 10, 3, 8, 1].indexOf(key) >= 0) {
			chordKeys = flatKeys;
		}

		self.key = key;
		element.find('['+opts.dataAttr.chord+']').removeAttr(opts.dataAttr.chord);
		element.find('['+opts.dataAttr.chordRel+']').each(function(i,chordTag) {
			var $chordTag = $(chordTag);
			var chordRel = +$chordTag.attr(opts.dataAttr.chordRel);
			var chord = (chordRel + key) % 12;
			$chordTag.text(replaceFlatSharp(chordKeys[chord], opts.chars));
			var chordInner = $chordTag.parent();
			if (chordInner.attr(opts.dataAttr.chord) == undefined) {
				chordInner.attr(opts.dataAttr.chord, chord);
			}
		});
	};

	$.fn.chordsify = function(options, param) {
		if (typeof(options) == 'string') {
			if (options == 'text') {
				var result = '';
				this.each(function(i,e) {
					result += $(e).data('chordsify').text();
				});
				return result;
			}
			
			return this.each(function(i,e) {
				var chords = $(e).data('chordsify');
				switch (options) {
					case 'option':
						chords.option(param);
					break;
					case 'replace':
						chords.replace(param);
					break;
					case 'destroy':
						chords.destroy();
					break;
					case 'transpose':
						chords.transpose(param);
					break;
				}
			});
		}

		if (this.data('chordsify')) {
			return this;
		}

		var opts = $.extend(true, {}, $.fn.chordsify.defaults, options);
		return this.each(function(i,e) {
			$(e).data('chordsify', new Chords(e, opts));
		});
	};

	$.fn.chordsify.defaults = {
		// Block only supports "verse", "prechorus", "chorus", "bridge", and "tag"
		blockRegEx: /^\[\s*(verse|prechorus|chorus|bridge|tag)\s*(\d*)\s*\]$/i,
		chars: {
			flat: '\u266d',
			sharp: '\u266f'
		},
		classes: {
			block: 'chordsify-block',
			chord: 'chordsify-chord',
			chordInner: 'chordsify-chord-inner',
			line: 'chordsify-line',
			lyrics: 'chordsify-lyrics',
			phrase: 'chordsify-phrase',
			phraseCond: 'chordsify-phrase-cond',
			raw: 'chordsify-raw'
		},
		dataAttr: {
			blockType: 'data-block-type',
			blockNum: 'data-block-num',
			chord: 'data-chord',
			chordRel: 'data-chord-rel',
			originalKey: 'data-original-key',
			transposeKey: 'data-transpose-to'
		},
		elements: {
			block: '<div>',
			chord: '<sup>',
			chordInner: '<span>',
			chordTag: '<span>',
			line: '<div>',
			lyrics: '<span>',
			phrase: '<span>'
		}
	};

}( jQuery ));
