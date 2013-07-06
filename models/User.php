<?php
class User extends Model {
  public function save() {
    if (empty($this->email) ||
        empty($this->password) ||
        empty($this->first_name) ||
        empty($this->last_name)) {
      return false;
    }
    $email_exists = Model::factory('User')->where('email', $this->email)->count();
    if ($email_exists) {
      return false;
    }
    $this->created_at = date('U');
    parent::save();
    return true;
  }

  public function setlists() {
    return $this->has_many_through('Setlist');
  }
}