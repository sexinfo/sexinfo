<?php
/**********************************************************************//**\file
	Content Data Object

	Description:  Common class for content querying and output.
*******************************************************************************/

	class content
	{
		# Objects
		private $security;
		private $page;
		private $mysqli;
		

		# Variables
		private $content_type_slug;
		private $content_type_id;
		private $content_type_name;
		private $content_type_decription;
		private $slug = NULL;

		public function __construct($type, $content = '')
		{
			# Set content type
			$this->content_type_slug = $type;

			# Get config data
			$config = config::database();

			# Initialize Objects
			$this->security = new security();
			$this->page = new page();
			$this->mysqli = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);

			# Sanitize and set slug if exists
			$uri_details = explode('/', $_SERVER['REQUEST_URI']);
			
            if (strpos($uri_details[3], 'refid')) {
                header('Location: ' . "/sexinfo/$uri_details[3]");
                exit();
            }
            if (strpos($uri_details[3], 'article')) {
                $rep = (strpos($uri_details[3], 'article'));
                $uri_details[3] = $arturl;
                
                 $headernew = substr_replace($arturl, '', 0, $rep);
                header('Location: ' . "/sexinfo/$headernew");
                exit();
            }
            /*if (strpos($uri_details[3], 'article')){

                    $arturl = $uri_details[3];

                    $ref_cat = substr($arturl, 9, 13);
                    //$ref_id = substr($arturl, 20, 23);

                    $mysqli = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);
                    $slug = $mysqli->real_escape_string($ref_cat);
                   	$id = $mysqli->real_escape_string($ref_id);
                    
                    $result = $mysqli->query("
                    SELECT content_slug, category_type
                    FROM sex_content
                    JOIN sex_legacy
                    ON legacy_content_id = content_id
                    WHERE legacy_hash_id = '$slug'
                    LIMIT 1
		");
                    $row = $result->fetch_assoc();
                    $row['content_slug'] = $add;
                    $row['category_type'] = $prefix;

                    if ($prefix == 1) {
                        $pre = "article";
                    }

                    if ($prefix == 2) {
                        $pre = "question";
                    }

                    header('Location: ' . "/sexinfo/$prefix/$add");
                    exit();

            }*/

            if(isset($uri_details[3]))
			{
				$this->slug = $this->mysqli->real_escape_string(data::slug_check($uri_details[3]));
			}
			/*if(isset($_GET['slug']))
			{
				$this->slug = $this->mysqli->real_escape_string(data::slug_check($_GET['slug']));
			}*/

			# Get data for this page
			$this->content_get_data();

			# Add type-specific navigation menus
			$nav = new nav();
			$this->page->navigation($nav->output($this->content_type_id));

			#if(isset($_GET['slug']))
			#	$this->content_body();
			if(isset($uri_details[3]) && $uri_details[3] != '')
				$this->content_body();
			else
				$this->content_index();

			# Output Page
			$this->page->output();
		}

		public function __destruct()
		{
			$this->mysqli->close();
		}

