//(function($) {
  function Songsheet(options) {
    var defaultOptions = {
      orientation: 'portrait',
      unit: 'in',
      width: 8.5, // inches
      height: 11, // inches
      columns: 2,
      gutter: 1, // inches
      margin: { // inches
        top: 0.5,
        bottom: 0.5,
        left: 0.5,
        right: 0.5
      },
      fonts: {
        default: {
          name: 'times',
          style: 'normal',
          size: 10,
          lineHeight: 12,
          background: null
        },
        bridge: {
          style: 'italic'
        },
        chorus: {
          background: [178, 178, 178]
        },
        title: {
          name: 'helvetica',
          style: 'bold',
          size: 12
        }
      },
      formatting: {
        titleSpace: 0.25,
        songSpace: 1.75,
        collapseMark: ' (×[:times])'
      },
      // Collapse Level:
      // 0  = no collapse
      // 1  = if whole paragraph contains only repeating lines
      // 2  = if repeating lines are at the beginning/end of the paragraph
      // 3  = all repeating lines
      collapseLevel: 2,
      forceCollapse: false,
      copies: 2
    }
    var options = $.extend({}, defaultOptions, options);
    options.contentWidth = options.width - options.margin.left - options.margin.right;
    options.contentHeight = options.height - options.margin.top - options.margin.bottom;
    options.columnWidth = (options.contentWidth + options.gutter) / options.columns;

    // Extend font styles
    for (name in options.fonts) {
      if (name == 'default') continue;
      options.fonts[name] = $.extend({}, options.fonts.default, options.fonts[name]);
    }

    this.options = options;
    this.doc = new jsPDF(options.orientation, options.unit, [options.width, options.height]);
    this.columns = [];
    this.songs = [];

    for (var col = 0; col < options.columns; col++) {
      var x = this.colX(col);
      this.columns[col] = { x: x, y: options.margin.top, center: x + (options.columnWidth - options.gutter)/2 };
    }

    return this;
  }

  Songsheet.prototype.colX = function(col) {
    return this.options.margin.left + (col * this.options.columnWidth);
  }

  Songsheet.prototype.pointsToUnit = function(value) {
    if (this.options.unit == 'in') {
      return value/72;
    } else {
      return value;
    }
  }

  Songsheet.prototype.collapseLyrics = function(lyrics, level) {
    if (level == undefined) {
      level = this.options.collapseLevel;
    }

    if (level == 0) {
      return lyrics;
    }

    var collapsed = [];
    var collapseCount = 1;
    for (var i = 0; i < lyrics.length; i++) {
      var line = lyrics[i];
      if (line[2] == 0 || line[2] > level) {
        if (collapseCount > 1) {
          collapsed[collapsed.length-1][1] += this.options.formatting.collapseMark.replace('[:times]', collapseCount);
        }
        collapsed.push([line[0], line[1], line[2]]);
        collapseCount = 1;
      } else {
        collapseCount++
      }
    }

    if (collapseCount > 1) {
      collapsed[collapsed.length-1][1] += this.options.formatting.collapseMark.replace('[:times]', collapseCount);
    }

    return collapsed;
  }

  Songsheet.prototype.songHeights = function(lyrics) {
    // Title space
    var h = (1 + this.options.formatting.titleSpace) * this.options.fonts.title.lineHeight;
    var heights = { 1: 0, 2: 0, 3: 0 };

    for (var i = 0; i < lyrics.length; i++) {
      var style = lyrics[i][0];
      if (! this.options.fonts[style]) {
        style = 'default';
      }

      h += this.options.fonts[style].lineHeight;
      if (lyrics[i][2] > 0) {
        heights[lyrics[i][2]] += this.options.fonts[style].lineHeight;
      }
    }

    heights[0] = this.pointsToUnit(h);
    for (var i = 1; i < 4; i++) {
      heights[i] = heights[i-1] - this.pointsToUnit(heights[i]);
    }

    return heights;
  }

  Songsheet.prototype.textCenter = function(x, y, text, font) {
    if (text == '') {
      return this;
    }

    this.doc.setFont(font.name, font.style);
    this.doc.setFontSize(font.size);
    var textWidth = this.pointsToUnit(this.doc.getStringUnitWidth(text) * font.size);

    if (font.background) {
      // Fill background
      this.doc.setDrawColor(0);
      this.doc.setFillColor(font.background[0], font.background[1], font.background[2]);
      this.doc.rect(x - textWidth/2 - 0.015, y, textWidth + 0.03, this.pointsToUnit(font.lineHeight), 'F');
    }

    this.doc.text(x - textWidth/2, y + this.pointsToUnit(font.size * 0.9), text);
    
    return this;
  }

  Songsheet.prototype.printSong = function(col, song) {
    var doc = this.doc;
    var defaultFont = this.options.fonts.default;
    var titleFont = this.options.fonts.title;
    var formatting = this.options.formatting;

    // Title
    this.textCenter(this.columns[col].center, this.columns[col].y, song.title, titleFont);
    this.columns[col].y += this.pointsToUnit((1 + formatting.titleSpace) * titleFont.lineHeight);

    // Lyrics
    for (var i = 0; i < song.lyricsForPrint.length; i++) {
      var line = song.lyricsForPrint[i];
      var style = line[0];
      if (! this.options.fonts[style]) {
        style = 'default';
      }
      var content = line[1];
      var font = this.options.fonts[style];
      this.textCenter(this.columns[col].center, this.columns[col].y, content, font);
      this.columns[col].y += this.pointsToUnit(font.lineHeight);
    }

    this.columns[col].y += this.pointsToUnit(formatting.songSpace * defaultFont.lineHeight);

    return this;
  }

  Songsheet.prototype.addSong = function(song) {
    // Process lyrics
    var lines = song.lyrics.split("\n");
    var lyricsArray = [];
    var style = 'default';
    var collapseLevel = 0;
    var lastLine = null;

    for (var i = 0; i < lines.length; i++) {
      var line = lines[i].trim();

      if (line == "") {
        lyricsArray.push(['default', '', 0]);
        lastLine = null;
        continue;
      }

      var matches = line.match(/^\[(?:([a-z]+)(?:\s*)(?:[0-9]*))+\]$/i);

      if (matches) {
        style = matches[1];
        lastLine = null;
      } else {
        // Get rid of the chords
        // Collapse the space
        line = line.replace(/\[[^\[\]]*\]/g, '').replace(/\s+/g, ' ').trim();
        if (lastLine == line) {
          collapseLevel = 3;
        } else {
          collapseLevel = 0;
        }
        lastLine = line;
        lyricsArray.push([style, line, collapseLevel]);
      }
    }

    // Re-adjust collapse levels
    if (lyricsArray.length > 0) {
      var i = -1;
      while (i <= lyricsArray.length) {
        if (i == -1 || i == lyricsArray.length || lyricsArray[i][1] == '') {
          if (i > 0) {
            // End of paragraph
            // Look back
            var j = i-1
            while (j > 0 && lyricsArray[j][2] == 3) {
              lyricsArray[j][2] = 2;
              j--;
            }
          }

          if (i+1 < lyricsArray.length && lyricsArray[i+1][1] != '') {
            // Beginning of paragraph
            // Look ahead
            var j = i+2
            while (j < lyricsArray.length && lyricsArray[j][2] == 3) {
              lyricsArray[j][2] = 2;
              j++;
            }
            if (j >= lyricsArray.length || lyricsArray[j][1] == '') {
              // Whole paragraph
              for (var k = i+2; k < j; k++) {
                lyricsArray[k][2] = 1;
              }
            }
          }
        }
        i++;
      }
    }

    song.lyricsOriginal = song.lyrics;
    song.lyrics = lyricsArray;

    // Calculate song height for each collapse level
    song.heights = this.songHeights(lyricsArray);

    this.songs.push(song);
    return this;
  }

  Songsheet.prototype.addSongs = function(songs) {
    var self = this;
    $.each(songs, function(i,song) {
      self.addSong(song);
    });
    return this;
  }

  Songsheet.prototype.estimateColumns = function() {
    var options = this.options;
    var collapseLevel = options.collapseLevel;
    var forceCollapse = options.forceCollapse;

    var h = 0;
    for (var i = 0; i < this.songs.length; i++) {
      h += songs[i].heights[collapseLevel];
    }
  }

  Songsheet.prototype.distributeColumns = function() {
    var options = this.options;
    var collapseLevel = options.collapseLevel;
    var totalHeight = -this.pointsToUnit(options.formatting.songSpace * options.fonts.default.lineHeight);

    // Get songs' height
    var songInfo = [];
    for (var i = 0; i < this.songs.length; i++) {
      var h = this.songs[i].heights[collapseLevel];
      songInfo[i] = { id: i, height: h };
      totalHeight += h + this.pointsToUnit(options.formatting.songSpace * options.fonts.default.lineHeight);
    }

    if (totalHeight <= options.contentHeight) {
      // Return one column
      return [{ height: totalHeight, songs: this.songs }];
    }

    songInfo.sort(function(a,b) {
      return b.height - a.height;
    });

    // Reset column heights
    var columnHeights = [];
    for (var col = 0; col < options.columns; col++) {
      columnHeights[col] = 0;
    }
    
    function minIndex(arr) {
      var i = 0, min = arr[0], minIndex = 0;

      while(++i < arr.length) {
        if (arr[i] < min) {
          minIndex = i;
          min = arr[i];
        }
      }

      return minIndex;
    }

    // Assign songs to the column with the most space
    for (var i = 0; i < songInfo.length; i++) {
      var id = songInfo[i].id;
      var col = minIndex(columnHeights);
      columnHeights[col] += songInfo[i].height + this.pointsToUnit(options.formatting.songSpace * options.fonts.default.lineHeight);
      this.songs[id].col = col;
    }
    
    // Translating columns to the order of the songs
    var columnsTranslate = {};
    var currentCol = 0;

    // Add songs into columns
    var columnSongs = [];
    for (var i = 0; i < this.songs.length; i++) {
      var col = this.songs[i].col;
      if (col in columnsTranslate) {
        col = columnsTranslate[col];
      } else {
        columnsTranslate[col] = currentCol;
        columnSongs[currentCol] = { songs: [], height: columnHeights[col] - this.pointsToUnit(options.formatting.songSpace * options.fonts.default.lineHeight) };
        col = currentCol;
        currentCol++;
      }
      console.log(col, this.songs[i].title);
      columnSongs[col]['songs'].push(this.songs[i]);
    }
    
    return columnSongs;
  }

  Songsheet.prototype.render = function() {
    var options = this.options;
    var songs = this.songs;

    // Fill background
    /*
    this.doc.setDrawColor(0);
    this.doc.setFillColor(224,224,224);
    this.doc.rect(this.colX(0), options.margin.top, options.columnWidth - options.gutter, options.height - options.margin.top - options.margin.bottom, 'F');
    this.doc.rect(this.colX(1), options.margin.top, options.columnWidth - options.gutter, options.height - options.margin.top - options.margin.bottom, 'F');
    */

    var columnSongs = this.distributeColumns();
    var numCol = columnSongs.length;
    var numPages = options.copies;
    if (numCol == 1) {
      numPages = numPages / 2;
    }

    // Print into PDF
    for (var page = 0; page < numPages; page++) {
      for (var col = 0; col < options.columns; col++) {
        var column;
        if (col < numCol) {
          column = columnSongs[col];
        } else {
          column = columnSongs[0];
        }
        this.columns[col].y = options.margin.top + (options.contentHeight - column.height)/2;
        for (var i = 0; i < column.songs.length; i++) {
          column.songs[i].lyricsForPrint = this.collapseLyrics(column.songs[i].lyrics);
          this.printSong(col, column.songs[i]);
        }
      }

      if (page+1 < numPages) {
        this.doc.addPage();
      }
    }
    return this;
  }

  Songsheet.prototype.save = function(filename) {
    return this.doc.save(filename);
  }

  Songsheet.prototype.dataURI = function() {
    return this.doc.output('datauristring');
  }
//}(jQuery));
