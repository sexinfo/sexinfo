<?php

	// Successful question submit page
	
	require_once('./core/sex-core.php');
	
		$page = new page();
	$page->add(' ');
	
	$page->title('Submit A Question');
	
	$page->add('<br /><br /><center><h3>Thank you for submitting your question!</h3> <br /><br /><font size="3">It may take up to 2-4 weeks to get a response (but usually we\'re very fast!).</font><br /><br /><br /><br /><br />
       
       
       <div id="questions">In the mean time, feel free to browse our <a href="http://www.soc.ucsb.edu/sexinfo/article/frequently-asked-questions"><b><font size="3"><br />Frequently Asked Questions</font></b></a>.<br /><br /><br /><br /><br /><br /><br /><br />');

	$page->output();

?>