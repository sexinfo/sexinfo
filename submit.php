<?php

	// QUESTIONS ENABLED
	// header( 'Location: http://www.soc.ucsb.edu/sexinfo/submit_form.php' ) ;


	// header( 'Location: http://www.soc.ucsb.edu/sexinfo/submit_form.php' ) ;
	
	require_once('./core/sex-core.php');
	
		$page = new page();
	$page->add(' ');
	
	$page->title('Ask the Sexperts - SexInfo Online');
	
	$page->add('<br /><br /><center><div id="questions"><br /><li>Before submitting a question, please try searching your question or browse the <a href="http://www.soc.ucsb.edu/sexinfo/article/frequently-asked-questions"><b><font size="3"><br />Frequently Asked Questions</font></b></a> to see if it has been already answered. 		

				<form action="http://www.google.com/cse" method="get">
					<div>
					<font size="3"><b><br />Search: </b></font> 
					<input type="hidden" name="cx" value="016229344819287620609:57qdqcwlna8" />
					<input type="hidden" name="ie" value="UTF-8" />

					<input id="search-query" type="text" name="q" maxlength="255" />
					</div>
				</form>
				</li><br /> ');


	$page->add('<li>It may take 2-4 weeks to receive a response.</li><br />');
	$page->add('<li>If you think you or your partner may be <a href="http://www.soc.ucsb.edu/sexinfo/article/can-i-get-pregnant-if"><b><font size="3">pregnant</font></b></a>, please click <a href="http://www.soc.ucsb.edu/sexinfo/article/can-i-get-pregnant-if"><b><font size="3" >here.</font></b></a></li><br /></ul>');	
	$page->add('<a href="http://www.soc.ucsb.edu/sexinfo/category/stds" <img src="images/std-bucket.jpg" style="position:relative; left:-60px; height:80px;" ></a>');
	$page->add('<a href="http://www.soc.ucsb.edu/sexinfo/category/rape-info" <img src="images/rape-bucket.jpg" style="position:relative; left:0px; right:0px; border:120px; height:80px;" ></a>');
	$page->add('<a href="http://www.soc.ucsb.edu/sexinfo/article/can-i-get-pregnant-if" <img src="images/pregnant-bucket.jpg" style="position:relative; top:0px; left:60px; right:10px; border:120px; height:80px;" ></a></div></center>');
	for($i = 0; $i < 6; $i++)
	{
		$page->add('');
	}
	$page->add(file_get_contents('submit_form.php'));
	$page->add('<br />
	');

	$page->output();
	

	
	/*require_once('./core/sex-core.php');

	$page = new page();
	
	$page->title('Ask the Sexperts - SexInfo Online');
	
	$page->add('<center><br /><h1>Ask the Sexperts</h1><br /><br /> ');
	$page->add('
	
	
	<div class="question-box"><br />
	<p><b><font size="3">We\'re sorry but the Sexperts are away for Summer break, we\'ll be back in September.</font></b></p>
	<br /><br /><li style="width:400px">Please try searching your question or browse the <a href="http://www.soc.ucsb.edu/sexinfo/article/frequently-asked-questions"><b><font size="2">Frequently Asked Questions</font></b></a> to see if it has been already answered. 		

				<form action="http://www.google.com/cse" method="get">
					<div>
					<font size="2"><b><br />Search: </b></font> 
					<input type="hidden" name="cx" value="016229344819287620609:57qdqcwlna8" />
					<input type="hidden" name="ie" value="UTF-8" />

					<input id="search-query" type="text" name="q" maxlength="255" />
					</div>
				</form>
				</li><br /><br />
	
	
	
	
	
	
	<a href="http://www.soc.ucsb.edu/sexinfo/category/stds" <img src="images/std-bucket.jpg" style="position:relative; left:-60px; height:80px;" ></a>
	<a href="http://www.soc.ucsb.edu/sexinfo/category/rape-info" <img src="images/rape-bucket.jpg" style="position:relative; left:0px; right:0px; border:120px; height:80px;" ></a>
	<a href="http://www.soc.ucsb.edu/sexinfo/article/can-i-get-pregnant-if" <img src="images/pregnant-bucket.jpg" style="position:relative; top:0px; left:60px; right:10px; border:120px; height:80px;" ></a><br />
	
	&nbsp;
	</div>
	</center>
	<br />
	
	&nbsp;
	');*/
	
	$page->output();
	
	
?>