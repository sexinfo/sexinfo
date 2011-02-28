<?php
/**********************************************************************//**\file
	Debug Information

	Description: Outputs debug information at the top of the page
*******************************************************************************/

	class debug
	{
		public function output_legacy_id($page, $slug)
		{
			$config = config::database();
			$mysqli = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);

			# Build appropriate query
			switch ($_SERVER['SCRIPT_NAME'])
			{
				case '/sexinfo/article.php':
				case '/sexinfo/question.php':
					$query = "
						SELECT `legacy_hash_id`, `content_id`
						FROM `sex_legacy`
						JOIN `sex_content`
							ON legacy_content_id = content_id
						WHERE content_slug = '$slug'
					";
					break;

				case '/sexinfo/category.php':
					$query = "
						SELECT `legacy_hash_id`, `category_id`
						FROM `sex_legacy`
						JOIN `sex_category`
							ON legacy_content_id = category_id
						WHERE category_slug = '$slug'
					";
					break;
			}

			$result = $mysqli->query($query);

			# Output id
			if($row = $result->fetch_row())
			{
				$page->add('<div class="debug"><p><a href="http://www.soc.ucsb.edu/sexinfo/?article='.$row[0].'">legacy_hash_id: '.$row[0].'</a><br /><a href="/sexinfo/admin/admin-content.php?id='.$row[1].'">new id: '.$row[1].'</a></p></div>');
			}
		}

		# untested
		/*public function get_legacy_id($slug)
		{
			$config = config::database();
			$mysqli = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);

			$query = "SELECT `legacy_hash_id`
						FROM `sex_legacy`
						JOIN `sex_content`
							ON legacy_content_id = content_id
						WHERE content_slug = '$slug'";
			$result = $mysqli->query($query);

			if($row = $result->fetch_row())
			{
				return $row[0];
			}
		}*/
	}
?>
