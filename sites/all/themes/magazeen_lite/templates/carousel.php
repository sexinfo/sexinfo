<div id="carousel">

<?php
$jsonData = file_get_contents("modules/carousel-links.json", true);
$phpArray = json_decode($jsonData, true);

foreach($phpArray as $val) {
	print $val['title'];
}

?>

</div>

<!-- "/sites/all/themes/magazeen_lite/images/modules/" -->