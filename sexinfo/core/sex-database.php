<?php
/**********************************************************************//**\file
	Armory Database

	Description:  Database simplification / abstraction class for armory data
*******************************************************************************/

	class database
	{
		public $mysqli = null;
		public $result = null;
		public $error = null;

		public function __construct()
		{
			$config = config::database();

			$this->mysqli = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);
		}

		public function query($query)
		{
			if($this->result = $this->mysqli->query($query))
			{
				return true;
			}
			else
			{
				$this->error = $this->mysqli->error;
				return false;
			}
		}

		public function result()
		{
			return $this->result->fetch_assoc();
		}

		public function quick_result()
		{
			$array = $this->result->fetch_array();
			return $array[0];
		}

		public function multi_result()
		{
			$results = array();

			while($row = $this->result->fetch_assoc())
			{
				$results[]=$row;
			}

			return $results;
		}

		public function numrows()
		{
			return $this->mysqli->affected_rows;
		}

		public function lastid()
		{
			return $this->mysqli->insert_id;
		}

		public function escape($string)
		{
			return $this->mysqli->real_escape_string($string);
		}

		public function __destruct()
		{
			$this->mysqli->close();
		}
	}
?>