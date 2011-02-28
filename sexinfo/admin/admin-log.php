<?php
/**********************************************************************//**\file
	Error Log

	Description: Displays errors that have been collected by the error class.
*******************************************************************************/

	require('../core/sex-core.php');

	# Security function - MUST PASS TRUE FOR ADMIN PAGES!
	$security = new security(TRUE);

	if($security->session_logged_in() && $security->permission_level() == 1)
	{
		$page = new admin_page('template.html');
		$page->add('<h1>Error Log</h1>');
		$page->add('<p>Last 30 Errors:</p>');

		$config = config::database();
		$mysqli = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);

		$result = $mysqli->query("
			SELECT error_id, error_time, error_type, error_script, error_line, error_body
			FROM sex_error
			ORDER BY error_time DESC
			LIMIT 30
		");

		$page->add('<table id="log" style="width: 100%;">');
		while($row = $result->fetch_assoc())
		{
			$page->add('<tr class="head"><td>#'.$row['error_id'].'</td><td>'.date('Y-m-d H:i:s', $row['error_time']).'</td><td>Type '.$row['error_type'].'</td><td>Line '.$row['error_line'].'</td><td>'.$row['error_script'].'</td></tr>');
			$page->add('<tr><td colspan="5" class="error"><div class="error">'.$row['error_body'].'</div></td>');
		}
		$page->add('</table>');

		$page->output();
	}
	else
	{
		$security->redirect();
	}
?>
