<?php
	# CORE LOGIC
	# Simply incude this file to use database queries
	class security
	{

	}

	class dbquery
	{
		private $mysqli;

		public function __construct()
		{
			require_once('config.php');
			$this->mysqli = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);
		}

		public function __destruct()
		{
			$this->mysqli->close();
		}

		public function fetch_string($query)
		{
			$result = $this->mysqli->query($query);
			$row = $result->fetch_row();
			return $row[0];
		}

		public function fetch_row()
		{
			
		}

		public function fetch_array()
		{
			
		}

		public function sanitize($string)
		{
			$safechars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
			return (strspn($string, $safechars) == strlen ($string));
		}
	}
?>
