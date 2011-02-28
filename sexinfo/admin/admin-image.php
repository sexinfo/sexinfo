<?php
/**********************************************************************//**\file
	Admin Image Uploader

	Description: Allows editors and webmasters to upload images
*******************************************************************************/

	require('../core/sex-core.php');

	# Security function - MUST PASS TRUE FOR ADMIN PAGES!
	$security = new security(TRUE);

	$page = new admin_page('template.html');
	$page->title('Image Uploader');

	if($security->session_logged_in())
	{
		$mysqli = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);
		
		$page->add('<h1>Image Uploader</h1>');

		# Output form
		$page->add('<form action="admin-image.php" method="post" enctype="multipart/form-data">
	<p>Please select image(s) in <b>jpg</b>, <b>png</b>, or <b>gif</b> format from your computer to upload.  A datestamp will automatically be prepended to the filename to prevent duplicate filenames.  For example, <code>my-picture.jpg</code> becomes <code>'.data::format_date(time(), 5).'-my-picture.jpg</code></p>
	
	<p>
		<input type="hidden" name="MAX_FILE_SIZE" value="153600" /><input type="file" name="image1" />&nbsp;Article URL:<input type="text" name="URL1"><br />
		<input type="file" name="image2" />&nbsp;Article URL:<input type="text" name="URL2"><br />
		<input type="file" name="image3" />&nbsp;Article URL:<input type="text" name="URL3"><br />
		<input type="file" name="image4" />&nbsp;Article URL:<input type="text" name="URL4"><br />
		<input type="file" name="image5" />&nbsp;Article URL:<input type="text" name="URL5">
	</p>
	<p><input type="reset"> <input type="submit" value="Upload Images" /></p>
</form>');

		######

		# Process uploaded files / form data
		foreach($_FILES as $key => $var)
		{
			$file_safe = true;

			# Check that form has not been tampered with
			switch($key)
			{
				case 'image1':
				case 'image2':
				case 'image3':
				case 'image4':
				case 'image5':
					break;
				default:
					$file_safe == false;
					break;
			}

			# Check that filetype is an image
			switch($var['type'])
			{
				case 'image/png':
				case 'image/jpeg':
				case 'image/gif':
					break;
				default:
					$file_safe = false;
			}

			# Check that filesize is appropriate, check errors, and check uploaded
			if($var['size'] > 153600 || $var['error'] != 0 || !is_uploaded_file($var['tmp_name']))
			{
				$file_sale = false;
			}

			# If everything checks, save file
			if($file_safe)
			{
				# http://us2.php.net/manual/en/function.move-uploaded-file.php
				move_uploaded_file($var['tmp_name'], '../images/'.data::format_date(time(), 5).'-'.str_replace(' ', '_', $var['name']));
			}

			if(!$file_safe && $var['name'] != '')
			{
				$upload_error[] = $var['name'];
			}
		}

		# Output error message if an upload fails
		if(isset($upload_error))
		{
			$page->add('<div id="upload_error">There was an error uploading the following file(s): ');
			$first = true;

			foreach($upload_error as $var)
			{
				if($first)
				{
					$first = false;
				}
				else
				{
					$page->add(', ');
				}
				
				$page->add('<b>'.$var.'</b>');
			}
			$page->add('</div>');
		}
		
		# Check Article URL
		if(isset($_POST['URL1'])) $URL1 = $_POST['URL1'];
		else $URL1 = "(Not Entered)";


		######

		# Create list of recently-uploaded images
		$page->add('<h1>Recently-Uploaded Images*</h1>');

		$updated_images = array();

		# Grab full list of images from disk
		foreach(scandir('../images/') as $var)
		{
			# Don't display hidden files or directories
			if($var[0] != '.' and is_file('../images/'.$var))
			{
				$file = '../images/'.$var;

				# http://us2.php.net/manual/en/function.filemtime.php
				if(filemtime($file) > time() - 2592000) # 2592000 = 30 days
				{
					$updated_images[$var] = filemtime($file);
				}
			}
		}
		arsort($updated_images);

		# Output recent images
		$limit = 20; # limit number of recent images
		
		$page->add('<ul id="uploaded_images">');
		foreach($updated_images as $key => $var)
		{
			if($limit == 0)
				break(1);

			$page->add('<li><a href="/sexinfo/images/'.$key.'"><img src="../images/'.$key.'" alt="" /></a></li>');

			$limit--;
		}
		$page->add('</ul>');
		$page->add('<div class="clear"></div>');

		$page->add('<p>*"Recently-updated" constitutes any images uploaded or changed in the last 30 days, limited to 20 items.</p>');
	}

	$page->output();
?>
