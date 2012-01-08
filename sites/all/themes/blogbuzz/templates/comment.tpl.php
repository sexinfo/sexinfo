<?php
// $Id: comment.tpl.php,v 1.1.2.1 2011/01/23 05:03:51 antsin Exp $

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

<div class="<?php print $classes; ?>"><div class="comment-inner clearfix">
  <?php print $picture;?>
  <div class="submitted"><span class="author"><?php print $author; ?></span><span><?php print date("D, d/m/y", $comment->created);?></span></div>
  <div class="content">
    <?php if ($title): ?>
      <h3 class="title" <?php print $title_attributes; ?>><?php print $title; ?></h3> 
    <?php endif; ?>
    <img src="<?php global $base_url; print $base_url .'/' . $directory; ?>/images/comment_arrow.png" class="comment_arrow" />
    <?php
      hide($content['links']);
      print render($content);
    ?>
    <?php if ($signature): ?>
      <div class="user-signature"><?php print $signature; ?></div>
    <?php endif; ?> 
  </div>
  <div class="links"><?php print render($content['links']) ?></div>
</div></div> <!-- /comment-inner, /comment -->