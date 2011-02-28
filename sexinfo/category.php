<?php
/**********************************************************************//**\file
	Category Index

	Description:  Shows a list of categories, or displays a single category page
		if there is a $_GET match
*******************************************************************************/

	require_once('./core/sex-core.php');

	$page = new page();

	# NOTE!  $_GET['slug'] changed to isset($uri_details[3]) && $uri_details[3] != '' because LSIT is FUBAR and doesn't get query string from mod_rewrite

	$uri_details = explode('/', $_SERVER['REQUEST_URI']);

    if (strpos($uri_details[3], 'refid')) {
                header('Location: ' . "/sexinfo/$uri_details[3]");
                exit();
            }
            if ($rep = strpos($uri_details[3], 'article')) {
                $uri_details[3] = $arturl;
                $rep - 1;
                 $headernew = substr_replace($arturl, '', 0, $rep);
                header('Location: ' . "/sexinfo/$headernew");
                exit();
            }
    
    if(isset($uri_details[3]) && $uri_details[3] != '')
	{
		$mysqli = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);

		$slug = $mysqli->real_escape_string($uri_details[3]);

		$result = $mysqli->query("
			SELECT category_id, category_title, content_body
			FROM sex_category
			JOIN sex_content
				ON content_id = category_content_id
			WHERE category_slug = '$slug'
			LIMIT 1
		");
		
		if($row = $result->fetch_assoc())
		{
			$page->title($row['category_title']);
			$page->add($row['content_body']);

			#$page->add('<h2>Subcategories</h2>');

			#$nav = new nav();
			#$page->add($nav->output(1, $row['category_id']));
			
			// first grab subcategories
			
			/*$subcategories = $mysqli->query("
				SELECT category_title, category_slug
				FROM sex_category
				WHERE category_parent=$id
			");
			
			if(mysqli_num_rows($subcategories))
			{
				$page->add("<div class=\"subcategories\">\n");
				$page->add("<h2>Subcategories</h2>");
				
				$page->add("<ul>\n");
				while($subcategory = $subcategories->fetch_row())
				{
					$title = $subcategory[0];
					$slug = $subcategory[1];
					
					$page->add("<li><a href=\"/sexinfo/category/$slug\">$title</a></li>");
				}
				$page->add("</ul>\n</div>\n");
			}
			
			// now grab member articles
			
			$articles = $mysqli->query("
				SELECT content_title,content_slug
				FROM sex_content
				JOIN sex_bridge
					ON bridge_content_id=content_id
				WHERE bridge_category_id=$id
			");
			
			if(mysqli_num_rows($articles))
			{
				$page->add("<div class=\"articles\">\n");
				$page->add("<h2>Articles</h2>");
				$page->add("<ul\n>");
				
				while($article = $articles->fetch_row())
				{
					$title = $article[0];
					$slug = $article[1];
					
					$page->add("<li><a href=\"/sexinfo/article/$slug\">$title</a></li>");
				}
				
				$page->add("</ul>\n</div>");
			}*/
		}
		else
		{
			# Category Page Hack
			$result->close();
			$result = $mysqli->query("
				SELECT content_slug
				FROM sex_content
				WHERE content_slug = '$slug'
				LIMIT 1
			");
			if($row = $result->fetch_row())
			{
				$url = '/sexinfo/article/'.$slug;

				header('HTTP/1.1 301 Moved Permanently');
				header('Location: ' . $url);
			}
			else
				error::code(404, __LINE__);
		}
	}
	else
	{
		$page->title('Category');
		$page->add('<h1>Category Listing</h1>');
		$page->add('<p>The Sexperts have written over 2000 pages on SexInfo, and it has to arranged in an orderly fashion!  Check out the categories below for related content.</p>');
		# Add url query string filter for types?
		#$page->add('<p>You can see all types of content by default; if you\'d only like to see one type, select it from the list.');

		$mysqli = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);

		$result = $mysqli->query("
			SELECT type_id, type_name
			FROM sex_type
			WHERE type_id != 3
		");

		#TODO empty category check

		$nav = new nav();

		while($row = $result->fetch_assoc())
		{
			$page->add('<h3 class ="post-content" name="'.$row['type_name'].'">'.$row['type_name'].'s</h3>');
			$page->add($nav->output($row['type_id'], 0));
		}
	}
	
	$page->output();
?>