<?php
/**********************************************************************//**\file
	Zap!

	Description: Shows articles flagged by Sexperts for review.
*******************************************************************************/

	require('../core/sex-core.php');

	# Security function - MUST PASS TRUE FOR ADMIN PAGES!
	$security = new security(TRUE);

	$page = new admin_page('template.html');

	if($security->session_logged_in())
	{
		$page->add('<h1>Zap!</h1>');
		$page->add('<p>Zapping content allows Sexperts to flag pages that have problems, such as inaccurate information, poor formatting, inappropciate language, or other wonky stuff.  Zapped pages are probably good candidates for rewrites.  At the very least, someone should take a look at them to see what\'s wrong.  Editing an article will clear its Zap! status.');

		$page->add('<p>Zapping has not yet been fully implemented!</p>');
	}

	$page->output();
?>
