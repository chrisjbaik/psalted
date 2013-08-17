<?php
class Group extends Model {
  public function users() {
    return $this->has_many_through('User');
  }
  public function setlists() {
    return $this->has_many('Setlist');
  }

  public function save() {
    if (empty($this->name)) {
      return false;
    }
    if (empty($this->url)) {
      $this->generateSlug();
    }
    parent::save();
    return true;
  }

  public function delete() {
    Model::factory('GroupUser')->where('group_id', $this->id)->delete_many();
    $setlists = $this->setlists()->find_many();
    foreach ($setlists as $setlist) {
      $setlist->delete();
    }
    parent::delete();
    return true;
  }

  public function generateSlug() {
    $url = URLify::filter($this->name);
    $found = Model::factory('Group')->where('url', $url)->find_one();

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

      $found = Model::factory('Group')->where('url', $url)->find_one();
    }
    $this->url = $url;

    return true;
  }
}