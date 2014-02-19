<?php

// NAV LINKS FOR FRONT PAGE CAROUSEL MODULE
$jsonData = file_get_contents("carousel-links.json", true);
$phpArray = json_decode($jsonData, true);

?>
<ul>
  <div class="carousel-nav-left">
  <?php for ($i = 0; $i < 4; $i++) { $linkItem = $phpArray[$i];?>
    <li>
      <?php $currentClass = ($i == 0) ? 'current' : '' ?>
      <a class="<?php print $currentClass ?>" data-num="<?php echo $i+1; ?>" href="#">
      <img class="carousel-thumb" src="<?php print path_to_theme() . '/images/modules/' . $linkItem['thumbnail']; ?>" />
      <span class="carousel-link-text"><?php echo $linkItem['title']; ?></span>
      </a>
    </li>
  <?php } ?>
  </div><!-- .carousel-nav-left -->

  <div class="carousel-nav-right">
  <?php for ($i = 4; $i < count($phpArray); $i++) { $linkItem = $phpArray[$i];?>
    <li>
      <a href="#" data-num="<?php echo $i+1; ?>">
      <img class="carousel-thumb" src="<?php print path_to_theme() . '/images/modules/' . $linkItem['thumbnail']; ?>" />
        <?php echo $linkItem['title']; ?>
      </a>
    </li>
  <?php } ?>
  </div><!-- .carousel-nav-left -->
</ul>
