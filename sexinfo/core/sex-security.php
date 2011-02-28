<?php
/**********************************************************************//**\file
	Security and Authentication Classes

	Description: Controls admin panel login, user access level, and provides
		security check functions (is the user logged in?).
*******************************************************************************/

	class security
	{
		private $session_active = FALSE; # Whether the session has been initalized
		private $session_admin = FALSE; # Whether the current script is an admin page
		private $session_logged_in = FALSE; # Whether the current session is valid
		private $asynchronous = FALSE; # Whether the request should be treated as asynchronous

		/*
		 * Workflow:
		 *
		 * - Create s ecurity object
		 * - Constructor checks for existence of session cookie
		 * - If cookie exists, initializes session
		 * - If cookie not set, send to login page
		 * - This step is here to reduce the number of active sessions created by the content page when it checks to see whether user is logged in (to output "edit" buttons on each page)
		 * - If the session cookie is present, initialize the session, check session variables against current stuff.
		 * - If everything matches, continue session, otherwise kill it
		 */

		/**
		 * Constructor initializes session if cookie exists
		 * @param boolean $admin_page
		 * @param boolean $asynchronous
		 */
		public function __construct($admin_page = FALSE, $asynchronous = FALSE)
		{
			$this->session_admin = $admin_page;
			$this->asynchronous = $asynchronous;

			if(isset($_COOKIE['PHPSESSID']) || $this->session_admin)
				$this->initialize();

			if($this->session_admin && !$this->session_logged_in && $_SERVER['PHP_SELF'] != '/sexinfo/admin/admin-security.php')
			{
				$this->fail();
			}
		}

		/**
		 * Destructor for any post-script cleanup, should NOT call close()
		 * The destructor is run after every script / pageview ends, not at the end of the session
		 */
		public function __destruct()
		{
			
		}

/*******************************************************************************
		Internal Functions
*******************************************************************************/

		/**
		 * Create a session and initialize session varibles with data for security verification
		 */
		private function initialize()
		{
			if(!$this->session_active)
			{
				ini_set('session.gc_maxlifetime', '1200');
				ini_set('session.gc_probability', '25');
				session_start();

				$this->session_active = TRUE;

				$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
				$_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
			}
			if(isset($_SESSION['username']) && $_SESSION['username'] != '')
			{
				$this->session_logged_in = TRUE;
			}
		}

		/**
		 * Return a session hash (md5)
		 * @return string
		 */
		private function session_hash()
		{
			return md5('SD)7fdsd)#%&@5'.session_id());
		}

/*******************************************************************************
		External Functions
*******************************************************************************/

		/**
		 *
		 * @param string $username Raw username will be validated via data::username_valid()
		 * @param string $password Raw password will be salted and hashed via data::password_hash()
		 * @return boolean Returns FALSE on failure
		 */
		public function login($username, $password)
		{
			if(data::username_valid($username))
			{
				$password = data::password_hash($password);

				$config = config::database();
				$mysqli = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);

				$result = $mysqli->query("
					SELECT `user_id`, `user_first_name`, `user_permission_level`
					FROM `sex_user`
					WHERE `user_username` = '$username'
						AND `user_password` = '$password'
						AND `user_activated` = '1'
				");

				if($row = $result->fetch_assoc())
				{
					$_SESSION['user_id'] = intval($row['user_id']);
					$_SESSION['username'] = $username;
					$_SESSION['first_name'] = $row['user_first_name'];
					$_SESSION['permission_level'] = intval($row['user_permission_level']);

					$this->session_logged_in = TRUE;

					$result->close();
					$mysqli->query("UPDATE sex_user SET user_date_last_login = ".time()." WHERE user_id = {$_SESSION['user_id']}");
					$mysqli->close();
					return TRUE;
				}
				$result->close();
				$mysqli->close();
			}
			return FALSE;
		}

		/**
		 * Close session (destroy session data)
		 * Note: this is NOT a destructor class
		 */
		public function close()
		{
			$_SESSION = array();
			#yoinked from http://us.php.net/manual/en/function.session-destroy.php
			if (isset($_COOKIE[session_name()])) {
			    setcookie(session_name(), '', time()-42000, '/');
			}
			session_destroy();
		}

		/**
		 * Check whether there is a valid (logged in) session for this user
		 * @return boolean
		 */
		public function session_logged_in()
		{
			return $this->session_logged_in;
		}

		public function fail()
		{
			if($this->asynchronous) {
				header('HTTP/1.1 401 Unauthorized');
				echo("You don't have permission to do that. Make sure you're logged in!");
				exit();
			}
			else {
				$this->redirect();
			}
		}
		
		public function redirect($url = null)
		{
			header('HTTP/1.1 307 Temporary Redirect');
			if($url === null)
			{
				header('Location: /sexinfo/admin/admin-security.php');
			}
			else
			{
				header('Location: '.$url);
			}
			exit();
		}

		/**
		 * Returns an int (or null) for the current user's permissions:
		 *
		 * 1 = Developer
		 * 2 = Webmaster
		 * 3 = Editor
		 * 4 = Inactive
		 *
		 * @return integer
		 */
		public function permission_level()
		{
			return isset($_SESSION['permission_level']) ? $_SESSION['permission_level'] : NULL;
		}
	}
?>
