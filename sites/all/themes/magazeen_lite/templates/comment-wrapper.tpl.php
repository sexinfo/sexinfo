<?php if ($content) : ?>
  <h2 class="comments-header">
    <?php print render($title_prefix); ?>
    <?php print $node->comment_count ?> <?php print t('Comments'); ?>
    <?php print render($title_suffix); ?>
  </h2>
  <div id= "comments" class="comment block">
    <?php print render($content['comments']); ?>
    <?php if ($content['comment_form']): ?>
      <?php print render($content['comment_form']); ?>
    <?php endif; ?>
  </div>
<?php endif; ?><!-- /comments -->