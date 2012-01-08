<?php
/**
 * @file
 * Default output for a FlexSlider node.
*/
?>
<div class="flexslider-content flexslider clearfix" id="flexslider-<?php print $id; ?>">
  <ul class="slides">
  <?php foreach($items as $item) : ?>
    <li><?php print render($item); ?>
      <?php if(!empty($item['#item']['title']) || !empty($item['#item']['alt'])) : ?>
       <div class="flex-caption"><strong><?php print $item['#item']['title']; ?></strong>&nbsp;<?php print $item['#item']['alt'];?></div>
      <?php endif; ?>
    </li>
  <?php endforeach; ?>
  </ul>
</div>