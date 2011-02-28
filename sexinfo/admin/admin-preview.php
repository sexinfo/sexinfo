<?php
/**********************************************************************//**\file
	Preview Content

	Description: Allows editors to preview the page content as it will appear
		in the page before saving / publishing the document.
*******************************************************************************/

	require('../core/sex-core.php');

	# Security function - MUST PASS TRUE FOR ADMIN PAGES!
	$security = new security(TRUE);

	$page = new admin_page('template.html');

	if($security->session_logged_in())
	{
		$_POST[''];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php ?></title>
	<script language=javascript>
	function pie(window.name = "SexPreview";)
	</script>
</head>

<body onload="pie()">
</body>
</html>

<?php
	}

	$page->output();
?>