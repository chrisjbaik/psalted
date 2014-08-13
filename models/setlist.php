<?php
class Setlist extends Model {
  public function save() {
    if (empty($this->title)) {
      return false;
    }

    if (empty($this->created_at)) {
      $this->created_at = time();
    }
    if (empty($this->updated_at)) {
      $this->updated_at = time();
    }
    if (empty($this->date)) {
      $this->date = time();
    }
    if (empty($this->url)) {
      $this->generateSlug();
    }
    parent::save();
    return true;
  }

  public function delete() {
    Model::factory('SetlistSong')->where('setlist_id', $this->id)->delete_many();
    parent::delete();
    return true;
  }

  public function user() {
  	return $this->belongs_to('User');
  }

  public function songs() {
  	return $this->has_many_through('Song')->order_by_asc('priority');
  }

  public function group() {
    return $this->belongs_to('Group');
  }

  public function pdfName() {
    return preg_replace('/^-+|-+$/', "", preg_replace('/-+/', "-", preg_replace('/[_|\s]+/', "-", strtolower($this->title)))).'.pdf';
  }

  public function generateSlug() {
    $url = URLify::filter($this->title);
    $found = Model::factory('Setlist')->where('url', $url)->find_one();

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

      $found = Model::factory('Setlist')->where('url', $url)->find_one();
    }
    $this->url = $url;

    return true;
  }
}