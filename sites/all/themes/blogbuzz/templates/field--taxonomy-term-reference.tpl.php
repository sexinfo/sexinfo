<?php
// $Id: field--taxonomy-term-reference.tpl.php,v 1.1.2.1 2011/01/23 05:03:51 antsin Exp $
/**
 * @file
 * Output for taxonomy term reference fields.
 */
?>

<div class="<?php print $classes; ?>"<?php print $attributes; ?>>
  <?php if (!$label_hidden) : ?>
    <h3 class="field-label"<?php print $title_attributes; ?>><?php print $label ?>&nbsp;</h3>
  <?php endif; ?>
  <span class="field-items"<?php print $content_attributes; ?>>
    <?php $num_fields = count($items); ?>
    <?php $i = 1; ?>
    <?php foreach ($items as $delta => $item) : ?>
      <?php print render($item); ?><?php $i != $num_fields ? print ', ' : ''; ?>
      <?php $i++; ?>
    <?php endforeach; ?>
  </span>
</div>
