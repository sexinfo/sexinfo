<?php
header('Content-type: text/plain');

require('../core/sex-core.php');

$mysqli = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);

//TODO: security!!!
# Security function - MUST PASS TRUE FOR ADMIN PAGES!
$security = new security(TRUE, TRUE);

if(isset($_POST['parent'])) $parent = (int) $_POST['parent'];
else $parent = 0;
if(isset($_POST['type'])) $type = (int) $_POST['type'];
else $type = 0;
if(isset($_POST['name'])) $name = trim($_POST['name']);
else $name = '';

if($name == '') {
	header('HTTP/1.1 400 Bad Request');
	echo "Name cannot be blank.";
}
else if($parent > 0) { // ignore type and base everything on parent
	$result = $mysqli->query("SELECT * FROM `sex_category` WHERE `category_id` = '$parent' LIMIT 1");
	
	if($row = $result->fetch_assoc()) { // verify existence of parent
		$type = $row['category_type'];
		$name = $mysqli->real_escape_string($name);
		$slug = data::slug_create($name, false);
		
		$mysqli->query(
			"INSERT INTO `sex_category`
			 (`category_title`, `category_parent`, `category_type`, `category_slug`)
			 VALUES ('$name', '$parent', '$type', '$slug')"
		);
		
		header('HTTP/1.1 200 OK');
		
		echo "Category '$name' created under '{$row['category_title']}' (type $type)! Now add some content to it!";
	}
	else {
		header('HTTP/1.1 400 Bad Request');
		echo "Parent not found. Reload the page and try again.";
	}
	
	$result->close();
}
else if($type > 0) { // create a new (parentless) category of this type
	$result = $mysqli->query("SELECT * FROM `sex_type` WHERE `type_id` = '$type' LIMIT 1");
	
	if($row = $result->fetch_assoc()) { // verify existence of type
		$name = $mysqli->real_escape_string($name);
		$slug = data::slug_create($name, false);
		
		$mysqli->query(
			"INSERT INTO `sex_category`
			 (`category_title`, `category_parent`, `category_type`, `category_slug`)
			 VALUES ('$name', NULL, '$type', '$slug')"
		);
		
		header('HTTP/1.1 200 OK');
		
		echo "Category '$name' created in type $type! Now add some content to it!";
	}
	else {
		header('HTTP/1.1 400 Bad Request');
		echo "Type not found. Reload the page and try again.";
	}
	
	$result->close();
}
else {
	header('HTTP/1.1 400 Bad Request');
	echo "A request to create a new category was made without any parent or type information." .
		" Either you're being very tricky or something is very broken!";
}

?>