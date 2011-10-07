<?php
	if($_SERVER['SERVER_NAME'] != 'www.soc.ucsb.edu')
		phpinfo();
	else {
		header("Location: http://www.soc.ucsb.edu/sexinfo/");
		exit;
	}
?>
