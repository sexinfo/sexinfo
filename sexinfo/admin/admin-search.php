<?php
/**********************************************************************//**\file
	Admin Search

	Description: Allows authors to find and edit articles in the database.
*******************************************************************************/

	require('../core/sex-core.php');

	# Security function - MUST PASS TRUE FOR ADMIN PAGES!
	$security = new security(TRUE);

	$page = new admin_page('template.html');

	$page->title('Search');
	$page->add('<h1>Search for Content</h1>
		<div id="search">');
	
	$search_term = '';
	if(isset($_GET['q']))
		$search_term = $_GET['q'];
		
	
	$page->add('
		<form method="get" action="admin-search.php">
			<div id="search-line"><input id="search-field" name="q" type="text" value="'.$search_term.'"/><input id="search-button" type="submit" value="Search" /></div>
		</form>
	');
	
	if(isset($_GET['q']) && $search_term != '')
	{
		$mysqli = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);
		if(
			$result = $mysqli->query("
				SELECT content_id, content_slug, content_title, type_name_short, type_slug
				FROM sex_content
				JOIN sex_type
					ON content_type = type_id
				WHERE content_id = '$search_term' OR content_title LIKE '%$search_term%' OR content_body LIKE '%$search_term%' OR content_abstract LIKE '%$search_term%'
			")
		)
		{
			$page->add('
			<table>
				<thead>
					<colgroup>
						<col class="id" />
						<col class="title" />
						<col class="type" />
						<col class="view" />
						<col class="edit" />
					</colgroup>
				</thead>
				<tr>
					<th>#</th>
					<th>Title</th>
					<th>Type</th>
					<th>View</th>
					<th>Edit</th>
				</tr>');
			while($row = $result->fetch_assoc())
			{
				$page->add('
				<tr>
					<td>'.$row['content_id'].'</td>
					<td>'.$row['content_title'].'</td>
					<td>'.$row['type_name_short'].'</td>
					<td><a href="/sexinfo/'.$row['type_slug'].'/'.$row['content_slug'].'">View</a></td>
					<td><a href="/sexinfo/admin/admin-content.php?action=edit&amp;slug='.$row['content_slug'].'">Edit</a></td>
				</tr>');
			}
			$page->add('</table>');
		}
		else
		{
			$page->add('<p>No results were found for your search terms.</p>');
		}
	}
	else
	{
		$page->add('<p class="tips">Search tips:  You can search for content based on its id#, title, abstract, or content.</p>');
	}
	
	$page->add('</div>');

	$page->output();
?>
