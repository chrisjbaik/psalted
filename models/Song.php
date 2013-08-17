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
      $this->filterLyricsFromChords();
    }
    $this->saveFullTextCopy();
    parent::save();
    return true;
  }
  public function saveFullTextCopy() {
    $song_fts = ORM::for_table('song_fts')->create();
    $song_fts->rowid = $this->id;
    $song_fts->title = $this->title;
    $song_fts->lyrics = $this->lyrics;
    $song_fts->artist = $this->artist;
    $song_fts->save();
  }
  public function filterLyricsFromChords() {
    $this->lyrics = preg_replace('/\[[^\]]*\]/', '', $this->chords);
    return true;
  }

  public function generateSlug() {
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
}