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
  }
}