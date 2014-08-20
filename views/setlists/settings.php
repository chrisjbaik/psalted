<?php include_once('../views/includes/header.php'); ?>

<?php
function printOptions($default, array $options) {
  foreach ($options as $value => $text) {
    $selected = (($value == $default) ? ' selected' : '');
    echo "<option value=\"$value\"$selected>$text</option>";
  }
}
?>
<div data-role="content">
  <form data-ajax="false" method="post">
    <div class="ui-field-contain">
      <label for="setlist-use-group">Use group's default settings</label>
      <select name="use_group" id="setlist-use-group" data-role="slider">
      <?php
        printOptions($use_group, array('No', 'Yes'));
      ?>
      </select>
    </div>

    <br>
    <div id="setlist-settings-section">
      <div id="setlist-settings-target">
        <div class="settings-group<?= $use_group ? '' : ' hidden' ?>" data-settings='<?= json_encode($group_settings) ?>'>
          <h3>Settings for: <?= $group->name ?></h3>
          <div class="ui-bar ui-bar-a">These settings will apply to all setlists in this group</div>
        </div>
        <div class="settings-this<?= $use_group ? ' hidden' : '' ?>" data-settings='<?= json_encode($settings) ?>'>
          <h3>Settings for: this setlist</h3>
          <div class="ui-bar ui-bar-a">These settings will apply only to this setlist</div>
        </div>
      </div>
      <?php
      $s = $use_group ? $group_settings : $settings;
      ?>
      <div class="ui-field-contain">
        <label for="setlist-pdf-style">Style:</label>
        <select name="settings[style]" id="setlist-pdf-style">
        <?php
          printOptions($s['style'], array('center'=>'Center', 'left'=>'Left', 'chords'=>'Chords'));
        ?>
        </select>
      </div>

      <div class="ui-field-contain">
        <label for="setlist-pdf-size">Paper size:</label>
        <select name="settings[size]" id="setlist-pdf-size">
        <?php
          printOptions($s['size'], array('Letter'=>'Letter', 'A4'=>'A4'));
        ?>
        </select>
      </div>

      <div class="ui-field-contain">
        <label for="setlist-pdf-copies">Number of copies:</label>
        <select name="settings[copies]" id="setlist-pdf-copies">
        <?php
          printOptions($s['copies'], array('auto'=>'Auto', '1'=>'1', '2'=>'2'));
        ?>
        </select>
      </div>

      <div class="ui-field-contain">
        <label for="setlist-pdf-songnumber">Song numbering:</label>
        <select name="settings[songnumber]" id="setlist-pdf-songnumber" data-role="slider">
        <?php
          printOptions($s['songnumber'], array('off'=>'Off', 'on'=>'On'));
        ?>
        </select>
      </div>

      <div class="ui-field-contain">
        <label for="setlist-pdf-pagenumber">Page numbering:</label>
        <select name="settings[pagenumber]" id="setlist-pdf-pagenumber" data-role="slider">
        <?php
          printOptions($s['pagenumber'], array('off'=>'Off', 'auto'=>'Auto'));
        ?>
        </select>
      </div>
    </div>
    
    <br>

    <input id="submit-form" type="submit" value="Save Settings" data-theme="b">
  </form>
</div>
<?php
  include_once('../views/includes/footer.php');
