<?php
header('Content-type: text/plain');

require('../core/sex-core.php');

$mysqli = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);

$global_time = time();

//TODO: security!!!
# Security function - MUST PASS TRUE FOR ADMIN PAGES!
$security = new security(TRUE, TRUE);

if(isset($_POST['catID'])) $catID = (int) $_POST['catID'];
else $catID = 0;

$result = $mysqli->query("SELECT * FROM `sex_category` WHERE `category_id` = '$catID' LIMIT 1");

if($row = $result->fetch_assoc()) {
  if(is_null($row['category_content_id'])) {
    $name = $row['category_title'];
    $slug = data::slug_create($name);
    $type = 3; // 3 is for menu pages... right?
    
    $mysqli->query("
		INSERT INTO `sex_content`
		SET `content_slug` = '$slug',
			`content_title` = '$name',
			`content_body` = '',
			`content_added` = $global_time,
			`content_modified` = $global_time,
			`content_type` = '$type',
			`content_is_published` = 0
	");
    
    $newres = $mysqli->query("SELECT `content_id` FROM `sex_content` WHERE `content_slug` = '$slug' LIMIT 1");
    if($newpage = $newres->fetch_row()) {
      $newid = $newpage[0];
    
      $mysqli->query("UPDATE `sex_category` SET `category_content_id`='$newid' WHERE `category_id`='$catID'");
    
      header('HTTP/1.1 200 OK');
      echo $newid;
    }  
    else {
      header('HTTP/1.1 500 Internal Error');
      echo "Error creating new content page.";
    }
    $newres->close();
  }
  else {
    header('HTTP/1.1 400 Bad Request');
    echo "That category already has associated content. Please refresh to ensure you're up to date.";
  }
}
else {
  header('HTTP/1.1 400 Bad Request');
  echo "Category not found. Please refresh to ensure you're up to date.";
}

$result->close();

?>