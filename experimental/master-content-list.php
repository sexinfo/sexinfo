<?php
/**********************************************************************//**\file
	Master Content Listing

	Description: Outputs a master list of all content in the database
*******************************************************************************/

	require_once('../core/sex-core.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
	<title>SexInfo - Master CONTENT List</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<style type="text/css">
		body { margin: 2em; font-family: verdana; font-size: 1.2em; line-height: 1.3em; }
		a { color: black ; text-decoration: none; border-bottom: 1px dotted black; }
		.cat a, span.cat{ background-color: #afa; }
		.article a, span.article { background-color: #aaf; }
		.question a, span.question { background-color: #ffa; }
		.other a, span.other { background-color: #aaa; }
		.error a, span.error { background-color: #faa; }
	</style>
</head>

<body>
	<h1>Legend</h1>
	<p>Entries with <span class="cat">green backgrounds</span> are categories (organizational structure in the database); all others are content pages (visible on the site somewhere).</p>
	<p><span class="article">Blue entries</span> are articles, <span class="question">yellow entries</span> are Q&amp;A's, <span class="other">grey entries</span> are neither, and <span class="error">red entries</span> are broken.</p>
	<p>All top-level and sub-level article categories should have a page attached to them.  Only top-level Q&amp;A categories will have a page attached which shows a list of all subcategories and questions; individual subcategories for Q&amp;A's do not have pages.</p>
	<p>Some categories appear twice because they have a category for Articles and for Q&amp;A's.  A lower id# (following in parens) usually indicates an Article category.</p>
	<h1>Uncategorized Content</h1>
<?php

	# Initialize DB connection
	$mysqli = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);

	$result3 = $mysqli->query("
		SELECT content_id, content_title, content_slug, type_name_short
		FROM sex_content
		JOIN sex_type
			ON content_type = type_id
		LEFT JOIN sex_bridge
			ON content_id = bridge_content_id
		WHERE bridge_category_id IS NULL
		ORDER BY content_title ASC
	");

	echo '<ul>';
	while($row3 = $result3->fetch_assoc())
	{
		if($row3['type_name_short'] == 'Article')
			echo '<li class="article">';
		elseif($row3['type_name_short'] == 'Q&amp;A')
			echo '<li class="question">';
		else
			echo '<li class="other">';
		echo '<a href="http://www.soc.ucsb.edu/sexinfo/category/'.$row3['content_slug'].'">'.$row3['content_title'].'</a> ('.$row3['type_name_short'].' id'.$row3['content_id'].')</li>';
	}
	echo '</ul>';
?>
	<h1>Categorized Content</h1>
<?php
	# Get full listing
	$result = $mysqli->query("
		SELECT category_id, category_title, category_slug, type_name_short
		FROM sex_category
		JOIN sex_type
			ON category_type = type_id
		WHERE category_parent IS NULL
		ORDER BY category_title ASC
	");

	echo '<ul>';

	while($row = $result->fetch_assoc())
	{
		$current_categories = array();

		$category_id  = $row['category_id'];

		echo '<br /><li class="cat"><a href="http://www.soc.ucsb.edu/sexinfo/category/'.$row['category_slug'].'">'.$row['category_title'].'</a> ('.$row['type_name_short'].', id'.$row['category_id'].')';

		get_category_data($row['category_id']);

		echo '</li>';
	}

	echo '</ul>';
?>
</body>
</html>
<?php

	# Retrieve category data from DB
	function get_category_data($id)
	{
		GLOBAL $mysqli;
		GLOBAL $current_categories;
		
		$result = $mysqli->query("
			SELECT category_id, category_title, category_parent, category_slug, type_name_short
			FROM sex_category
			JOIN sex_type
				ON category_type = type_id
			WHERE category_parent = $id
		");

		echo '<ul>';
		while($row = $result->fetch_assoc())
		{
			echo '<li class="cat"><a href="http://www.soc.ucsb.edu/sexinfo/category/'.$row['category_slug'].'">'.$row['category_title'].'</a> ('.$row['type_name_short'].' id'.$row['category_id'].')</li>';
			get_category_data($row['category_id']);
		}
		echo '</ul>';

		$result = $mysqli->query("
			SELECT content_id, content_title, content_slug, type_name_short
			FROM sex_content
			JOIN sex_type
				ON content_type = type_id
			JOIN sex_bridge
				ON content_id = bridge_content_id
			WHERE bridge_category_id = $id
			ORDER BY content_title ASC
		");

		echo '<ul>';
		while($row = $result->fetch_assoc())
		{
			if($row['type_name_short'] == 'Article')
				echo '<li class="article">';
			elseif($row['type_name_short'] == 'Q&amp;A')
				echo '<li class="question">';
			else
				echo '<li class="other">';
			echo '<a href="http://www.soc.ucsb.edu/sexinfo/category/'.$row['content_slug'].'">'.$row['content_title'].'</a> ('.$row['type_name_short'].' id'.$row['content_id'].')</li>';
		}
		echo '</ul>';
	}
?>