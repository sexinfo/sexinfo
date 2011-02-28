<?php
/**********************************************************************//**\file
	Submittal page

	Description: Submittal Form for Q&A
*******************************************************************************/
require_once('./core/sex-core.php');


		

	


	
# Initialise Database Connection
$mysql = mysql_connect($config['dbhost'], $config['dbuser'], $config['dbpass']);
$db = mysql_select_db($config['dbname'], $mysql);

$result = mysql_query("SELECT enableSubmit, downMessage, maxQuestions, maxMessage from sex_question_config limit 1 ");
$res = mysql_fetch_row($result);

$enableSubmit = $res[0];
$downMessage = $res[1];
$maxQuestions = $res[2];
$maxMessage = $res[3];

if ($enableSubmit == 1){
    $page->title('Question Submittal is Down');
    $page->add("<h2><p>We're sorry, the SexAdmins are currently busy trying to get the new site running fully. </p></h2>

<p>Questions should be accepted again within the next couple days. Thank you for your understanding!</p>");
    $page->output();
}
else {
	$query = mysql_query('SELECT * from sex_question where if(DATE_FORMAT(unix_timestamp(),"%w")>4,date_sub(unix_timestamp(),INTERVAL DATE_FORMAT(unix_timestamp(),"%w")-5 DAY),date_sub(unix_timestamp(),INTERVAL DATE_FORMAT(unix_timestamp(),"%w")+2 DAY)) <= question_submission_date');
    //$query = mysql_query('SELECT * from sex_question where if(DATE_FORMAT(curdate(),"%w")>4,date_sub(curdate(),INTERVAL DATE_FORMAT(curdate(),"%w")-5 DAY),date_sub(curdate(),INTERVAL DATE_FORMAT(curdate(),"%w")+2 DAY)) <= date ');
	$quer = mysql_num_rows($query);

    if (count($quer) > $maxQuestions) print $maxMessage;
	else { 
        

	$page = new page();
	 if ($enableSubmit == 2) {
		

		$page->add(' ');
	
	$page->title('Submit A Question');
	
	
	$page->add('<center><div id="questions"><br /><li>Before submitting a question, please try searching your question or browse the <a href="http://www.soc.ucsb.edu/sexinfo/article/frequently-asked-questions"><b><font size="3">Frequently Asked Questions</font></b></a> to see if it has been already answered. 		

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
	$page->add('<a href="http://www.soc.ucsb.edu/sexinfo/article/can-i-get-pregnant-if" <img src="images/pregnant-bucket.jpg" style="position:relative; top:0px; left:60px; right:10px; border:120px; height:80px;" ></a></div></center><br><br>');
	
/*	for($i = 0; $i < 6; $i++)
	{
		$page->add('');
	} */
	
	$page->add(file_get_contents('submit_form.php'));

	$page->output();
 /*       }
   }
} */
?>