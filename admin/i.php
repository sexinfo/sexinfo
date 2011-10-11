<?php
	if($_SERVER['SERVER_NAME'] != 'www.soc.ucsb.edu')
		phpinfo();
	else {
		header("Location: http://www.soc.ucsb.edu/sexinfo/");
		exit;
	}
	
/**********************************************************************//**\file
	phpinfo()

	Description: Provides information about the current PHP configuration and 
		server environment.
*******************************************************************************/

	//require('../core/sex-core.php');

	# Security function - MUST PASS TRUE FOR ADMIN PAGES!
	//$security = new security(TRUE);
	

?>
