<?php include_once('../views/includes/header.php'); ?>
<div data-role="content">

  <ul id="tags-list-tags" data-role="listview" data-divider-theme="a" data-inset="true">
    <li data-role="list-divider" role="heading">
      Tags
    </li>
    <!-- <li data-theme="c" data-icon="plus"><a href="/songs/new">New Tag</a></li> -->
    <?php
      if (count($tags) == 0) {
        echo "<li>There are currently no tags.</li>";
      }
      foreach ($tags as $tag) {
      ?>
        <li class="listview" data-title="<?= $tag->name ?>">
          <a href="/tags/<?= $tag->url ?>" data-transition="slide">
            <label class="tag-label">
              <h2 class="listview-heading"><?= $tag->name ?></h2>
            </label>
          </a>
        </li>
      <?php
      }
    ?>
  </ul><!--list view-->
</div>

<?php include_once('../views/includes/footer.php'); ?>