/*******************************************************************************
	Private Functions
*******************************************************************************/

		private function content_get_data()
		{
			$result = $this->mysqli->query("
				SELECT type_id, type_name, description
				FROM sex_type
				WHERE type_slug = '$this->content_type_slug'
			");

			$row = $result->fetch_assoc();
			$this->content_type_id = $row['type_id'];
			$this->content_type_name = $row['type_name'];
			$this->content_type_description = $row['description'];

			$result->close();
		}

/*******************************************************************************
	Public Functions
*******************************************************************************/

		/**
		 * Display content body (assumes slug is set)
		 */
		public function content_body()
		{
			$result = $this->mysqli->query("
				SELECT content_id, content_title, content_body, content_published, content_modified, content_slug, content_title_in_body, content_abstract
				FROM sex_content
				WHERE content_slug = '$this->slug' AND content_type = '$this->content_type_id' AND content_is_published = 1
				LIMIT 1
			");
			if($row = $result->fetch_assoc())
			{
				# Temporarily disable buttons for non-functional features
				#$this->page->add('<div id="toolbox"><a href="#" class="button" id="problem" title="Report a problem"></a>');
				$this->page->add('<div id="toolbox">');
				if($this->security->session_logged_in())
				{
					$this->page->add('<a href="/sexinfo/admin/admin-content.php?action=edit&amp;slug='.$row['content_slug'].'" class="button" id="edit"title="Edit this page"></a><!--<a href="#" class="button" id="zap" title="Zap this item"></a><a href="#" class="button" id="favorite" title="Mark as Sexpert\'s Choice">--></a>');
					$this->page->head('<style type="text/css">#content h1, #content h2.title{background-color:#4b4;}</style>');
				}
				$this->page->add('</div>');
				$this->page->add('<div class="clear"></div>'); # CSS fix due to inconsistent article formatting
				$this->page->title($row['content_title']);
				
				# Add header, if not in body
				if($row['content_title_in_body'] == 0)
					$this->page->add('<h1>'.$row['content_title'].'</h1>');
				
				# Add abstract
				if(!is_null($this->page->add($row['content_abstract'])) && $this->page->add($row['content_abstract']) != '')
				{
					$this->page->add('<div class="abstract">'.$row['content_abstract'].'</div>');
				}
				
				# Remove Curly Quotes + Add body
				$this->page->add(data::cleaner($row['content_body']));

				# Add Metadata
				$this->page->add('<div class="metadata">Created '.data::format_date($row['content_published'], 1));
				if(data::format_date($row['content_published'], 1) != data::format_date($row['content_modified'], 1))
				{
					$this->page->add(', last updated '.data::format_date($row['content_modified'], 1));
				}
				$this->page->add('</div>');
			}
			else
			{
				error::code(404, __LINE__);
			}
		}

		/**
		 * Display index for type
		 */
		public function content_index()
		{
			# Do 5+15 with summaries for first 5 and meta data for the rest
			$this->page->title($this->content_type_name);
			$this->page->add('<h1>SexInfo '.$this->content_type_name.' Listing</h1>');
			$this->page->add($this->content_type_description. '<br />');

			$this->page->add('<h3>Latest '.$this->content_type_name.'s</h3><br />');
			$this->page->add('<div id="index">');

			# Summaries
			$result = $this->mysqli->query("
				SELECT content_slug, content_title, content_published, content_body
				FROM sex_content
				WHERE content_type=$this->content_type_id AND content_is_published = 1
				ORDER BY content_published DESC
				LIMIT 5;
			");

			while($row = $result->fetch_assoc())
			{
				$this->page->add('<h3 class="post-title-small left"><a href="/sexinfo/'.$this->content_type_slug.'/'.$row['content_slug'].'">'.$row['content_title'].'</a></h3> <h3 class="post-content" style="font-size:1.5em">              &nbsp;&nbsp;&nbsp; '.data::format_date($row['content_published'], 1).'</h3>');
				$this->page->add('<p>'.substr(strip_tags($row['content_body']), 0, 400).'&hellip; <a href="/sexinfo/'.$this->content_type_slug.'/'.$row['content_slug'].'">(read more)</a></p>');
			}

			$result->close();

			# One-liners
			$result = $this->mysqli->query("
				SELECT content_slug, content_title, content_published
				FROM sex_content
				WHERE content_type=$this->content_type_id
				ORDER BY content_published DESC
				LIMIT 5,15;
			");

			$alt = TRUE;
			$this->page->add('');
			while($row = $result->fetch_assoc())
			{
				$alt ? $this->page->add('') : $this->page->add('') ;
				$this->page->add('<h3 class="post-content" style="font-size:1.3em"><a href="/sexinfo/'.$this->content_type_slug.'/'.$row['content_slug'].'">'.$row['content_title'].' <span class="date"> - '.data::format_date($row['content_published'], 1).'</span></a></h3>');
				$alt = !$alt;
			}
			$this->page->add('');
			$this->page->add('</div>');

			$result->close();
		}
	}
?>