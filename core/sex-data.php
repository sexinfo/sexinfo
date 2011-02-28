<?php
/**********************************************************************//**\file
	Data Manipulation Class

	Description: Classes used for common data manipulation tasks, such as slug
		creation and validation, data parsing, formatting, etc.
*******************************************************************************/

	/* TODO	This class needs cleanup!	If you can, please consolidate the logic
	 * for the slug functions so we have slug_create, slug_validate, and perhaps
	 * one helper function to check the string against acceptable values (regex)
	 */
	class data
	{
		/**
		 *
		 */
		public static function strip_magic_quotes()
		{
			if(get_magic_quotes_gpc())
			{
				foreach($_POST as $key => $var)
				{
					$_POST[$key] = stripslashes($var);
				}
			}
		}

		/** Convert or validate a slug to slug formatting rules
		 *
		 * @param string $url String to be sluggified
		 * @return string
		 */
		public static function slug_check($url)
		{
			# Prep string with some basic normalization
			$url = strtolower($url);
			$url = strip_tags($url);
			$url = stripslashes($url);
			$url = html_entity_decode($url);

			# Remove quotes (can't, etc.)
			$url = str_replace('\'', '', $url);
			
			# Replace non-alpha numeric with hyphens
			$match = '/[^a-z0-9]+/';
			$replace = '-';
			$url = preg_replace($match, $replace, $url);

			$url = trim($url, '-');

			return $url;
		}

		/** Create a new, unique slug for new content
		 *
		 * @param string $url Pass string to be converted to slug format
		 * @param bool $article True if new content is an article, false if it's a category. Defaults to true
		 * @return string New, unique slug
		 */
		public static function slug_create($url, $article = TRUE)
		{
			$url = self::slug_check($url);
			
			$type = $article ? 'content' : 'category';
			$table = "sex_$type"; // thing
			$column = "${type}_slug";

			# Check for duplicate slug
			$config = config::database();
			$mysqli = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);
			$unique = FALSE;
			$suffix = 1;
			$baseurl = $url;
			while(!$unique)
			{
				$result = $mysqli->query("SELECT COUNT(*) FROM `$table` WHERE `$column`='$url'");
				$row = $result->fetch_row();
				if($row[0] == 0)
				{
					$unique = TRUE;
				}
				else
				{
					$url = $baseurl.'-'.$suffix;
					$suffix++;
				}
				$result->close();
			}
			$mysqli->close();

			return $url;
		}

		/**
		 * Yoinked from http://www.vision.to/convert-mysql-date-to-unix-timestamp.php
		 * @param string $time
		 * @return int
		 */
		public static function mysql_timestamp($str)
		{
			list($date, $time) = explode(' ', $str);
			list($year, $month, $day) = explode('-', $date);
			list($hour, $minute, $second) = explode(':', $time);

			$timestamp = mktime($hour, $minute, $second, $month, $day, $year);

			return $timestamp;
		}
		
		public static function password_hash($str)
		{
			$salt = 'fa-0sew9_*&(%#231';

			return md5($salt.$str);
		}

		public static function username_valid($username_raw)
		{
			# Check that string is alphanumeric
			# Thanks to http://www.webmasterworld.com/javascript/3045489.htm
			$match = '/^[a-zA-Z0-9\-_@.]+$/';
			return preg_match($match, $username_raw);
		}
		
		public static function format_date($timestamp, $mode = 0)
		{
			if($timestamp == 0 || is_null($timestamp))
				return 'Never';
			
			if($mode == 1)
			{
				if(date('Y-m-d', $timestamp) == date('Y-m-d'))
					return 'Today';
				else
					return date('F j, Y', $timestamp);
			}
			elseif($mode == 2)
			{
				if(date('Y-m-d', $timestamp) == date('Y-m-d'))
					return 'Today at '.date('H:i', $timestamp);
				else
					return date('F j, Y H:i', $timestamp);
			}
			elseif($mode == 3)
			{
				return date('Y-m-d H:i', $timestamp);
			}
			elseif($mode == 4)
			{
				return date('l, M j H:i', $timestamp);
			}
			elseif($mode == 5)
			{
				return date('Y-m-d', $timestamp);
			}
			else
			{
				return date('c', $timestamp);
			}
		}

		public static function cleaner($input)
		{
			$table = array(chr(147)=>'"', chr(148)=>'"', chr(145)=>'\'', chr(146)=>'\'', chr(150)=>'-', chr(151)=>'-');
			foreach ($table as $key=>$var)
			{
				$input = str_replace($key, $var, $input);
			}
			return $input;
		}
		
		public static function permissions($level)
		{
			switch ($level)
			{
				case 1:
					return 'Developer';
				case 2:
					return 'Webmaster';
				case 3:
					return 'Editor';
				case 4:
					return 'Inactive';
				default:
					return 'Unspecified';
			}
		}
		
		/**
		 * Given a category ID, retrieve the whole ancestry of that category
		 * @param int $cat_id The ID of the category
		 * @return array A list of categories, starting from the top level down to the requested category.
		 *				 The categories are stored as associative arrays with the keys 'id', 'title', and 'slug'.
		 */
		public static function fetch_category_ancestry($cat_id) {
			$config = config::database();
			$mysqli = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);
			
			$data = array();
			$next_cat = $mysqli->real_escape_string($cat_id);
			
			do {
				$result = $mysqli->query("SELECT * FROM `sex_category` WHERE `category_id`='$next_cat'");
				$category = $result->fetch_assoc();
				array_unshift($data, array(
					'id' => $category['category_id'],
					'slug' => $category['category_slug'],
					'title' => $category['category_title']
				));
				$next_cat = $category['category_parent'];
				$result->close();
			} while(!is_null($next_cat));
			
			return $data;
		}
		
		/**
		 * Given a category ID, return a bunch of <option> tags (for use in a <select>) corresponding
		 * to possible subcategories.
		 * @param int $type The type of category to look for (e.g. 1 for article or 2 for Q&A)
		 * @param int $id The ID of the parent category. If 0, returns categories with no parent (top-level categories).
		 * @param int $next If provided, this information will be used to pre-select one of the categories (by 
		 *				setting its 'selected' attribute).
		 * @return string A bunch of <option> tags.
		 */
		public static function subcategory_options($type = 1, $id = 0, $next = 0) {
			$config = config::database();
			$mysqli = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);
			
			$options = '';
			$type = (int) $type;
			$id = (int) $id;
			$next = (int) $next;

			if($id > 0) {
				$result = $mysqli->query("
					SELECT	 *
					FROM		 `sex_category`
					WHERE		`category_parent`='$id'
					AND			`category_type`='$type'
					ORDER BY `category_title`
				");

				$options .= '<option value="0"';
				if($next == 0) $options .= ' selected="selected"';
				$options .= ">&laquo;end here&raquo;</option>\n";
			}
			else {
				$result = $mysqli->query("
					SELECT	 *
					FROM		 `sex_category`
					WHERE		`category_parent` IS NULL
					AND			`category_type`='$type'
					ORDER BY `category_title`
				");
				
				$options .= '<option value="0"';
				if($next == 0) $options .= ' selected="selected"';
				$options .= ">Select a category</option>\n";
			}

			while($subcat = $result->fetch_assoc()) {
				$subid = (int) $subcat['category_id'];
				$subtitle = htmlspecialchars($subcat['category_title']);

				$options .= "<option value=\"$subid\"";
				if($subid == $next) $options .= ' selected="selected"';
				$options .= ">$subtitle</option>\n";
			}
			
			$result->close();
			
			return $options;
		}
		
		public static function fetch_category_descendants($cat_id) {
			$config = config::database();
			$mysqli = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);
			
			$cats = array();
			$cat_id = (int) $cat_id;
			
			$result = $mysqli->query("SELECT * FROM `sex_category`
																WHERE `category_parent`='$cat_id'
																ORDER BY `category_id` ASC");
																
			while($cat = $result->fetch_assoc()) {
				$name = $cat['category_title'];
				$id = $cat['category_id'];
				$slug = $cat['category_slug'];
				$content = $cat['category_content_id'];
				$children = data::fetch_category_descendants($id);
				
				$cats[] = array('name' => $name,
												'id' => $id,
												'slug' => $slug,
												'content' => $content,
												'children' => $children);
			}
			
			$result->close();
			
			return $cats;
		}
		
		public static function fetch_full_category_list($type = 1) {
			$config = config::database();
			$mysqli = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);
			
			$cats = array();
			$type = (int) $type;
			
			$result = $mysqli->query(
				"SELECT * FROM `sex_category`
				 WHERE `category_type`='$type'
				 AND `category_parent` IS NULL
				 ORDER BY `category_title` ASC"
			);
			
			while($cat = $result->fetch_assoc()) {
				$name = $cat['category_title'];
				$id = $cat['category_id'];
				$slug = $cat['category_slug'];
				$content = $cat['category_content_id'];
				$children = data::fetch_category_descendants($id);
				
				$cats[] = array('name' => $name,
												'id' => $id,
												'slug' => $slug,
												'content' => $content,
												'children' => $children);
			}
			
			$result->close();
			
			return $cats;
		}

		public static function bug_priority($input, $title = false)
		{
			if($title)
			{
				switch($input)
				{
					case 1:
						return 'Low priority issues are annoying bugs or \'nice-to-have\' features that don\'t impact current functionality.';
					case 2:
						return 'Medium priority issues indicate missing functionality of an infrequently-used feature.';
					case 3:
						return 'High priority issues indicate missing functionality of a commonly-used feature.';
					case 4:
						return 'Critical issues indicate a show-stopping problem that must be fixed immediately.';
					default:
						return 'No priority has been specified for this issue.';
				}
			}
			else
			{
				switch($input)
				{
					case 1:
						return 'Low';
					case 2:
						return 'Medium';
					case 3:
						return 'High';
					case 4:
						return 'Critical';
					default:
						return 'Unspecified';
				}
			}
		}

		public static function bug_status($input, $title = false)
		{
			if($title)
			{
				switch($input)
				{
					case 1:
						return 'An Open issue is outstanding and has not yet been assigned to a developer.';
					case 2:
						return 'An Assigned issue has been delegated to or claimed by a developer who is responsible for working on it.';
					case 3:
						return 'A Fixed issue has been worked on and implemented or resolved.  Yay!';
					case 4:
						return 'A Closed issue is one that the developers have decided not to work on.';
					default:
						return 'No status has been specified for this issue.';
				}
			}
			else
			{
				switch($input)
				{
					case 1:
						return 'Open';
					case 2:
						return 'Assigned';
					case 3:
						return 'Fixed';
					case 4:
						return 'Closed';
					default:
						return 'Unspecified';
				}
			}
		}

		public static function bug_type($input, $title = false)
		{
			if($title)
			{
				switch($input)
				{
					case 1:
						return 'Bug - A feature that exists doesn\'t work correctly, or at all';
					case 2:
						return 'Enhancement - Additional functionality for an existing feature';
					case 3:
						return 'Todo - A task or feature that a developer plans to work on';
					default:
						return 'This issue has not been categorized.';
				}
			}
			else
			{
				switch($input)
				{
					case 1:
						return 'Bug';
					case 2:
						return 'Enhancement';
					case 3:
						return 'Todo';
					default:
						return 'Unspecified';
				}
			}
		}
	}
?>