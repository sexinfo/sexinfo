<?php

// SLIDES/CAPTIONS FOR FRONT PAGE CAROUSEL MODULE

$jsonData = file_get_contents("carousel-links.json", true);
$phpArray = json_decode($jsonData, true);

?>

<?php foreach ($phpArray as $linkItem) { ?>
<div class="carousel-frame">
  <img src="<?php print path_to_theme() . '/images/modules/' . $linkItem['image']; ?>" alt="<?php print $linkItem['alt-text'];?>" />
  <div class="carousel-caption">
    <a href="<?php print $linkItem['url'];?>"><h3><?php print $linkItem['title'];?></h3></a>
    <p><?php print $linkItem['description'];?>
    <a href="<?php print $linkItem['url'];?>">Read More!</p>
    </a>
  </div>
</div>
<!-- .carousel.frame -->
<?php } ?>
