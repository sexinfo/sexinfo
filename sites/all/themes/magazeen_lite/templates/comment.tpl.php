<div class="<?php print $classes .' '. $zebra; ?> clearfix">
  <div class="comment-inner">
    <?php print render($title_prefix); ?>
    <?php if ($title): ?>
      <h3 class="title"><?php print render($title) ?></h3>
    <?php endif; ?>
    <?php print render($title_suffix); ?>
  
    <?php if ($new): ?>
      <span class="new"><?php print drupal_ucfirst($new) ?></span>
    <?php endif; ?>
  
    <?php print render($picture); ?>

    <div class="submitted">
      <?php print $submitted; ?>
    </div>

    <div class="content">
      <?php hide($content['links']); print render($content); ?>
      <?php if ($signature): ?>
        <div class="user-signature clearfix">
          <?php print $signature ?>
        </div>
      <?php endif; ?>
    </div>
    <?php if ($content['links']): ?>
      <div class="links">
        <?php print render($content['links']) ?>
      </div>
    <?php endif; ?>
  </div>
</div>