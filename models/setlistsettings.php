<?php
class SetlistSettings extends Model {
  public static $default = array(
    'copies'     => 'auto',
    'pagenumber' => 'auto',
    'size'       => 'Letter',
    'songnumber' => 'off',
    'style'      => 'center',
  );

  public static function validate($s) {
    $settings = self::$default;
    if ( ! is_array($s)) {
      return $settings;
    }

    if (isset($s['style']) and in_array($s['style'], array('center', 'left', 'chords'))) {
      $settings['style'] = $s['style'];
    }

    if (isset($s['size']) and in_array($s['size'], array('A4', 'Letter'))) {
      $settings['size'] = $s['size'];
    }

    if (isset($s['copies']) and $s['copies'] == 'auto' or is_numeric($s['copies'])) {
      $settings['copies'] = $s['copies'];
    }

    if (isset($s['songnumber']) and in_array($s['songnumber'], array('on', 'off'))) {
      $settings['songnumber'] = $s['songnumber'];
    }

    if (isset($s['pagenumber']) and in_array($s['pagenumber'], array('auto', 'off'))) {
      $settings['pagenumber'] = $s['pagenumber'];
    }

    return $settings;
  }

  public function extract() {
    return self::validate($this->as_array());
  }

  public static function getID($settings) {
    $settings = self::validate($settings);

    $found = Model::factory('SetlistSettings')
      ->where('copies',     $settings['copies'])
      ->where('pagenumber', $settings['pagenumber'])
      ->where('size',       $settings['size'])
      ->where('songnumber', $settings['songnumber'])
      ->where('style',      $settings['style'])
      ->find_one();
    if ($found) {
      return $found->id;
    }

    $obj = Model::factory('SetlistSettings')->create();
    $obj->copies     = $settings['copies'];
    $obj->pagenumber = $settings['pagenumber'];
    $obj->size       = $settings['size'];
    $obj->songnumber = $settings['songnumber'];
    $obj->style      = $settings['style'];
    $obj->save();

    return $obj->id;
  }

}