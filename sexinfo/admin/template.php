<?php
/**********************************************************************//**\file
	Script Title / Purpose

	Description: ...
		Second line indented
*******************************************************************************/

	require('../core/sex-core.php');

	# Security function - MUST PASS TRUE FOR ADMIN PAGES!
	$security = new security(TRUE);

	$page = new admin_page('template.html');

	if($security->session_logged_in())
	{
		#code starts here
	}

	$page->output();
?>
