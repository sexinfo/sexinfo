<?php

/**
 * Site Configuration
 *
 * Description: Define global variables used on the site.
 */
$config = config::database();

class config
{

	public static function database()
	{
		if( $_SERVER['SERVER_NAME'] == 'www.soc.ucsb.edu' )
		{
			$config = array(
				'dbhost' => 'database.lsit.ucsb.edu',
				'dbuser' => 'sexweb00m',
				'dbpass' => '249APWan',
				'dbname' => 'sexweb00'
			);
		}
		else
		{
			$config = array(
				'dbhost' => 'localhost',
				'dbuser' => 'sexweb00m',
				'dbpass' => '249APWan',
				'dbname' => 'sexweb00'
			);
		}
		return $config;
	}

	public static function admin_navigation( $permissions = NULL )
	{
		if( $permissions == 1 || $permissions == 2 )
		{
			$array = array(
				'Home' => 'index.php',
				'Incoming Q&amp;A' => 'admin-question.php',
				'Content Editor' => 'admin-content.php',
				'Categories' => 'admin-category.php',
				'Search' => 'admin-search.php',
				'User Management' => 'admin-user.php',
				'Issue Tracker' => 'admin-bugs.php',
				'Tools' => 'admin-tools.php',
			);
		}
		elseif( $permissions == 3 )
		{
			$array = array(
				'Home' => 'index.php',
				'Incoming Q&amp;A' => 'admin-question.php',
				'Content Editor' => 'admin-content.php',
				'Image Uploader' => 'admin-image.php',
				'Search' => 'admin-search.php',
				'Old Pages' => 'admin-old.php',
				'Issue Tracker' => 'admin-bugs.php',
				'Templates' => 'admin-templates.php',
			);
		}
		else
		{
			$array = array(
				'Home' => 'index.php',
			);
		}

		return $array;
	}
}

?>