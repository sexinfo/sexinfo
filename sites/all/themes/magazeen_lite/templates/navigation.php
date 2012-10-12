<?php if (!empty($main_menu)): ?>
  <div id="navigation">
    <div class="container clearfix">

      <?php print theme('links', array('links' => $main_menu, 'attributes' => array('id' => 'main-menu', 'class' => array('links', 'main-menu')))); ?>

    </div>
  </div><!-- #navigation -->
<?php endif;?>
