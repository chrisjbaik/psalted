<?php
class Tag extends Model {
  public function save() {
    if (empty($this->name)) {
      return false;
    }
    
    parent::save();
    return true;
  }

  public function songs() {
    return $this->has_many_through('User');
  }
}