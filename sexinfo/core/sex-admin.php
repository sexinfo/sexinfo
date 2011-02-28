<?php
/**********************************************************************//**\file
	Sex Admin Classes

	Description: Extended classes specifically for use in the admin panel
*******************************************************************************/

	class admin_page extends page
	{
		function create_navlist()
		{
			GLOBAL $security;
			$navlist = config::admin_navigation($security->permission_level());

			$parts = explode('/', $_SERVER['PHP_SELF']);
			$lastpart = $parts[count($parts)-1];

			foreach($navlist as $key => $var)
			{
				$this->navlist .= '<li><a ';
				if($var == $lastpart)
				{
					$this->title($key);
					$this->navlist .=  'class="current" ';
				}
				$this->navlist .= 'href="'.$var.'">'.$key.'</a></li>';
			}
		}

		function output_login_link()
		{
			GLOBAL $security;
			# Show login link if not logged in
			if(!$security->session_logged_in() || (isset($_GET['action']) && $_GET['action'] == 'logout'))
			{
				$this->insert('%login%', '<a href="admin-security.php">Login</a>');
				$this->insert('%user%', '');
			}
			
			# Show logout link if logged in, and show username
			else
			{
				$this->insert('%login%', '<a href="admin-security.php?action=logout">Logout</a>');
				
				$user_message = 'Logged in as '.$_SESSION['first_name'].' ('.data::permissions($_SESSION['permission_level']).')';
				
				if(isset($_SESSION['impersonating']) && $_SESSION['impersonating'] == TRUE)
					$this->insert('%user%', $user_message.' &bull; <a href="admin-impersonate.php">Restore Permissions</a>');
				else
					$this->insert('%user%', $user_message);
			}
		}

		function output_navigation()
		{
			$this->create_navlist();
			$this->insert('%navigation%', $this->navlist);
		}

		public function output($return = false)
		{
			$nav = new nav();
			$this->insert('%content%', $this->content);
			$this->insert('%head%', $this->head);
			$this->insert('%warning%', $this->warning);
			$this->output_login_link();
			$this->output_navigation();

			parent::output($return);
		}
	}
?>
