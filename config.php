<?php
	# Configuration array
	if($_SERVER['SERVER_NAME'] == 'www.soc.ucsb.edu')
	$config = array(
		'dbhost' => 'database.lsit.ucsb.edu',
		'dbuser' => 'sexweb00m',
		'dbpass' => '249APWan',
		'dbname' => 'sexweb00'
	);
	else
	$config = array(
		'dbhost' => 'localhost',
		'dbuser' => 'sexweb00m',
		'dbpass' => '249APWan',
		'dbname' => 'sexweb00'
	);
?>
