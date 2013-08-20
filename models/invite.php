<?php
class Invite extends Model {
  public function save() {
    if (empty($this->key)) {
      $this-generateKey();
    }
    parent::save();
    return true;
  }
  public function generateKey() {
    $this->key = substr(md5(rand()), 0, 15);
  }
}