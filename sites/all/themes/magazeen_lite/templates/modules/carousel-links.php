<?php

// NAV LINKS FOR FRONT PAGE CAROUSEL MODULE
$jsonData = file_get_contents("carousel-links.json", true);
$phpArray = json_decode($jsonData, true);

?>
<ul>
  <div class="carousel-nav-left">
  <?php for ($i = 0; $i < 4; $i++) { $linkItem = $phpArray[$i];?>
    <li>
      <img class="carousel-thumb" src="<?php print path_to_theme() . '/images/modules/' . $linkItem['image']; ?>" />
      <a class="current" data-num="<?php echo $i+1; ?>" href="#">
        <?php echo $linkItem['title']; ?>
      </a>
    </li>
  <?php } ?>
  </div><!-- .carousel-nav-left -->

  <div class="carousel-nav-right">
  <?php for ($i = 4; $i < count($phpArray); $i++) { $linkItem = $phpArray[$i];?>
    <li>
      <img class="carousel-thumb" src="<?php print path_to_theme() . '/images/modules/' . $linkItem['image']; ?>" />
      <a class="current" data-num="<?php echo $i+1; ?>" href="#">
        <?php echo $linkItem['title']; ?>
      </a>
    </li>
  <?php } ?>
  </div><!-- .carousel-nav-left -->
</ul>
