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
  	return $this->has_many_through('Song')
      ->select('chosen_by')
      ->select_expr('CASE WHEN setlist_song.key IS NOT NULL THEN setlist_song.key ELSE song.key END','setlist_key')
      ->order_by_asc('priority');
  }

  public function group() {
    return $this->belongs_to('Group');
  }

  // This function acts as setter and getter
  public function settings($settings = NULL) {
    if ($settings === NULL) {
      $s = $this->belongs_to('SetlistSettings', 'settings_id')->find_one();
      if ($s) {
        return $s->extract();
      } else {
        return SetlistSettings::$default;
      }
    } else {
      return $this->settings_id = SetlistSettings::getID($settings);
      // Don't forget to save
    }
  }

  public function settingsForPDF() {
    if ($this->settings_id) {
      return $this->settings();
    } elseif ($this->group_id) {
      return $this->group()->find_one()->settings();
    } elseif ($this->user_id) {
      return $this->user()->find_one()->settings();
    } else {
      // I don't think it would ever get here but just to be safe
      return SetlistSettings::$default;
    }
  }

  public function pdfName() {
    return preg_replace('/^-+|-+$/', "", preg_replace('/-+/', "-", preg_replace('/[_|\s]+/', "-", strtolower($this->title)))).'.pdf';
  }

  public function pdfOutput() {
    $settings = $this->settingsForPDF();

    $sheet = new Chordsify\SongSheet(SetlistSettings::writerOptions($settings));
    $songs = $this->songs()->find_many();
    foreach ($songs as $song) {
      $s = new Chordsify\Song($song->chords, array('title'=>$song->title, 'originalKey'=>$song->key));
      $sheet->add($s);
    }

    return $sheet->pdfOutput('D', $this->pdfName());
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