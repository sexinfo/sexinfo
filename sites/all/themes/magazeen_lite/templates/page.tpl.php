<?php include 'header.php' ?>

<?php include 'navigation.php' ?>

<?php $mainClass = (isset($node)) ? 'main-'.$node->nid : " "; ?>

<div id="main" class="clearfix <?php echo $mainClass ?>">
  <div class="container clearfix">
    <div class="main">
      <?php if ($breadcrumb): print $breadcrumb; endif; ?>

      <?php
        # View and Edit buttons
        if ($tabs): print '<div id="tabs-wrapper" class="clear-block">' . render($tabs) .'</div>'; endif;
      ?>

      <?php
        # "has been successfully updated, etc"
        if ($show_messages && $messages): print $messages; endif;
      ?>

      <?php print render($page['help']); ?>

      <div class="clearfix">
        <?php print render($page['content']); ?>
      </div>

      <div id="sidebar" class="right">
        <?php print render($page['sidebar_second']); ?>
      </div><!-- #sidebar.right -->

    </div><!-- .main -->
  </div><!-- .container.clearfix -->
</div><!-- #main.clearfix -->

<?php include 'footer.php' ?>
