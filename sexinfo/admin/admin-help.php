<?php
/**********************************************************************//**\file
	Script Title / Purpose

	Description:
*******************************************************************************/

	require('../core/sex-core.php');

	# Security function - MUST PASS TRUE FOR ADMIN PAGES!
	$security = new security(TRUE);

	$page = new admin_page('template.html');
	$page->title('Help');
	$page->add('<h1>Getting Help</h1>');
	$page->add(file_get_contents('help.html'));

	$page->output();
?>
