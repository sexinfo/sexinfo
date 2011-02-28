<?php
/**********************************************************************//**\file
	Navigation Class

	Description: Navigation list processing and output; builds category lists
		for the current article and outputs them to the main page
*******************************************************************************/

/*******************************************************************************
	NOTE!  Initial version is ported from another project and needs tweaking.
*******************************************************************************/
	class nav
	{
		# Constructor
		private $mysqli;

		# Working
		# private $category = array();

		# Output
		private $category_output = '';

		public function __construct()
		{
			$config = config::database();
			$this->mysqli = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);
		}

		public function __destruct()
		{
			unset($this->category_output);
		}

		public function output($type, $id = 0)
		{
			$current_parent_type = intval($type);
			$current_parent_id = intval($id);

			$this->category_output = '';

			$this->build_category_list($current_parent_type, $current_parent_id);

			return $this->category_output;
		}

		private function build_category_list($current_parent_type, $current_parent_id, $current = '')
		{
			# This prevents the system from outputing empty lists
			$current_level_has_children = FALSE;

			foreach($this->get_category_data($current_parent_type, $current_parent_id) as $var)
			{
				#$this->category[] = $var;
				if(!$current_level_has_children)
				{
					$this->category_output .= '<ul>';
					$current_level_has_children = TRUE;
				}

				$this->category_output .= '<li>';
				$this->category_output .= '<a href="/sexinfo/category/'.$var['category_slug'].'">'.$var['category_title'].'</a>';
				$this->build_category_list($current_parent_type, $var['category_id'], '<a class="overview" href="/sexinfo/category/'.$var['category_slug'].'">'.$var['category_title'].' Overview</a>');
				$this->category_output .= '</li>';
			}
			if($current_level_has_children)
				$this->category_output .= '</ul>';
		}

		private function get_category_data($current_parent_type, $current_parent_id)
		{
			if($current_parent_id === 0) {
				$where_clause = "category_parent IS NULL";
			}
			else {
				$where_clause = "category_parent = '$current_parent_id'";
			}
			
			if($current_parent_type != 0)
				$where_clause .= " AND category_type = $current_parent_type";
					
			$query = "SELECT category_id, category_title, category_slug
				FROM sex_category
				WHERE $where_clause";


			$result = $this->mysqli->query($query);
			$array = array();
			while($row = $result->fetch_assoc())
			{
				$array[] = $row;
			}
			return $array;
		}
	}
?>
