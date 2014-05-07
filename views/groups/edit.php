<?php include_once('../views/includes/header.php'); ?>
<div data-role="content">
  <form id="groups-new" method="post" data-ajax='false'>
    <label for="textinput-hide" class="ui-hidden-accessible">Group Name</label>
    <input type="text" name="name" id="textinput-hide" placeholder="Group Name" value="<?php if (!empty($group->name)) { echo $group->name; } ?>">
    <ul id='groups-new-members' data-role="listview" data-inset="true" data-divider-theme="a" data-split-icon='delete' data-split-theme='c'>
      <li data-role="list-divider" role="heading">Members</li>
      <?php
        if (!empty($members)) {
          foreach ($members as $member) {
            echo "<li data-id=\"{$member->id}\">";
            echo "<a href=\"#\">{$member->first_name} {$member->last_name}</a>";
            echo '<a href="#" class="remove-member" data-theme="b">Remove Member</a>';
            echo "<input type=\"hidden\" name=\"members[]\" value=\"{$member->id}\">";
          }
        } else {
          echo "<li data-id='{$user->id}'>{$user->first_name} {$user->last_name}<input type='hidden' name='members[]' value='{$user->id}'>";
        }
      ?>
    </ul>
    <div id="groups-new-member-choices-box" style="padding: 15px 0;"> 
      <ul id="groups-new-member-choices" data-filter-reveal="true" data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="Type a person's name...">
      </ul>
    </div>
    <div class="ui-field-contain">
      <fieldset data-role="controlgroup" data-type="horizontal">
        <legend>Verse:</legend>
        <input type="checkbox" name="checkbox-verse-bold" id="checkbox-verse-bold" class="custom">
        <label for="checkbox-verse-bold">b</label>
     
        <input type="checkbox" name="checkbox-verse-italics" id="checkbox-verse-italics" class="custom">
        <label for="checkbox-verse-italics"><em>i</em></label>
     
        <input type="checkbox" name="checkbox-verse-underline" id="checkbox-verse-underline" class="custom">
        <label for="checkbox-verse-underline">u</label>
      </fieldset>
    </div>
    <div class="ui-field-contain">
      <fieldset data-role="controlgroup" data-type="horizontal">
        <legend>Chorus:</legend>
        <input type="checkbox" name="checkbox-chorus-bold" id="checkbox-chorus-bold" class="custom">
        <label for="checkbox-chorus-bold">b</label>
     
        <input type="checkbox" name="checkbox-chorus-italics" id="checkbox-chorus-italics" class="custom">
        <label for="checkbox-chorus-italics"><em>i</em></label>
     
        <input type="checkbox" name="checkbox-chorus-underline" id="checkbox-chorus-underline" class="custom">
        <label for="checkbox-chorus-underline">u</label>
      </fieldset>
    </div>
    <div class="ui-field-contain">
      <fieldset data-role="controlgroup" data-type="horizontal">
        <legend>Bridge:</legend>
        <input type="checkbox" name="checkbox-bridge-bold" id="checkbox-bridge-bold" class="custom">
        <label for="checkbox-bridge-bold">b</label>
     
        <input type="checkbox" name="checkbox-bridge-italics" id="checkbox-bridge-italics" class="custom">
        <label for="checkbox-bridge-italics"><em>i</em></label>
     
        <input type="checkbox" name="checkbox-bridge-underline" id="checkbox-bridge-underline" class="custom">
        <label for="checkbox-bridge-underline">u</label>
      </fieldset>
    </div>
    <input type="hidden" name="format" value="joeboe" id="groups-new-format"/>
    <input type="submit" value="<?php if (!empty($group)) { echo 'Save Changes'; } else { echo 'Add Group'; } ?>" data-theme="b" data-role="button" />
  </form>
</div>
<?php include_once('../views/includes/footer.php'); ?>