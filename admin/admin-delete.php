<?php
/**********************************************************************//**\file
	Tools

	Description: Provides links to and descriptions of various admin tools
*******************************************************************************/

	require('../core/sex-core.php');

	# Security function - MUST PASS TRUE FOR ADMIN PAGES!
	$security = new security(TRUE);

	$page = new admin_page('template.html');
	$page->add('<h1>Delete Content</h1>');
	$page->title('Delete Content');

	if($security->session_logged_in() && $security->permission_level() < 3)
	{
		
		$db = new database();

		if(isset($_GET['delete']) && is_numeric($_GET['delete']))
		{
			$page->add('<p class="warning" style="font-weight: bold;">All deletions are permanent. Please verify that this information matches the page you intend to delete.</p>');

			$item = intval($_GET['delete']);

			$db->query("SELECT content_id, content_title, content_slug, content_added, content_published, content_modified, content_is_published FROM sex_content WHERE content_id = $item");
			$data = $db->result();

			$page->add('
			<h2>Metadata</h2>
			<table>
				<tr><th>Column</th><th>Value</th></tr>
				<tr><td>content_id</td><td>'.$data['content_id'].'</td></tr>
				<tr><td>content_title</td><td>'.$data['content_title'].'</td></tr>
				<tr><td>content_slug</td><td>'.$data['content_slug'].'</td></tr>
				<tr><td>content_added</td><td>'.data::format_date($data['content_added'], 3).'</td></tr>
				<tr><td>content_published</td><td>'.data::format_date($data['content_published'], 3).'</td></tr>
				<tr><td>content_modified</td><td>'.data::format_date($data['content_modified'], 3).'</td></tr>
			</table>');

			if($data['content_is_published'] == 1)
			{
				$page->add('<p>You can view the page here: <a href=""></a>');
			}
			else
			{
				$page->add('<p>This page has not been published.  <a href="admin-content.php?action=edit&amp;slug='.$data['content_slug'].'">View it in the editor.</a></p>');
			}

			$page->add('<form action="admin-delete.php" method="post"><p style="text-align: center;"><input type="hidden" name="delete" value="'.$data['content_id'].'" /><input type="submit" value="Delete This Entry" /></p></form>');
			
		}
		elseif(isset($_POST['delete']) && is_numeric($_POST['delete']))
		{
			#$page->add($_SERVER['HTTP_REFERER']);
			$item = intval($_POST['delete']);

			if($db->query("DELETE FROM sex_content WHERE content_id = $item LIMIT 1"))
			{
				$page->add('<p>Entry #'.$item.' was successfully removed from the database.</p>');
			}
			else
			{
				$page->add('<p>There was a problem deleting entry #'.$item.'.  It may have already been removed.</p>');
			}
		}
		else
		{
			$page->add('<p>This is a placeholder for a deletion queue (or something didn\'t work correctly).  Return to the <a href=".">dashboard</a>.</p>');
		}
	}
	else
	{
		$page->add('<p>You do not have permission to access this page.</p>');
	}

	$page->output();
?>
