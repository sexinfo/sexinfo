<?php
/**********************************************************************//**\file
	Tools

	Description: Provides links to and descriptions of various admin tools
*******************************************************************************/

	require('../core/sex-core.php');

	# Security function - MUST PASS TRUE FOR ADMIN PAGES!
	$security = new security(TRUE);

	$page = new admin_page('template.html');

	if($security->session_logged_in())
	{
		if($security->permission_level() > 2)
		{
			$security->redirect('.');
		}

		$page->add('<h1>Tools</h1>');
		$page->add('<p>Tools marked with (Admin) are specific to webmasters.  Other tools have been mvoed to this page to reduce the number of tabs (above).</p>');
		$page->add('
			<ul id="toolbox">
			<li>
			<a href="admin-image.php"><img src="invisible.gif" /></a>
			<h2>Image Uploader</h2>
			<p>Provides image upload functionality, displays latest images, and provides img tag code for insertion into content.</p>
			</li>
			
			<li>
			<a href="admin-old.php"><img src="invisible.gif" /></a>
			<h2>Old Pages</h2>
			<p>Displays pages that have not been updated in more than 2.5 years.</p>
			</li>

			<li>
			<a href="admin-templates.php"><img src="invisible.gif" /></a>
			<h2>Templates</h2>
			<p>A list of Word templates that can be used for canned responses, etc.</p>
			</li>

			<li>
			<a href="admin-config.php"><img src="invisible.gif" /></a>
			<h2>Config Editor (Admin)</h2>
			<p>The Configuration Editor allows admins to adjust special settings, such as the number of incoming questions per week.</p>
			</li>

			<li>
			<a href="admin-impersonate.php"><img src="invisible.gif" /></a>
			<h2>Impersonate (Admin)</h2>
			<p>Allows a superuser to impersonate another user and adopt his/her permission set for testing and debugging.</p>
			</li>

			</ul>
			<div style="clear: both;"></div>
		');
	}

	$page->output();
?>
