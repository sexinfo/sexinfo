<?php
/**********************************************************************//**\file
	Impersonate

	Description: Allows superusers to impersonate other users to test / debug
*******************************************************************************/

	require('../core/sex-core.php');

	# Security function - MUST PASS TRUE FOR ADMIN PAGES!
	$security = new security(TRUE);

	$page = new admin_page('template.html');
	$page->title('Impersonate');

	if($security->session_logged_in() && $_SESSION['permission_level'] == 1)
	{
		$mysqli = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);

		$page->add('<h1>Impersonate</h1>');
		$page->add('<p>Impersonating a user allows you to take over their permission set to troubleshoot a problem or debug new features without exposing their credentials.  Impersonating a user only changes your permission set, and does not allow you to make edits under their name.</p>');
		
		$result = $mysqli->query("SELECT user_id, user_username, user_permission_level FROM sex_user WHERE user_permission_level > 1");
		
		# Build options list
		$options = '';
		
		while($row = $result->fetch_assoc())
		{
			$options .= '<option value="'.$row['user_id'].'">'.$row['user_username'].' ('.data::permissions($row['user_permission_level']).')</option>';
		}
		$result->close();
		
		if(isset($_POST['submit']))
		{
			$user = intval($_POST['user']);
			
			$result = $mysqli->query("SELECT user_id, user_username, user_first_name, user_permission_level FROM sex_user WHERE user_permission_level > 1 AND user_id = $user");
			
			if($row = $result->fetch_assoc())
			{
				$_SESSION['real_permission_level'] = $_SESSION['permission_level'];
				$_SESSION['permission_level'] = intval($row['user_permission_level']);

				$_SESSION['real_username'] = $_SESSION['username'];
				$_SESSION['username'] = $row['user_username'];

				$_SESSION['real_first_name'] = $_SESSION['first_name'];
				$_SESSION['first_name'] = $row['user_first_name'];

				$_SESSION['real_user_id'] = $_SESSION['user_id'];
				$_SESSION['user_id'] = intval($row['user_id']);

				$_SESSION['impersonating'] = TRUE;
				
				$page->add('<div class="notice">You are impersonating '.$row['user_username'].'</div>');
			}
			else
			{
				$page->add('<div class="warning">There was a problem impersonating, please try again.</div>');
			}
		}
		else
		{
			$page->add('
				<form method="post" action="admin-impersonate.php">
					<p><label for="user">Choose a user to impersonate:</label>
					<select id="user" name="user">
						'.$options.'
					</select>
					<input type="submit" name="submit" value="Impersonate!" /></p>
				</form>
			');
		}
	}
	else
	{
		if(isset($_SESSION['impersonating']) && $_SESSION['impersonating'] == TRUE)
		{
			$_SESSION['permission_level'] = $_SESSION['real_permission_level'];
			$_SESSION['first_name'] = $_SESSION['real_first_name'];
			$_SESSION['username'] = $_SESSION['real_username'];
			$_SESSION['user_id'] = $_SESSION['real_user_id'];
			unset($_SESSION['real_permission_level']);
			unset($_SESSION['real_first_name']);
			unset($_SESSION['real_username']);
			unset($_SESSION['real_user_id']);
			unset($_SESSION['impersonating']);
			$page->add('<h1>Impersonate</h1>');
			$page->add('<div class="notice">Your permissions have been restored.</div>');
		}
		else
		{
			$page->add('<h1>Impersonate</h1>');
			$page->add('<p>You don\'t have permission to use this feature.</p>');
		}
	}

	$page->output();
?>
