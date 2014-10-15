<?php
class Song extends Model {
  public function save() {
    if (empty($this->title)) {
      return false;
    }
    if (empty($this->url)) {
      $this->generateSlug();
    }
    if (!empty($this->chords)) {
      $options = array();
      $options['originalKey'] = $this->key;
      $s = new Chordsify\Song($this->chords, $options);
      $this->chords = $s->text(array('chords' => true, 'sections' => true, 'formatted' => false));
      $this->lyrics = $s->text(array('chords' => false, 'sections' => false, 'formatted' => false));
      $this->has_chords = $s->hasChords;
      $this->key = $s->originalKey()->value();
    } else {
      $this->has_chords = false;
    }

    //$this->saveFullTextCopy();
    parent::save();
    return true;
  }

  public function keyToString($key = null) {
    if ($key === null) {
      $key = $this->key;
    }
    
    if ($key === null || $key === 'auto') {
      return '';
    }

    $keyArray = array(
      0 => 'C',
      1 => 'D♭',
      2 => 'D',
      3 => 'E♭',
      4 => 'E',
      5 => 'F',
      6 => 'F♯',
      7 => 'G',
      8 => 'A♭',
      9 => 'A',
      10 => 'B♭',
      11 => 'B'
    );
    return $keyArray[$key];
  }
  
  public function filterLyricsFromChords() {
    $this->lyrics = preg_replace('/\[[^\]]*\]/', '', $this->chords);
    return true;
  }

  public function generateSlug() {
    URLify::$remove_list = array();
    $url = URLify::filter($this->title);
    $found = Model::factory('Song')->where('url', $url)->find_one();

    while ($found) {
      $url = preg_replace_callback('/[-]?([0-9]+)?$/', function ($matches) {
        if (isset($matches[1])) {
          return '-' . ($matches[1] + 1);
        } else if (empty($matches[0])) {
          return '-1';
        } else {
          return;
        }
      }, $url, 1);

      $found = Model::factory('Song')->where('url', $url)->find_one();
    }
    $this->url = $url;

    return true;
  }

  public function tags() {
    return $this->has_many_through('Tag');
  }

  public static function queryPopularity() {
    $songs = Model::factory('Song')->select('song.id');

    // Popularity Equation
    $songs->select_expr(<<<QUERY
      TOTAL(
        CASE WHEN julianday("now", "-8 days") > julianday(setlist.date, "unixepoch")
          THEN 24/(julianday("now") - julianday(setlist.date, "unixepoch"))
          ELSE 3
        END
      )
QUERY
    , 'popularity');

    $songs->join('setlist_song', array('song.id', '=', 'setlist_song.song_id'));
    $songs->join('setlist', array('setlist.id', '=', 'setlist_song.setlist_id'));
    $songs->group_by('song.id');

    $popularity = array();
    foreach ($songs->find_array() as $p) {
      $popularity[$p['id']] = $p['popularity'];
    }
    return $popularity;
  }
}