<?php
/**<div class="desc">**************************************************//**\file
	Administration Index

	Description:  Displays a summary of data for admins and authors (contingent
		on the user's current access level).
**</div>***********************************************************************/

	require('../core/sex-core.php');

	# Security function - MUST PASS TRUE FOR ADMIN PAGES!
	$security = new security(TRUE);

	# Initlialize page object
	$page = new admin_page('template.html');

	# Set page titles
	$page->title('Home - UCSB SexInfo Admin');

	# Initialize database connection
	$mysqli = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);

	# Outputs greeting and number of articles
	$page->add('<h1>Welcome to the SexInfo Admininistration Panel</h1>');
	$result = $mysqli->query("SELECT COUNT(*) FROM sex_content");
	$row = $result->fetch_row();
	$page->add('<p>There are currently '.$row[0].' pieces of content in the database.</p>');
	$result->close();

	# If user is an admin or webmaster:
	if($_SESSION['permission_level'] == 1 || $_SESSION['permission_level'] == 2)
	{
		# Show unpublished articles
		$result = $mysqli->query("
			SELECT content_id, content_title, content_added, content_published, content_slug, type_name_short, user_first_name, user_last_name
			FROM sex_content
			JOIN sex_type
				ON content_type = type_id
			LEFT JOIN sex_user
				ON content_author_id = user_id
			WHERE content_is_published = 0
			ORDER BY content_added DESC
		");
		
		$page->add('<h2>Content Awaiting Webmaster Review</h2>');
		if($result->num_rows != 0)
		{
			$page->add('
				<table class="index">
					<tr>
						<th>Title</th>
						<th>Type</th>
						<th>Date Added</th>
						<th>Author</th>
						<th>Delete</th>
					</tr>
			');
			while($row = $result->fetch_assoc())
			{
				$page->add('
					<tr>
						<td><a href="admin-content.php?action=edit&amp;slug='.$row['content_slug'].'">'.$row['content_title'].'</a></td>
						<td>'.$row['type_name_short'].'</td>
						<td>'.data::format_date($row['content_added'], 5).'</td>
						<td>'.$row['user_first_name'].' '.$row['user_last_name'].'</td>
						<td><a href="admin-delete.php?delete='.$row['content_id'].'">Delete</a></td>
					</tr>
				');
			}
			$page->add('</table>');
		}
		
		# If everything is published, show a happy message!
		else
		{
			$page->add('<div class="notice">All content is current!  Yay!  :D</div>');
		}
		
		# Show recently published articles
		$page->add('<h2>Recently Published</h2>');
		$result = $mysqli->query("
			SELECT content_id, content_title, content_added, content_published, content_slug, type_name_short, type_slug, user_first_name, user_last_name
			FROM sex_content
			LEFT JOIN sex_type
				ON content_type = type_id
			LEFT JOIN sex_user
				ON content_author_id = user_id
			WHERE content_is_published = 1
			ORDER BY content_published DESC
			LIMIT 15;
		");
		$page->add('
			<table class="index">
				<tr>
					<th>Title</th>
					<th>Type</th>
					<th>Date Published</th>
					<th>Author</th>
				</tr>
		');
		while($row = $result->fetch_assoc())
		{
			$page->add('
				<tr>
					<td><a href="/sexinfo/'.$row['type_slug'].'/'.$row['content_slug'].'">'.$row['content_title'].'</a></td>
					<td>'.$row['type_name_short'].'</td>
					<td>'.data::format_date($row['content_published'], 5).'</td>
					<td>'.$row['user_first_name'].' '.$row['user_last_name'].'</td>
				</tr>
			');
		}
		$page->add('</table>');

	}
	
	# Show own articles to editors, provide edit links
	elseif($_SESSION['permission_level'] == 3)
	{
		$page->add('<h2>Your Content</h2>');
		
		$result = $mysqli->query("
			SELECT `content_id`, `content_title`, `content_added`, `content_published`, `content_slug`, `type_name_short`, `type_name_plural`, `type_slug`, `content_is_published`
			FROM `sex_content`
			JOIN `sex_type`
				ON `content_type` = `type_id`
			WHERE `content_author_id` = {$_SESSION['user_id']}
			ORDER BY `content_added` DESC
		");
		
		# Generate table with list of own content
		if($result->num_rows != 0)
		{
			$page->add('<p>Note:  Clicking an unpublished item will pull it up in the content editor.  Clicking a published item will take you to it on the site.</p>');
			
			$page->add('
				<table class="index">
					<tr>
						<th>Title</th>
						<th>Type</th>
						<th>Date Added</th>
						<th>Date Published</th>
					</tr>
			');
			while($row = $result->fetch_assoc())
			{
				# If content is published, provide link to live version
				if($row['content_is_published'] == 1)
				{
					$page->add('
						<tr>
							<td><a href="/sexinfo/'.$row['type_slug'].'/'.$row['content_slug'].'">'.$row['content_title'].'</a></td>
							<td>'.$row['type_name_short'].'</td>
							<td>'.data::format_date($row['content_added'], 5).'</td>
							<td>'.data::format_date($row['content_published'], 5).'</td>
						</tr>
					');
				}
				
				# If content is NOT published, highlight in red, link to editor
				else
				{
					$page->add('
						<tr class="warning">
							<td><a href="admin-content.php?action=edit&amp;slug='.$row['content_slug'].'">'.$row['content_title'].'</a></td>
							<td>'.$row['type_name_short'].'</td>
							<td>'.data::format_date($row['content_added'], 5).'</td>
							<td>Unpublished</td>
						</tr>
					');

				}
			}
			$page->add('</table>');
		}
		
		# If user content count is zero, output help message w/ webmaster e-mails
		else
		{
			$page->add('<p>You haven\'t submitted anything yet.  Please contact your friendly webmaster if you\'re having problems.</p>');
			
			$result	= $mysqli->query("SELECT user_first_name, user_last_name, user_email FROM sex_user WHERE user_permission_level <= 2");
			
			$page->add('<ul>');
			while($row = $result->fetch_assoc())
			{
				$page->add('<li><a href="mailto:'.$row['user_email'].'">'.$row['user_first_name'].' '.$row['user_last_name'].'</a></li>');
			}
			$page->add('</ul>');
		}
	}
	
	# Show own articles to inactive users
	else
	{
	}

	# Close database connection
	$mysqli->close();

	# Output page
	$page->output();
?>
