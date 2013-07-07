<?php
class Hybridauth extends Model {
  public function user() {
    return $this->belongs_to('User');
  }
  public function save() {
    parent::save();
    return true;
  }
}