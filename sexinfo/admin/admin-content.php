<?php
/**********************************************************************//**\file
	Content Editor

	Description: Allows authors to post new articles or edit existing articles.
		Edits will be added to a moderation queue for final webmaster edits and
		approval.
*******************************************************************************/

	require('../core/sex-core.php');

	# Security function - MUST PASS TRUE FOR ADMIN PAGES!
	$security = new security(TRUE);
	
	$global_time = time();

	if($security->session_logged_in() && ($_SESSION['permission_level'] == 1 || $_SESSION['permission_level'] == 2 || $_SESSION['permission_level'] == 3))
	{
		$page = new admin_page('template.html');
		$page->add('<h1>Content Editor</h1>');

		$mysqli = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);

	
		$id = 0;
		$title = '';
		$body = '';
		$validation_body = ''; # For validation purposes
		$abstract = '';
		$type = 1;
		$slug = '';

		# fetch data if this is an edit
		if(isset($_GET['action']) && $_GET['action'] == 'edit') {
			$result = false;

			if(isset($_GET['id'])) {
				$id = $mysqli->real_escape_string($_GET['id']);

				$result = $mysqli->query(
					"SELECT `content_id`, `content_title`, `content_body`, `content_abstract`, `content_type`
					 FROM `sex_content` WHERE `content_id`='$id' LIMIT 1"
				);
			} else if(isset($_GET['slug'])) {
				$slug = $mysqli->real_escape_string($_GET['slug']);

				$result = $mysqli->query(
					"SELECT `content_id`, `content_title`, `content_body`, `content_abstract`, `content_type`
					 FROM `sex_content` WHERE `content_slug`='$slug' LIMIT 1"
				);
			}

			if($result) {
				if($row = $result->fetch_assoc()) {
					$id = $row['content_id'];
					$title = $row['content_title'];
					$body = $row['content_body'];
					$abstract = $row['content_abstract'];
					$validation_body = $row['content_body']; # For validation purposes
					$type = $row['content_type'];
				}

				$result->close();
			}
		}

		$errors = '';
		$posted = false;

		if(isset($_POST['submit'])) {
			$id = (int) $_POST['id'];

			if(isset($_POST['type'])) {
				$type = (int) $_POST['type'];
			}
			else {
				$type = 1;
			}

			$title = trim(stripslashes($_POST['title']));
			$body = trim(stripslashes($_POST['body']));
			$validation_body = trim(stripslashes($_POST['body'])); # For validation purposes
			$abstract = trim(stripslashes($_POST['abstract']));

			$query = '';
			$query_body = $mysqli->real_escape_string($body);
			$query_title = $mysqli->real_escape_string($title);
			$query_abstract = $mysqli->real_escape_string($abstract);

			if($title === '') {
				$errors .= 'Error: Article must have a title.';
			}
			else if($id === 0) {
				# make a new article
				$slug = data::slug_create($title);
				$query = "
					INSERT INTO `sex_content`
					SET
						`content_slug` = '$slug', 
						`content_title` = '$query_title',
						`content_abstract` = '$query_abstract',
						`content_body` = '$query_body',
						`content_added` = $global_time,
						`content_modified` = $global_time,
						`content_type` = '$type',
						`content_title_in_body` = 0,
				";
				# Check permissions
				if($_SESSION['permission_level'] == 1 || $_SESSION['permission_level'] == 2)
				{
					$query .= "
						`content_is_published` = 1,
						`content_publisher_id` = {$_SESSION['user_id']},
						`content_published` = $global_time
					";
				}
				else
				{
					$query .= "
						`content_is_published` = 0,
						`content_author_id` = {$_SESSION['user_id']}
					";
				}
				
				$mysqli->query($query);

				# get the ID of the new article
				$id = $mysqli->insert_id;

				$posted = true;
			}
			else {
				# update an existing article
				$result = $mysqli->query("
					SELECT `content_slug`
					FROM `sex_content`
					WHERE `content_id`='$id' LIMIT 1
				");

				if($row = $result->fetch_row()) { # update existing article
					$slug = $row[0];
                    $query1 = "UPDATE `sex_content`
                                SET `content_title` = '$query_title',
                                `content_abstract` = '$query_abstract',
                                `content_body` = '$query_body',
                                `content_type` = '$type',
                                `content_modified` = '$global_time',
                                `content_title_in_body` = 0,
                                `content_editor_id` = {$_SESSION['user_id']}, ";
                if($_SESSION['permission_level'] == 1 || $_SESSION['permission_level'] == 2)
                {
                        $query1 .= " `content_is_published` = 1, `content_publisher_id` = {$_SESSION['user_id']},
                                    `content_published` = $global_time
                                WHERE `content_id` = '$id'";
                }
                else {
                        $query1 .= " `content_is_published`  = '0', `content_editor_id` = {$_SESSION['user_id']}  WHERE `content_id` = '$id'";

                        }
                        
					$mysqli->query($query1);
                    

					$posted = true;
				} else {
					$errors .= 'Warning: the article to be edited was not found. ' .
						"Submit again to post this as a new article.<br />\n";
					$id = 0;
				}
			}
		}

		$id = (int) $id;
		$type = (int) $type;
		$title = htmlspecialchars($title);
		$abstract = htmlspecialchars($abstract);
		$body = htmlspecialchars($body);

		if($posted) {
			// now update the placement if necessary
			if($_POST['alterPlaces'] == 1) {
				// delete existing bridges in preparation for making new ones
				$mysqli->query("DELETE FROM `sex_bridge` WHERE `bridge_content_id`='$id'");

				// find the deepest category in each placement and create a bridge to it
				if(isset($_POST['cat'])) {
					foreach($_POST['cat'] as $placement) {
						$category = 0;

						for($i = 0; isset($placement[$i]); $i++) {
							if(((int) $placement[$i]) > 0) $category = $placement[$i];
						}

						if($category > 0) {
							// make sure it's a real category with the right type
							$result = $mysqli->query("SELECT * FROM `sex_category` WHERE `category_id`='$category' LIMIT 1");
							$cat = $result->fetch_assoc();
							if($cat && $cat['category_type'] == $type) {
								$mysqli->query("INSERT INTO `sex_bridge` (`bridge_content_id`, `bridge_category_id`)
									VALUES ('$id', $category)");
							}

							$result->close();
						}
					}
				}
			}
            if($type == 1) {
                $prefix = "article";
            }
            if($type == 2) {
                $prefix = "question";
            }
            if($type == 4) {
                $prefix = "article";
            }
			$page->add("Way to post! Your article can be viewed here:<br />\n");
			$page->add("<a href='/sexinfo/$prefix/$slug'>$title</a><br />\n");
            
		}
		else {
			$placements = $mysqli->query("SELECT * FROM `sex_bridge` WHERE `bridge_content_id`='$id'");

			$catlines = array();

			while($placement = $placements->fetch_assoc()) {
				$catlist = data::fetch_category_ancestry($placement['bridge_category_id']);
				$catnames = array();

				foreach($catlist as $cat) {
					$catnames[] = "<a href=\"../category/{$cat['slug']}\">{$cat['title']}</a>";
				}

				$catlines[] = implode(' &gt; ', $catnames);
			}

			$placements->close();

			if(count($catlines) > 0) {
				$result = $mysqli->query("SELECT * FROM `sex_type` WHERE `type_id`='$type' LIMIT 1");
				$categories = "Type: ";
				if($row = $result->fetch_assoc()) {
					$categories .= $row['type_name'];
				} else {
					$categories .= '&lt;undefined&gt;';
				}
				$categories .= " ($type)<br />\n" . implode("<br />\n", $catlines);
				$result->close();
			}
			else {
				$categories = "This article hasn't been placed in any categories!";
			}

			/*-- Content Editor Form -----------------------------------------*/

			$page->head('<script type="text/javascript" src="prototype.js"></script>' .
							'<script type="text/javascript" src="admin-content.js"></script>');
			
			$page->add(<<<HERE
				<form id="content-editor" action="admin-content.php" method="post">
					<div id="placement-editor">
						<h2>Categories</h2>
						<div id="existing-placements">
							$categories<br />
							<a href="javascript:SexInfo.Content.editPlacements();">[edit]</a><br />
						</div>
						<div id="edited-placements" style="display: none">Wait a moment...</div>
						<div class="error">$errors</div>
						
						<input type="hidden" name="id" id="id-input" value="$id" />
						<input type="hidden" name="alterPlaces" value="0" id="alter-places-input" />
						<input type="hidden" name="known-type" value="$type" id="known-type-input" />
					</div>
				
					<p><label for="title">Title:</label><br />
					<input id="title" type="text" name="title" value="$title" size="40" /> - Just here, not in the body.</p>
					
					<p><label for="abstract">Abstract:</label><br />
					<textarea id="abstract" name="abstract" rows="4" cols="100">$abstract</textarea></p>
	
					<p><label for="body">Body:</label><br />
					<textarea id="body" name="body" rows="20" cols="100">$body</textarea></p>
					
					<p><input type="submit" name="submit" value="Save" /></p>
				</form>
HERE
			);

			/*-- Uploaded Images ---------------------------------------------*/
			$page->add('<div id="content_images"><h3>Uploaded Images</h3>');
			$page->add('<p>Click and drag to insert into your document.</p>');
			$updated_images = array();
			foreach(scandir('../images/') as $var)
			{
				if($var[0] != '.' and is_file('../images/'.$var))
				{
					$file = '../images/'.$var;
					if(filemtime($file) > time() - 2592000)
					{
						$updated_images[$var] = filemtime($file);
					}
				}
			}
			arsort($updated_images);
			$limit = 20; # limit number of recent images
			$page->add('<ul id="content_uploaded_images">');
			foreach($updated_images as $key => $var)
			{
				if($limit == 0)
					break(1);
				$page->add('<li><img src="../images/'.$key.'" alt="" /></li>');
				$limit--;
			}
			$page->add('</ul></div>');
			$page->add('<div class="clear"></div>');

			/*-- END Uploaded Images -----------------------------------------*/
		}
		
		# Display page validation information for admins
		if(($_SESSION['permission_level'] == 1 || $_SESSION['permission_level'] == 2) && $body != '')
		{
			# Create content page to validate
			$validation_page = new page('../theme/template.html');
			$validation_page->title($title);
			$validation_page->add($validation_body);
			$output = $validation_page->output(TRUE);
			
			# Run tidy validation
			$tidy = new tidy();
			$tidy->parseString($output);
			$tidy->CleanRepair();
			
			# Output report
			$page->add('<h2>Validation Report</h2>');
			
			$valid = TRUE;
			
			$page->add('<code>'.nl2br(htmlspecialchars($tidy->errorBuffer)).'</code>');
			if(nl2br(htmlspecialchars($tidy->errorBuffer)) != '');
				$valid = FALSE;
			
			if(stripos($output, '<font'))
			{
				$page->add('<p class="error">Contains font tags (deprecated)</p>');
				$valid = FALSE;
			}
			if(stripos($output, 'align="center"'))
			{
				$page->add('<p class="error">Contains <code>align="center"</code></p>');
				$valid = FALSE;
			}
			
			if($valid)
				$page->add('<p>Things look OK!</p>');
		}

		$page->output();
	}
	else
	{
		$security->redirect();
	}
?>
