<?php include 'header.php' ?>

<?php include 'navigation.php' ?>

<h3>Recent Changes:</h3>
<ul>
<?php $query = db_select('node', 'n')->fields('n', array('nid'))->range(0, 10)->orderBy('changed', 'DESC');

      $nodeIDs = $query->execute()->fetchCol();
      $type = 'node';
      $conditions = array();
      $resetCache = FALSE;

      $nodes = entity_load($type, $nodeIDs, $conditions, $resetCache);
      foreach($nodes as $node) {
        echo '<li><a href="node/'; echo $node->nid; echo '">'; echo $node->title; echo '</a></li>';
      }
      ?>
</ul>
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
