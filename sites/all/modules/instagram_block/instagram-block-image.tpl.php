<?php
/**
 * @file
 * Default theme implementation of an Instagram image link.
 *
 * Available variables:
 * - post: The entire data array returned from the Instagram API request.
 * - href: The url to the Instagram post page.
 * - src: The source url to the instagram image.
 * - width: The display width of the image.
 * - height: The display height of the image.
 */
?>
<a class="group" target="blank_" rel="group1" href="<?php print $href ?>">
  <img style="float: left; margin: 0 5px 5px 0px; width: <?php print $width ?>px; height: <?php print $height ?>px;" src="<?php print $src ?>">
</a>
