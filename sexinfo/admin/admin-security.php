<?php
/**********************************************************************//**\file
	Administration Security

	Description: Provides login and logout functionality.
*******************************************************************************/

	require('../core/sex-core.php');

	# Security function - MUST PASS TRUE FOR ADMIN PAGES!
	$security = new security(TRUE);

	$page = new admin_page('template.html');

	if(isset($_POST['secure_form']))
	{
		if(isset($_POST['login']) && $_POST['login'] == 'Login')
		{
			
			if($security->login($_POST['username'], $_POST['password']))
			{
				$page->title('Logged In');
				$page->head('<meta http-equiv="refresh" content="3;url=/sexinfo/admin/" />');
				$page->add('<h1>Logged In</h1>');
				$page->add('
					<p>You have logged in successfully.</p>
					<p>You will be redirected to the homepage in 3 seconds.</p>
					<p><a href="/sexinfo/admin/">Go there now&hellip;</a></p>
				');
			}
			else
			{
				$page->title('Login');
				$page->add('<h1>Login</h1>');
				$page->add(admin_security_output_login_form(TRUE));
			}
		}
		elseif(isset($_POST['recovery']) && $_POST['recovery'] == 'Recover account')
		{
			$page->title('Account Recovery');
			$page->add('<h1>Account Recovery</h1>');

			if(data::username_valid($_POST['email']))
			{
				$mysqli = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);

				$result = $mysqli->query("
					SELECT user_id, user_activated
					FROM sex_user
					WHERE user_email = '{$_POST['email']}'
				");

				/*
				 * Finish password recovery code here
				 * - Get user data from database
				 * - If it exists, create a new password, add it to the DB, and e-mail it to the user
				 */
			}
		}
		else
		{
			$page->title('Error');
			$page->add('<p>Oops, something broke!  Sorry about that.</p>');
		}
	}
	elseif(isset($_GET['action']) && $_GET['action']=='logout')
	{
		# Already logged in
		$security->close();

		$page->head('<meta http-equiv="refresh" content="3;url=/sexinfo/admin/" />');
		$page->title('Logout');
		$page->add('<h1>Logged out</h1><p>You will be redirected in three seconds.</p>');

	}
	elseif(isset($_GET['action']) && $_GET['action']=='recovery')
	{
		# Forgot login

		$page->title('Account Recovery');
		$page->add('<h1>Account Recovery</h1>');
		$page->add('
		<form action="admin-security.php" method="post" style="width: 18em; padding: 1em 3em;">
			<p><label for="email">Your e-mail: </label> <input name="email" id="email" type="text" /></p>
			<p style="text-align: right;"><input type="submit" name="recovery" value="Recover account" /></p>
			<input type="hidden" name="secure_form" />
		</form>');
	}
	elseif(isset($security) && $security->session_logged_in())
	{
			header('HTTP/1.1 307 Temporary Redirect');
			header('Location: /sexinfo/admin/admin-settings.php');
			exit();
	}
	else
	{
		# Needs to login

		$page->title('Login');
		$page->add('<h1>Login</h1>');
		$page->add(admin_security_output_login_form(FALSE));
	}

	$page->output();

	function admin_security_output_login_form($show_message = FALSE)
	{
		$form = '		<form action="admin-security.php" method="post" style="width: 18em; padding: 1em 3em;">';
		if($show_message)
			$form .= '<p style="color: red;">Your login information was invalid.</p>';
		$form .= '			<table>
				<tr>
					<td><label for="username">Username:</label></td>
					<td><input name="username" id="username" type="text" tabindex="1" /></td>
				</tr>
				<tr>
					<td><label for="password">Password:</label></td>
					<td><input name="password" id="password" type="password" tabindex="2" /></td>
				</tr>
				<tr>
					<td></td>
					<td style="text-align: right;"><input type="submit" name="login" value="Login" tabindex="3" /></td>
				</tr>
				<input type="hidden" name="secure_form" />
			</table>
			<p>Please make sure you have cookies and javascript enabled.</p>
			<p><a href="?action=recovery">Forgot your login info?</a></p>
		</form>';
		return $form;
	}
?>