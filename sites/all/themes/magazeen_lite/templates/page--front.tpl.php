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

  <div class="carousel-container">
    <?php include 'modules/carousel.php' ?>
  </div>
<!--
  <div class="front-container cloud-container">
 <?php include 'modules/tag_cloud.php' ?>
 </div>
 --!>


  <div class="front-container">
    <?php include 'modules/box_grid.php' ?>
  </div>

</div><!-- .container -->

<?php include 'footer.php' ?>
