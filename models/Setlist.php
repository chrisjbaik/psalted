<?php
class Setlist extends Model {
  public function save() {
    if (empty($this->created_at)) {
      $this->created_at = time();
    }
    if (empty($this->updated_at)) {
      $this->updated_at = time();
    }
    parent::save();
    return true;
  }

  public function users() {
  	return $this->has_many_through('User');
  }

  public function songs() {
  	return $this->has_many_through('Song');
  }
}