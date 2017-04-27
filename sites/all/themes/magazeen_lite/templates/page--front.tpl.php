<?php include 'header.php' ?>

<div class="container">
  <?php
    # "has been successfully updated", etc
    if ($show_messages && $messages): print $messages; endif;
  ?>

  <?php print render($page['help']); ?>

  <div>
	<?php include 'mission.php' ?>
  </div>

  <?php include 'carousel.php' ?>
</div>

<?php include 'footer.php' ?>
