<?php
/**********************************************************************//**\file
	Error Class

	Description:  Provides centralized error messages and logging functions;
		Error logs are added to the sex_error table in the db.
*******************************************************************************/

	class error
	{

/*******************************************************************************
	HTML Errors
*******************************************************************************/

		public function code($type, $line, $message = '')
		{
			$page = new page();
			switch ($type)
			{
				case 404:
					$error = self::http404();
					break;
				case 'database':
					$error = self::database($message);
					break;
				default:
					$error = self::standard($message);
					break;
			}

			header($error['header']);

			$page->title($error['title']);
			$page->add($error['page_content']);
			$page->add('<p>We have made a note of the error and will investigate shortly.</p>');
			$page->output();

			self::submit_error('404', $error['error_message'].$message, $line);
			exit();
		}

		private function submit_error($type, $body, $line)
		{
			$time = time();
			$script = $_SERVER['SCRIPT_FILENAME'];

			$config = config::database();
			$mysqli = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);

			$script = $mysqli->real_escape_string($script);
			$body = $mysqli->real_escape_string($body);

			$query = ("
				INSERT INTO sex_error
				VALUES (NULL, $time, '$type', '$script', $line, '$body')
			");

			$mysqli->query($query);

			$mysqli->close();
		}

		private function http404()
		{
			$error['header'] = 'HTTP/1.1 404 Not Found';
			$error['title'] = '404 Not Found';
			$error['page_content'] = '
				<h1>404 - Not Found</h1>
				<p>The page you requested could not be found.</p>
				<p>Please verify that your URL is correct, or try
				<a href="http://www.google.com/cse?cx=016229344819287620609%3A57qdqcwlna8">searching</a>
				for the page you wanted to see.</p>';
			$error['error_message'] = "\n<span class=\"error\">\$_SERVER['REQUEST_URI']</span> = " . print_r($_SERVER['REQUEST_URI'], TRUE);
			
			# On launch old refid links are still broken, specific message for them...
			$error['page_content'] .= '<p style="padding: 10px; border: 1px solid #ff0; background-color: #ffa; margin-bottom: 1em;"><b>Note:</b>  We\'ve recently changed our URL formatting scheme, and some very old URLs to our site don\'t work anymore.  We\'ll be monitoring requests for pages using the old URL format, and we\'ll add support for them when we are able to determine which URLs are still being used on various sites.  We apologize for the invonvenience.</p>';

			return $error;
		}

		private function database()
		{
			$error['header'] = 'HTTP/1.1 200';
			$error['title'] = 'Database Error';
			$error['page_content'] = '
				<h1>Database Error</h1>
				<p>A database error occurred.</p>';
			$error['error_message'] = "\n<span class=\"error\">Query:</span> ";

			return $error;
		}

		private function standard()
		{
			$error['header'] = 'HTTP/1.1 200';
			$error['title'] = 'Error';
			$error['page_content'] = '
				<h1>Error</h1>
				<p>An unspecified error occurred.</p>';
			$error['error_message'] = "\n<span class=\"error\">Unspecified Error:</span> ";

			return $error;
		}
	}
?>
