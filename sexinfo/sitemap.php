<?php
/**********************************************************************//**\file
	Google Sitemap

	Description: Outputs a Google Sitemap with all content pages
*******************************************************************************/

	require_once('core/sex-core.php');

	echo '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

	$mysqli = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);

	# Get full listing
	$result = $mysqli->query("
		SELECT content_slug, type_slug, content_modified
		FROM sex_content
		JOIN sex_type
			ON content_type = type_id
		WHERE content_is_published = 1
		ORDER BY content_id ASC
	");

	while($row = $result->fetch_assoc())
	{
		# Output all data
		echo '<url><loc>http://www.soc.ucsb.edu/sexinfo/'.$row['type_slug'].'/'.$row['content_slug'].'</loc><lastmod>'.data::format_date($row['content_modified'],7).'</lastmod></url>';
	}

	echo '</urlset>';
?>
