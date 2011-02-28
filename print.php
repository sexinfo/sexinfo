<?php
/**********************************************************************//**\file
	SexInfo Homepage Redirector

	Description:  Print.php is a deprecated feature that provided a non-styled
		page for print.  /theme/print.css is now used instead, and print.php
		redirects to the homepage.
*******************************************************************************/

	header('HTTP/1.1 301 Permanent Redirect');
	header('Location: /sexinfo/?'.$_SERVER['QUERY_STRING']);
?>