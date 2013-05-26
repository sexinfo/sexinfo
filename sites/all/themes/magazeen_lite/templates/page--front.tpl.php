<?php include 'header.php' ?>

<?php include 'navigation.php' ?>

<div class="container">
  <?php if ($breadcrumb): print $breadcrumb; endif; ?>

  <?php
    # "has been successfully updated", etc
    if ($show_messages && $messages): print $messages; endif;
  ?>

  <?php print render($page['help']); ?>
</div>


<div class="carousel-container">
  <?php include 'modules/carousel.php' ?>
</div>


<div class="container front-container">
  <div class="row">
    <?php include 'modules/box_grid.php' ?>
  </div>
</div>


<?php include 'footer.php' ?>
