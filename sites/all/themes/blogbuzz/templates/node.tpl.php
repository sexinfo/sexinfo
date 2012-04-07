<?php
// $Id: node.tpl.php,v 1.1.2.1 2011/01/23 05:03:51 antsin Exp $

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

<div class="<?php print $classes; ?>">
  <div class="node-inner clearfix">
    <?php print render($title_prefix); ?>
    <?php if (!$page): ?>
      <h2<?php print $title_attributes; ?>><a href="<?php print $node_url; ?>"><?php print $title; ?></a></h2>
    <?php endif; ?>
    <?php print render($title_suffix); ?>

    <div class="meta">
      <?php if ($display_submitted): ?>
        <?php print t('Published by '); ?><?php print $name.t(' on ').$date; ?>
	  <?php endif; ?>
      <?php if (!empty($content['field_tags'])): ?>
        <?php print ' in '.render($content['field_tags']);  ?>
      <?php endif; ?>
    </div>

    <div class="content clearfix">
    <?php
      hide($content['comments']);
      hide($content['links']);
	  hide($content['field_tags']);
      print render($content);
    ?>
    </div>

    <?php if ($content['links']): ?>
      <div class="extra-links">
	    <?php if (!$page): ?>
	      <div class="read-more"><?php print l(t('Read more'), 'node/' . $nid, array('attributes' => array('class' => t('node-readmore-link')))); ?></div>
		<?php endif; ?>
        <?php print render($content['links']); ?>
      </div>
	<?php endif; ?>

    <?php print render($content['comments']); ?>
  </div>
</div> <!-- /node-inner, /node -->