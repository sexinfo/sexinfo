<?php
/**********************************************************************//**\file
	User Control Panel

	Description: Allows the creation and configuration of user accounts for
		editors and webmasters.
*******************************************************************************/

	require('../core/sex-core.php');

	# Security function - MUST PASS TRUE FOR ADMIN PAGES!
	$security = new security(TRUE);

	if($security->session_logged_in() && $security->permission_level() == 1)
	{
		$mysqli = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);
		
		# Initialize Objects
		$page = new admin_page('template.html');
		$page->title('User Management');
		$page->add('<h1>User Management</h1>');
		
		# Initialize Variables
		$user['id'] = '';
		$user['username'] = '';
		$user['first_name'] = '';
		$user['last_name'] = '';
		$user['email'] = '';
		$user['permission_level'] = '';
		
		/*if($_GET['id']!=0)
		# Add message
		$page->add('<div class="warning">id = '.$id.'</div>');*/

		############

		# Post form processing
		if(isset($_POST['user-add']) || isset($_POST['user-update']))
		{
			$user['id'] = intval($_POST['id']);
			$user['username'] = $_POST['username'];
			$user['first_name'] = $_POST['first-name'];
			$user['last_name'] = $_POST['last-name'];
			$user['email'] = $_POST['e-mail'];
			$user['permission_level'] = intval($_POST['permissions']);
			
			$form_data_valid = TRUE;
			
			foreach($user as $var)
			{
				if(data::username_valid($var) != 1)
					$form_data_valid = FALSE;
			}

			if($form_data_valid)
			{
				# Add user
				if(isset($_POST['user-add']))
				{
					$password = substr(md5(mt_rand()), 0, 8);
					
					$mysqli->query("
						INSERT INTO sex_user
						VALUES (
							NULL,
							'{$user['username']}',
							'{$user['email']}',
							'".data::password_hash($password)."',
							'{$user['first_name']}',
							'{$user['last_name']}',
							".time().",
							0,
							{$user['permission_level']},
							1,
							NULL
						)
					");
					
					# Send out e-mails
					$message = 'Hi '.$user['first_name'].',

Your account information for 152C can be found below:

Username: '.$user['username'].'
Password: '.$password.'

You can change your password on the "Settings" tab after logging in.

If you have any problems, or if you received this e-mail in error, please let us know.

Thanks,
sexwebmaster@gmail.com';
					
					mail($user['email'], '152C Account Information', $message, "From: sexwebmaster@gmail.com\r\nReply-To: sexwebmaster@gmail.com");
					
					$page->add('<div class="notice">User added successfully.<ul><li>Username: '.$user['username'].'</li><li>Password: '.$password.'</li></ul></div>');
				}
				
				# Edit user
				if(isset($_POST['user-update']))
				{
					$mysqli->query("
						UPDATE sex_user
						SET `user_username` = '{$user['username']}',
							`user_first_name` = '{$user['first_name']}',
							`user_last_name` = '{$user['last_name']}',
							`user_email` = '{$user['email']}',
							`user_permission_level` = {$user['permission_level']}
						WHERE `user_id` = {$user['id']}
					");
					$page->add('<div class="notice">User information updated successfully.</div>');
				}
	
				# Reset UI
				foreach($user as $key => $var)
				{
					$user[$key] = '';
				}
			}
			else
			{
				$page->add('<div class="warning">There was a problem with the information you entered; please double-check it.</div>');
			}
		}
		
		############

		# Get $id, set 0 on invalid data and fail those cases
		if(isset($_GET['id']))
			intval(strcmp(intval($_GET['id']), $_GET['id']) == 0) ? $id = intval($_GET['id']) :	$id = 0 ;
		
		if(isset($_GET['action']) && $_GET['action'] == 'edit' && $id != 0)
		{
			$result = $mysqli->query("
				SELECT `user_id`, `user_username`, `user_first_name`, `user_last_name`, `user_email`, `user_permission_level`
				FROM `sex_user`
				WHERE `user_id` = $id
			");
			$row = $result->fetch_assoc();
			
			$user['id'] = $row['user_id'];
			$user['username'] = $row['user_username'];
			$user['first_name'] = $row['user_first_name'];
			$user['last_name'] = $row['user_last_name'];
			$user['email'] = $row['user_email'];
			$user['permission_level'] = $row['user_permission_level'];

			$result->close();
		}
		
		############
		
		# Output form
		$page->add('
			<h2>Add New User</h2>
			<form id="add-user" method="post" action="admin-user.php">
				<table>
					<tr>
						<td>First Name</td>
						<td><input type="text" name="first-name" value="'.$user['first_name'].'" /></td>
					</tr>
					<tr>
						<td>Last Name</td>
						<td><input type="text" name="last-name" value="'.$user['last_name'].'" /></td>
					</tr>
					<tr>
						<td>E-mail</td>
						<td><input type="text" name="e-mail" value="'.$user['email'].'" /><!--@umail.ucsb.edu--></td>
					</tr>
					<tr>
						<td>Username</td>
						<td><input type="text" name="username" value="'.$user['username'].'" /></td>
					</tr>
					<tr>
						<td>Permissions</td>
						<td>
							<select name="permissions">');

		# Output permission options
		$permissions = array( 0, 1, 2, 3, 4 );
		foreach($permissions as $var)
		{
			$page->add('<option ');
			if($var == $user['permission_level'])
				$page->add('selected="selected" ');
			$page->add('value="'.$var.'">'.data::permissions($var).'</option>');
		}

		$page->add('					</select>
						</td>
					</tr>
				</table>
				<p>A password will be automatically generated and e-mailed to the address specified above.</p>');
		
		# Output Buttons
		$page->add('<p><input type="submit" name="user-add" value="Add New User" />');

		# Output update button if editing an existing user
		if(isset($_GET['action']) && $_GET['action'] == 'edit' && $id != 0)
		{
			$page->add(' <input type="submit" name="user-update" value="Update User" />');
		}
			
		# Output hidden form field if editing old user
		$page->add('<input type="hidden" name="id" value="'.$user['id'].'" />');

		# Finalize form
		$page->add('</p></form>');
		
		

		# Start current users
		$page->add('<h2>Current Users</h2>');

		$query = "
			SELECT user_id, user_username, user_email, user_first_name, user_last_name, user_date_added, user_date_last_login, user_activated, user_permission_level
			FROM sex_user
			ORDER BY user_last_name ASC
		";
		#if(!(isset($_GET['action']) && $_GET['action'] == 'showall'))
		#	$query .= " WHERE user_permission_level <= 3";
		$result = $mysqli->query($query);

		# Start table
		$page->add('
		<table style="width: 100%;">
			<tr>
				<th>Username (Edit)</th>
				<th>Real Name (E-mail)</th>
				<th>Perimissions</th>
				<th>Added</th>
				<th>Last Login</th>
			</tr>');
		
		# Output table contents
		while($row = $result->fetch_assoc())
		{
			$page->add('
			<tr>
				<!--<td>'.$row['user_id'].'</td>-->
				<td><a href="?action=edit&id='.$row['user_id'].'">'.$row['user_username'].'</a></td>
				<td><a href="mailto:'.$row['user_email'].'">'.$row['user_first_name'].' '.$row['user_last_name'].'</a></td>
				<td>'.data::permissions($row['user_permission_level']).'</td>
				<td>'.data::format_date($row['user_date_added'], 5).'</td>
				<td>'.data::format_date($row['user_date_last_login'], 5).'</td>
			</tr>');
		}
		$page->add('</table>');

		#$page->

		$page->output();
	}
	else
	{
		$security->redirect();
	}
?>
