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
            echo "<li data-theme='c' data-id='{$member->id}'>";
            echo "<a href='#'>{$member->first_name} {$member->last_name}</a>";
            echo "<a href='#' class='remove-member'>Remove Member</a>";
            echo "<input type='hidden' name='members[]' value='{$member->id}' />";
            echo "</li>";
          }
        } else {
          echo "<li data-theme='c' data-id='{$user->id}'>{$user->first_name} {$user->last_name}<input type='hidden' name='members[]' value='{$user->id}' /></li>";
        }
      ?>
    </ul>
    <div id="groups-new-member-choices-box" style="padding: 15px 0;"> 
      <ul id="groups-new-member-choices" data-filter-reveal="true" data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="Type a person's name..." data-filter-theme="d">
      </ul>
    </div>
    <input type="submit" value="<?php if (!empty($group)) { echo 'Save Changes'; } else { echo 'Add Group'; } ?>" data-theme="b" data-role="button" />
  </form>
  <script>
    $("#groups-new-member-choices").on("listviewbeforefilter", function ( e, data ) {
      var $ul = $( this ),
          $input = $( data.input ),
          value = $input.val(),
          html = "";
      $ul.html( "" );
      if ( value && value.length > 2 ) {
        $ul.html( "<li><div class='ui-loader'><span class='ui-icon ui-icon-loading'></span></div></li>" );
        $ul.listview( "refresh" );
        $.ajax({
          url: "/search/users/" + $input.val(),
          dataType: "json"
        })
        .then( function ( response ) {
          $.each( response, function ( i, val ) {
            if ($('#groups-new-members li[data-id=' + val.id + ']').length === 0) {
              html += "<li><a href='#' data-id='" + val.id + "'>" + val.first_name + ' ' + val.last_name + "</a></li>";
            }
          });
          $ul.html( html );
          $ul.listview( "refresh" );
          $ul.trigger( "updatelayout");
        });
      }
    });
    $(document).on('click', '#groups-new-member-choices a[data-id]', function (e) {
      if ($('#groups-new-members li[data-id=' + $(this).attr('data-id') + ']').length === 0) {
        $('#groups-new-members').append("<li data-theme='c' data-id='" + $(this).attr('data-id') + "'>" + $(this).text() + "<input type='hidden' name='members[]' value='" + $(this).attr('data-id') + "' /></li>")
        $('#groups-new-members').listview('refresh');
        $('#groups-new-member-choices-box .ui-input-clear').click();
        $('#groups-new-member-choices').html('');
      }
    });
    $(document).on('click', '.remove-member', function (e) {
      $(this).closest('li').remove();
    });
  </script>
</div>
<?php include_once('../views/includes/footer.php'); ?>