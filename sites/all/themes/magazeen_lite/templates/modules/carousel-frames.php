<?php

// SLIDES/CAPTIONS FOR FRONT PAGE CAROUSEL MODULE

$jsonData = file_get_contents("carousel-links.json", true);
$phpArray = json_decode($jsonData, true);

?>

<?php foreach ($phpArray as $linkItem) {
    $imagePath = path_to_theme() . '/images/modules/' . $linkItem['image']; ?>
<div class="carousel-frame" style="background-position: center; background-size: cover; background-image: url('<?php print $imagePath ?>');">
  <div class="carousel-backdrop">
      <div class="carousel-caption">
        <a href="<?php print $linkItem['url'];?>"><h3><?php print $linkItem['title'];?></h3></a>
        <p><?php print $linkItem['description'];?><a href="<?php print $linkItem['url'];?>">Read More!</a></p>
        </a>
      </div>
  </div>
</div>
<!-- .carousel.frame -->
<?php } ?>
