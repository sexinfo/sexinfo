<?php
/**********************************************************************//**\file
	Incoming Question and Answer Viewer

	Description: View new questions and answers, and print them out.
*******************************************************************************/

	require('../core/sex-core.php');

	# Security function - MUST PASS TRUE FOR ADMIN PAGES!
	$security = new security(TRUE);

	$page = new admin_page('template.html');

	# Set page titles
	$page->title('Incoming Q&amp;A - UCSB SexInfo Admin');
	$page->add('<h1>Incoming Questions <span class="date">for '.date('F j, Y').'</span></h1>');

	# Show link to config editor for webmasters / developers
	if($security->permission_level() === 1 || $security->permission_level() === 2)
	{
		$page->add('<p>To turn the Q&amp;A form on/off or change the messages, <a href="admin-config.php">click here</a>.');
	}
	
	# Initialize database connection
	$mysqli = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);
	
	# Set limit for # of questions
	if(isset($_POST['limit']))
	{
		# Injection protection
		intval(strcmp(intval($_POST['limit']), $_POST['limit']) == 0) ? $limit = intval($_POST['limit']) :	exit('Error') ;
	}
	else
	{
		# Pull from config
		if($result = $mysqli->query("SELECT maxQuestions FROM sex_question_config"))
		{
			$row = $result->fetch_row();
			$limit = intval($row[0]);
		}
		else
			$limit = 15;
	}
	
	# Set Where clause to pick out individual entry
	$where = '';
	$question_id = NULL;
	if(isset($_POST['question-id']) && $_POST['question-id'] != '')
	{
		intval(strcmp(intval($_POST['question-id']), $_POST['question-id']) == 0) ? $question_id = intval($_POST['question-id']) : exit('Error') ;
		$where = 'WHERE question_id = '.$question_id;
	}
	
	# Output Form for custom Limit / View
	$page->add('
		<form id="question-limit" action="admin-question.php" method="post">
			<div style="float: left;">Currently viewing the last <input name="limit" type="text" value="'.$limit.'" maxlength="2" style="width: 2em; text-align: center;" /> questions.  <input type="submit" value="Update" /></div>
			<div style="float: right;">View a specific item: <input name="question-id" type="text" value="'.$question_id.'" maxlength="5" style="width: 4em; text-align: center;" /><input type="submit" value="View" /></div>
			<div style="clear: both; margin-bottom: 1em;"></div>
		</form>');
	
	# Get questions
	$result = $mysqli->query("
		SELECT question_id, question_submission_date, question_body,
			question_guest_email, question_guest_age, question_guest_gender,
			question_wants_reply, question_guest_location, question_guest_method
		FROM sex_question
		$where
		ORDER BY question_submission_date DESC
		LIMIT $limit
	");
	
	$alt = TRUE;
	while($row = $result->fetch_assoc())
	{
		# Open question container, add alt class for alternating rows
		$page->add('<div class="question');
		if($alt)
			$page->add(' alt');
		$page->add('">');
		$alt = !$alt;

		# Begin metadata table
		$page->add('<table class="question"><tr class="meta">');

		# Output entry index and timestamp
		$page->add('<td class="id">'.$row['question_id'].'</td><td class="date">'.data::format_date($row['question_submission_date'], 4).'</td>');

		# Output guest personal info
		$page->add('<td class="gender">');

		# Gender
		switch ($row['question_guest_gender'])
		{
			case 2: $page->add('Female');
				break;
			case 1: $page->add('Male');
				break;
			default: $page->add('Unspecified');
				break;
		}

		$page->add(', '.$row['question_guest_age'].' from '.$row['question_guest_location'].'</td>');

		# If user wants a reply, highlight green and linkify e-mail, otherwise red and unlinked
		if($row['question_wants_reply'] == 1)
		{
			$page->add('<td class="reply"><a href="mailto:'.$row['question_guest_email'].'">'.$row['question_guest_email'].'</a></td>');
			## $page->add('<td class="reply">Please Reply</td>');
		}
		else
		{
			$page->add('<td class="noreply">'.$row['question_guest_email'].'</td>');
			## $page->add('<td class="noreply">Do Not Reply</td>');
		}

		/*
		# Output "how did you find us?" info
		$page->add('<td class="source">');
          switch ($row['question_guest_method'])
        {
                case 0: $page->add('Did not state');
                    break;
                case 1: $page->add('Search Engine');
                    break;
                case 2: $page->add('Friend / Family');
                    break;
                case 3: $page->add('Referral from another website');
                    break;
                case 4: $page->add('Trained Professional / Therapist');
                    break;
                case 5: $page->add('Class');
                    break;
                case 6: $page->add('Other');
                    break;
        }
        $page->add('</td>');
        */
		
		# Output question body, with hack to remove escape garbage
		$page->add('</tr>
					<tr>
						<td colspan="6" class="body">'.str_replace('\\\'', '\'', htmlentities($row['question_body'])).'</td>
					</tr>
				</table>');

		# Close question container
		$page->add('</div>');
	}

	$page->output();
?>