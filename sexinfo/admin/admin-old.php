<?php
/**********************************************************************//**\file
	Old Pages

	Description: List old pages so authors can figure out what they'd like to
		write about.
*******************************************************************************/

	require('../core/sex-core.php');

	# Security function - MUST PASS TRUE FOR ADMIN PAGES!
	$security = new security(TRUE);

	$page = new admin_page('template.html');
	$page->title('Old Pages');

	if($security->session_logged_in())
	{
		$page->add('<h1>Old Pages</h1>');
		$page->add('<p>Old pages are those that have not been modified in over 2.5 years.  An old page isn\'t necessarily out of date, but age can give you a rough idea of what may need to be updated.</p>');

		$mysqli = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);

		$result = $mysqli->query("
			SELECT content_id, content_title, content_slug, content_modified, type_slug
			FROM sex_content
			JOIN sex_type ON content_type = type_id
			WHERE content_modified <= ".(time()-78840000)."
			ORDER BY content_modified DESC
		");

		$page->add('<table>');
		while($row = $result->fetch_assoc())
		{
			$page->add('<tr><td><a href="/sexinfo/'.$row['type_slug'].'/'.$row['content_slug'].'">'.$row['content_title'].'</td><td>'.data::format_date($row['content_modified'], 1).'</td></tr>');
		}
		$page->add('</table>');

		$mysqli->close();
	}

	$page->output();
?>
