<?php
class Song extends Model {
  public function save() {
    if (empty($this->title) || empty($this->url)) {
      return false;
    }
    parent::save();
    return true;
  }
}