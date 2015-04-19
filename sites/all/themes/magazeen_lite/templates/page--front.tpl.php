<?php include 'header.php' ?>

<?php include 'navigation.php' ?>


<div class="container">
  <?php
    # "has been successfully updated", etc
    if ($show_messages && $messages): print $messages; endif;
  ?>

  <?php print render($page['help']); ?>

  <div class="front-container">
    <h1>Welcome to SexInfoOnline!</h1>
    <p>SexInfo Online is a website devoted to comprehensive sex education based on the best research we have to date. Our primary goal is to ensure that people around the world have access to useful and accurate information about all aspects of human sexuality.</p>

    <p>The site is maintained by students from the University of California, Santa Barbara who have studied advanced topics in human sexuality. You can read our mission statement here.</p>
  </div>

  <div class="front-container cloud-container">
    <?php include 'modules/tag_cloud.php' ?>
  </div>

  <div class="carousel-container">
    <?php include 'modules/carousel.php' ?>
  </div>

  <div class="front-container">
    <?php include 'modules/box_grid.php' ?>
  </div>

</div><!-- .container -->

<?php include 'footer.php' ?>
