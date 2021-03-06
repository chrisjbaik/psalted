<?php include_once('../views/includes/header.php'); ?>
<div data-role="content">
  <form id="setlists-new-form" method="post" data-ajax='false'>
    <label>
      Group:
      <select name="group">
      <?php
        echo "<option value='personal' ";
        if (empty($group)) {
          echo "selected";
        }
        echo ">Personal Setlists</option>";
        if (!empty($groups)) {
          foreach ($groups as $g) {
            echo "<option value='{$g->id}' ";
            if (!empty($group) && $group->id == $g->id) {
              echo "selected";
            }
            echo ">{$g->name}</option>";
          }
        }
      ?>
      </select>
    </label>
    <label for="setlist-title" class="ui-hidden-accessible">Setlist Name</label>
    <input type="text" name="title" id="setlist-title" placeholder="Setlist Name" value="<?php if (!empty($setlist->title)) { echo $setlist->title; } else { echo 'Default name'; } ?>">
    <label for="setlist-date" class="ui-hidden-accessible">Setlist Date</label>
    <input type="date" name="date" id="setlist-date" value="<?php if (!empty($setlist->date)) { echo date('Y-m-d', $setlist->date); } else { echo date('Y-m-d', time()); } ?>">
    <div id="setlists-warning-pages" class="hidden ui-body alert-warning ui-corner-all"><strong>Warning:</strong> This setlist requires <span id="setlists-warning-pages-count">2</span> pages</div>
    <ul id='setlists-new-songs' data-role="listview" data-inset="true" data-divider-theme="b" data-split-icon="delete" data-split-theme="b">
      <li data-role="list-divider" role="heading">Songs</li>
      <?php
        echo "<li id='setlists-new-songs-empty' ";
        if (!empty($songs)) {
          echo " class='hidden'";
        }
        echo ">Type in the box below to add more songs...</li>";
        if (!empty($songs)) {
          foreach ($songs as $index => $song) {
            echo '<li data-id="'.$song->id.'" class="setlist-view-song">';
            echo '<a href="#" class="song-label'.($song->artist ? '' : ' song-no-artist').'">';
            echo '<div class="song-label-key" data-chord="'.$song->setlist_key.'">'.$song->keyToString($song->setlist_key).'</div>';
            echo '<h2 class="listview-heading">'.$song->title.'</h2>';
            if (!empty($song->artist)) {
              echo '<span class="listview-footer">'.$song->artist.'</span>';
            }
            echo "</a>";

            echo "<a href='#' class='remove-song'>Remove Song</a>";
            echo "<input type='hidden' name='songs[{$index}][id]' value='{$song->id}' />";
            echo "<input type='hidden' name='songs[{$index}][chosen_by]' value='{$song->chosen_by}' />";
            echo "<input type='hidden' name='songs[{$index}][key]' value='{$song->setlist_key}' />";
            echo "</li>";
          }
        }
      ?>
    </ul>
    <div id="setlists-new-song-choices-box" style="padding: 15px 0;"> 
      <ul id="setlists-new-song-choices" data-filter-reveal="true" data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="Type a song title...">
      </ul>
    </div>
     <?php if (empty($setlist)) { $submitText = 'Add Setlist'; } else {$submitText = "Save Changes"; } ?>
    <input type="submit" id="setlist-submit" value= '<?php echo $submitText ?>' data-theme="b" data-role="button">
  </form>
  <div data-role="popup" id="setlists-song-chosen-by-popup" data-overlay-theme="a" data-theme="a" class="ui-corner-all">
    <div data-role="header" class="ui-corner-top">
      <h2>Song Title</h2>
    </div>
    <div data-role="content" class="ui-corner-bottom ui-content">
      <h2>Song Title</h2>
      <label for="key">Key sung in:</label>
      <select id="setlists-songs-key">
        <option value="0">C
        <option value="1">C♯ / D♭
        <option value="2">D
        <option value="3">D♯ / E♭
        <option value="4">E
        <option value="5">F
        <option value="6">F♯ / G♭
        <option value="7">G
        <option value="8">G♯ / A♭
        <option value="9">A
        <option value="10">A♯ / B♭
        <option value="11">B
      </select>
      <label for="setlists-song-chosen-by-select">Chosen by:</label>
      <select id="setlists-song-chosen-by-select">
        <?php
          foreach ($users as $user) {
            echo "<option value='{$user->id}'>{$user->first_name} {$user->last_name}</option>";
          }
        ?>
      </select>
      <a href='#' id='setlist-chosen-by-submit' data-theme='b' data-role='button'>Add the Song</a>
    </div>
  </div>
</div>
<?php include_once('../views/includes/footer.php'); ?>