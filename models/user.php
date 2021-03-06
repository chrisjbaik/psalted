<?php
class User extends Model {
  public function save() {
    if (empty($this->email) ||
        empty($this->password) ||
        empty($this->first_name) ||
        empty($this->last_name)) {
      return false;
    }
    if (empty($this->id)) {
      $email_exists = Model::factory('User')->where('email', $this->email)->count();
      if ($email_exists) {
        return false;
      }
      $this->created_at = date('U');
    }
    if (empty($this->roles)) {
      $this->roles = serialize(array('member'));
    }
    parent::save();
    return true;
  }

  public function delete() {
    Model::factory('GroupUser')->where('user_id', $this->id)->delete_many();
    $setlists = $this->setlists()->find_many();
    foreach ($setlists as $setlist) {
      $setlist->delete();
    }
    parent::delete();
    return true;
  }

  public function setlists() {
    return $this->has_many('Setlist');
  }

  public function groups() {
    return $this->has_many_through('Group');
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

  public function hasRole($role) {
    $user_roles = unserialize($this->roles);
    if (empty($user_roles)) {
      return false;
    }
    return in_array($role, $user_roles);
  }
}