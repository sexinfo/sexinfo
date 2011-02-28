<?php
/**********************************************************************//**\file
	Core (Controller Classes)

	Description: The core provides a means to add all of the available classes
		to your script with a single require() at the top.  All php files
		included by the core contain object definitions only.  Including
		core.php in a script should not result in any output whatsoever, until
		a particular class is invoked by the current script.
*******************************************************************************/

	# Default classes
	require_once('sex-autorun.php');
	require_once('sex-config.php');
	require_once('sex-content.php');
	require_once('sex-data.php');
	require_once('sex-database.php');
	require_once('sex-error.php');
	require_once('sex-navigation.php');
	require_once('sex-page.php');
	require_once('sex-security.php');
	require_once('sex-navigation-tab.php');
	
	# Extended classes
	require_once('sex-admin.php');

	# Data classes
	#require_once('sex-data-revision.php');
	#require_once('sex-data-content.php');
	require_once('sex-data-bug.php');
	#require_once('sex-data-message.php');

	# Debug
	require_once('sex-debug.php');
?>
