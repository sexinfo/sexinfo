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
	$page->title('Settings');

	if($security->session_logged_in())
	{
		$mysqli = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);
		
		$page->add('<h1>Personal Account Settings</h1>');
		if(isset($_POST['save']))
		{
			$user['oldpass'] = $_POST['oldpass'];
			$user['newpass1'] = $_POST['newpass1'];
			$user['newpass2'] = $_POST['newpass2'];
			
			$form_data_valid = TRUE;
			$errors = array();
			
			# Verify old password
			$result = $mysqli->query("SELECT user_password FROM sex_user WHERE user_id = {$_SESSION['user_id']}");
			$row = $result->fetch_assoc();
			if($row['user_password'] != data::password_hash($user['oldpass']))
			{
				$form_data_valid = FALSE;
				$errors[] = 'Your current password was invalid.';
			}
			
			# Validate new password
			if($user['newpass1'] != $user['newpass2'])
			{
				$form_data_valid = FALSE;
				$errors[] = 'Your new passwords don\'t match.';
			}
			if(strlen($user['newpass1']) < 8)
			{
				$form_data_valid = FALSE;
				$errors[] = 'Your new password is too short.';
			}
			
			# Update password if form is valid
			if($form_data_valid)
			{
				$mysqli->query("UPDATE sex_user SET user_password = '".data::password_hash($user['newpass1'])."' WHERE user_id = {$_SESSION['user_id']} LIMIT 1");
				$page->add('<div class="notice">Your password was updated successfully.</div>');
			}
			# Dump error message if it isn't
			else
			{
				$page->add('<div class="warning">Your password was not updated.');
				if(isset($errors))
				{
					$page->add('<ul>');
					foreach($errors as $var)
					{
						$page->add('<li>'.$var.'</li>');
					}
					$page->add('</ul>');
				}
				$page->add('</div>');
			}
		}
		$page->add('
		<p>Use the form below to update your password.  Note:  Passwords must be at least 8 characters long.</p>
		<form action="admin-settings.php" method="post">
			<table style="width: 400px;">
				<tr>
					<td><label for="oldpass">Current Password</label></td>
					<td><input id="oldpass" name="oldpass" type="password" value="" /></td>
				</tr>
				<tr>
					<td><label for="newpass1">New Password</label></td>
					<td><input id="newpass1" name="newpass1" type="password" value="" /></td>
				</tr>
				<tr>
					<td><label for="newpass2">Verify New Password</label></td>
					<td><input id="newpass2" name="newpass2" type="password" value="" /></td>
				</tr>
				<tr>
					<td></td>
					<td><input name="save" type="submit" value="Update Password" /></td>
				</tr>
			</table>
		</form>
		');
	}

	$page->output();
?>
