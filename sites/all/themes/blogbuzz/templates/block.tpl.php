<?php
// $Id: block.tpl.php,v 1.1.2.1 2011/01/23 05:03:51 antsin Exp $

/*
+----------------------------------------------------------------+
|   BlogBuzz for Dupal 7.x - Version 1.0                         |
|   Copyright (C) 2011 Antsin.com All Rights Reserved.           |
|   @license - GNU GENERAL PUBLIC LICENSE                        |
|----------------------------------------------------------------|
|   Theme Name: BlogBuzz                                         |
|   Description: BlogBuzz by Antsin                              |
|   Author: Antsin.com                                           |
|   Website: http://www.antsin.com/                              |
|----------------------------------------------------------------+
*/  
?>

<?php if ($block->region =='primary_menu' || $block->region =='content'): ?>
  <?php print $content; ?>
<?php else: ?>
  <div id="block-<?php print $block->module . '-' . $block->delta; ?>" class="<?php print $classes; ?>"<?php print $attributes; ?>>
    <div class="block-inner clearfix">
      <?php if ($block->subject): ?><div class="title"><h2><?php print $block->subject ?></h2></div><?php endif; ?>
      <div class="content"<?php print $content_attributes; ?>>
        <?php print $content; ?>
      </div>
    </div>
  </div> <!-- /block-inner, /block -->
<?php endif; ?>