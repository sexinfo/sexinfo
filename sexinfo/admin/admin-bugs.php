<?php
/**********************************************************************//**\file
	Bugs

	Description: Allows users and devs to report and track bugs on SexInfo
*******************************************************************************/

	require('../core/sex-core.php');
	data::strip_magic_quotes();

	# Security function - MUST PASS TRUE FOR ADMIN PAGES!
	$security = new security(TRUE);

	$page = new admin_page('template.html');

	if($security->session_logged_in())
	{
		$db = new database();

		/*-- View An Issue ---------------------------------------------------*/
		if(isset($_GET['view']) && is_numeric($_GET['view']))
		{
			$bug = new sex_bug(intval($_GET['view']));

			$page->add('<h1>Issue id <span style="color: #82B94A;">'.$bug->get_id().'</span> &bull; '.$bug->get_title().'</h1>');
			$page->add('<table style="width: auto; background: none;">
				<tr><td>Reported by</td><td>'.$bug->get_reporter_name().'</td></tr>
				<tr><td>Reported on</td><td>'.data::format_date($bug->get_added(), 1).'</td></tr>
				<tr><td>Updated on</td><td>'.data::format_date($bug->get_updated(), 1).'</td></tr>
				<tr style="border-top: 1px solid #aaa;"><td>Type</td><td title="'.data::bug_type($bug->get_type(), true).'">'.data::bug_type($bug->get_type()).'</td></tr>
				<tr><td>Priority</td><td title="'.data::bug_priority($bug->get_priority(), true).'">'.data::bug_priority($bug->get_priority()).'</td></tr>
				<tr><td>Status</td><td title="'.data::bug_status($bug->get_status(), true).'">'.data::bug_status($bug->get_status()).'</td></tr>');
			if($bug->get_url() != '')
				$page->add('<tr style="border-top: 1px solid #aaa;"><td>URL</td><td>'.$bug->get_url().'</td></tr>');
			if($bug->get_useragent() != '')
				$page->add('<tr style="border-bottom: 1px solid #aaa;"><td>User-agent</td><td>'.$bug->get_useragent().'</td></tr>');
			if($bug->get_assigned_to_id() != 0)
				$page->add('<tr><td>Assigned to</td><td><a href="admin-bug.php?user='.$bug->get_assigned_to_id().'">'.$bug->get_assigned_to_name().'</a></td></tr>');
			if($bug->get_fixer_id() != 0)
				$page->add('<tr><td>Fixed by</td><td><a href="admin-bug.php?user='.$bug->get_fixer_id().'">'.$bug->get_fixer_name().'</a></td></tr>');

			$page->add('</table>');
			if($_SESSION['permission_level'] == 1 || $bug->get_reporter_id() == $_SESSION['user_id'])
				$page->add('<p><a href="admin-bugs.php?edit='.$bug->get_id().'">Edit this issue</a></p>');

			$page->add('<h4>Description</h4><div class="issue_body"><p>'.nl2br($bug->get_description()).'</p></div>');

			/*# Comment Form
			$page->add('<h1>Comments</h1>');
			$page->add('');*/
		}
		/*-- Add Or Edit An Issue --------------------------------------------*/
		elseif(isset($_GET['new']) || isset($_GET['edit']))
		{
			$page->add('<h1>Report Issue</h1>');

			$warning_title = '';
			$warning_description = '';
			$warning_url = '';

			# If making a new issue, set stuff based on the button that was pressed.
			if(isset($_GET['new']))
			{
				$bug = new sex_bug();

				if(isset($_POST['new_issue']))
				{
					if(!$bug->set_title($_POST['bug_title']))
						$warning_title = '<span class="error">Must be between 5 and 60 characters</span>';
					if(!$bug->set_description($_POST['bug_description']))
						$warning_description = '<span class="error" style="font-weight: normal;">Must be at least 10 characters</span>';
					if(!$bug->set_url($_POST['bug_url']))
						$warning_url = '<span class="error">Must be less than 120 characters</span>';
					
					$bug->set_type(intval($_POST['bug_type']));
					$bug->set_useragent($_POST['bug_useragent']);

					$bug->set_reporter_id($_SESSION['user_id']);

					if($_SESSION['permission_level'] == 1)
					{
						$bug->set_status(intval($_POST['bug_status']));
						$bug->set_priority(intval($_POST['bug_priority']));
						$bug->set_assigned_to_id(intval($_POST['bug_assign_to']));
					}

					if(is_null($bug->error))
					{
						$bug->save();
						$page->add('<p class="notice">Your entry was saved successfully.</p>');
						$page->head('<meta http-equiv="refresh" content="2;url=admin-bugs.php" />');
						$page->output();
						die();
					}
					else
					{
						$page->add('<p class="warning">There was a problem saving your entry; please see the details below.</p>');
					}
				}
				else
				{
					if($_GET['new'] == 'Report a Problem')
						$bug->set_type(1);
					if($_GET['new'] == 'Make a Suggestion')
						$bug->set_type(2);
					if($_GET['new'] == 'Add Todo')
						$bug->set_type(3);

					$bug->set_useragent($_SERVER['HTTP_USER_AGENT']);
					if(!strpos($_SERVER['HTTP_REFERER'], 'admin-bugs.php'))
						$bug->set_url($_SERVER['HTTP_REFERER']);
					else
						$bug->set_url('');
					$bug->set_priority(0);
				}
			}
			# If editing an issue, load object
			if(isset($_GET['edit']))
			{
				if(is_numeric($_GET['edit']) && $bug = new sex_bug(intval($_GET['edit'])))
				{
					if($bug->get_reporter_id() == $_SESSION['user_id'] || $_SESSION['permission_level'] == 1)
					{
						# Save, if appropriate
						if(isset($_POST['edit_issue']))
						{
							if(!$bug->set_title($_POST['bug_title']))
								$warning_title = '<span class="error">Must be between 5 and 60 characters</span>';
							if(!$bug->set_description($_POST['bug_description']))
								$warning_description = '<span class="error" style="font-weight: normal;">Must be at least 10 characters</span>';
							if(!$bug->set_url($_POST['bug_url']))
								$warning_url = '<span class="error">Must be less than 120 characters</span>';
							
							$bug->set_type(intval($_POST['bug_type']));

							if($_SESSION['permission_level'] == 1)
							{
								$bug->set_status(intval($_POST['bug_status']));
								$bug->set_priority(intval($_POST['bug_priority']));
								$bug->set_assigned_to_id(intval($_POST['bug_assign_to']));
							}

							if(is_null($bug->error))
							{
								$bug->save();
								$page->add('<p class="notice">Your entry was saved successfully.</p>');
								$page->head('<meta http-equiv="refresh" content="2;url=admin-bugs.php" />');
								$page->output();
								die();
							}
							else
							{
								$page->add('<p class="warning">There was a problem saving your entry; please see the details below.</p>');
							}
						}
					}
					else
					{
						$page->add('<p class="warning">You do not have permission to edit that issue.</p>');
						$bug = new sex_bug();
					}
				}
				else
				{
					$page->add('<p class="warning">An invalid bug_id has been specified.</p>');
				}
			}
			
			$page->add('
				<p>Use this page to report a bug or request a feature enhancement.  Bugs are problems, i.e. things that are supposed to be working but aren\'t.  Feature enhancements change the way existing features work (improving workflow, for instance).</p>
			');
			$page->add('<form method="post" action="admin-bugs.php?'.$_SERVER['QUERY_STRING'].'">
				<table class="form">
					<tr><td><label for="bug_title">Title</label></td><td><input id="bug_title" name="bug_title" type="text" value="'.$bug->get_title().'" maxlength="60" style="width: 30em;" /> '.$warning_title.'</td></tr>
					<tr><td><label for="bug_url">URL</label></td><td><input id="bug_url" name="bug_url" type="text" value="'.$bug->get_url().'" maxlength="120" style="width: 30em;" /> '.$warning_url.'</td></tr>
					<input name="bug_useragent" type="hidden" value="'.$bug->get_useragent().'" maxlength="120" />
					<input name="bug_id" type="hidden" value="'.$bug->get_id().'" />
				</table>

				<p><label for="bug_type">Type:</label>
				<select id="bug_type" name="bug_type">
			');
			foreach(array(0 => 'Unspecified', 1 => 'Bug / Problem', 2 => 'Enhancement', 3 => 'Todo') as $key => $var)
			{
				if($bug->get_type() == $key)
					$selected = ' selected="selected"';
				else
					$selected = '';
				$page->add('<option value="'.$key.'" title="'.data::bug_type($key, true).'" '.$selected.'>'.data::bug_type($key).'</option>');
			}
			$page->add('</select>');

			# Extra fields for developers
			if($_SESSION['permission_level'] == 1)
			{
				$page->add(' <label for="bug_priority">Priority:</label><select id="bug_priority" name="bug_priority">');
				
				foreach(array(0 => 'Unspecified', 1 => 'Low', 2 => 'Medium', 3 => 'High', 4 => 'Critical') as $key => $var)
				{
					if($bug->get_priority() == $key)
						$selected = ' selected="selected"';
					else
						$selected = '';
					$page->add('<option value="'.$key.'" title="'.data::bug_priority($key, true).'" '.$selected.'>'.data::bug_priority($key).'</option>');
				}

				$page->add('</select>
					<label for="bug_status">Status:</label>
					<select id="bug_status" name="bug_status">');
				
				foreach(array(1 => 'Open', 2 => 'Assigned', 3 => 'Fixed', 4 => 'Closed') as $key => $var)
				{
					if($bug->get_status() == $key)
						$selected = ' selected="selected"';
					else
						$selected = '';
					$page->add('<option value="'.$key.'" title="'.data::bug_status($key, true).'" '.$selected.'>'.data::bug_status($key).'</option>');
				}
				
				$page->add('</select>
					<label for="bug_assign_to">Assign To:</label>
					<select id="bug_assign_to" name="bug_assign_to">');
				$page->add('<option value="0">Nobody</option>');

				$db->query("SELECT user_id, Concat(user_first_name, ' ', user_last_name) as user_full_name FROM sex_user WHERE user_permission_level = 1 ORDER BY user_last_name ASC");
				$result = $db->multi_result();

				foreach($result as $var)
				{
					if($var['user_full_name'] != 'John Baldwin')
					{
						if($bug->get_assigned_to_id() == $var['user_id'])
							$selected = ' selected="selected"';
						else
							$selected = '';
						$page->add('<option value="'.$var['user_id'].'" '.$selected.'>'.$var['user_full_name'].'</option>');
					}
				}
				$page->add('</select>');
			}
			$page->add('</p>
				<h4><label for="bug_description">Detailed Description</label> '.$warning_description.'</h4>
				<p><textarea id="bug_description" name="bug_description" rows="6" cols="80">'.$bug->get_description().'</textarea></p>');
			if(isset($_GET['new']))
				$page->add('<p style="text-align: center;"><input name="new_issue" type="submit" value="Submit Issue" /></p>');
			if(isset($_GET['edit']))
				$page->add('<p style="text-align: center;"><input name="edit_issue" type="submit" value="Submit Issue" /></p>');
			$page->add('</form>');
		}
		/*-- User Overview ---------------------------------------------------*/
		elseif(isset($_GET['user']))
		{

		}
		/*-- Output Summary Form ---------------------------------------------*/
		else
		{
			$page->add('<h1>Issue Tracking</h1>');
			$page->add('<p><i>"It\'s not a bug, it\'s a feature!"</i></p><p>If you experience a technical problem or other unexpected behavior while using the site, please <a href="admin-bugs.php?new">let us know about it</a>.  You may also use this page to request feature enhancements or make suggestions.</p>');

			$page->add('<form style="none" method="get" action="admin-bugs.php"><p style="text-align: center;"><input type="submit" name="new" value="Report a Problem" /> <input type="submit" name="new" value="Make a Suggestion" />');
			if($_SESSION['permission_level'] == 1)
				$page->add(' <input name="new" type="submit" value="Add Todo" /> <!--<input name="user" type="submit" value="View My Stuff" />-->');
			$page->add('</p></form>');

			$db->query("SELECT bug_id, bug_title, bug_status, bug_type, bug_priority, bug_added, bug_updated FROM sex_bug WHERE bug_status != 3 AND bug_status != 4 ORDER BY bug_priority DESC");
			$result = $db->multi_result();

			# Output bugs
			if($result)
			{
				$page->add('<h3>Open Issues</h3>
				<table id="open_issues">
				<col style="width: 3em" />
				<col />
				<col style="width: 8em" />
				<col style="width: 8em" />
				<col style="width: 8em" />
				<col style="width: 8em" />
				<tr>
					<th>id</th>
					<th>Title</th>
					<th>Priority</th>
					<th>Status</th>
					<th>Type</th>
					<th>Activity</th>
				</tr>');

				foreach($result as $var)
				{
					$page->add('<tr class="priority'.$var['bug_priority'].'">
						<td><a href="admin-bugs.php?view='.$var['bug_id'].'">'.$var['bug_id'].'</a></td>
						<td style="overflow: hidden;"><a href="admin-bugs.php?view='.$var['bug_id'].'">'.$var['bug_title'].'</a></td>
						<td title="'.data::bug_priority($var['bug_priority'], true).'"><a href="admin-bugs.php?view='.$var['bug_id'].'">'.data::bug_priority($var['bug_priority']).'</a></td>
						<td title="'.data::bug_status($var['bug_status'], true).'"><a href="admin-bugs.php?view='.$var['bug_id'].'">'.data::bug_status($var['bug_status']).'</a></td>
						<td title="'.data::bug_type($var['bug_type'], true).'"><a href="admin-bugs.php?view='.$var['bug_id'].'">'.data::bug_type($var['bug_type']).'</a></td>
						<td><a href="admin-bugs.php?view='.$var['bug_id'].'">'.data::format_date(max($var['bug_added'], $var['bug_updated']), 5).'</a></td>
					</tr>');
				}

				$page->add('</table>');
			}
			else
			{
				$page->add('<p>There are no open issues in the issue tracker, or there\'s a database problem.  Try creating one.</p>');
			}

			$db->query("SELECT bug_id, bug_title, bug_status, bug_type, bug_priority, bug_added, bug_updated FROM sex_bug WHERE bug_status = 3 OR bug_status = 4 ORDER BY bug_updated DESC LIMIT 7");
			$result = $db->multi_result();

			# Output resolved issues
			if($result)
			{
				$page->add('<h3 title="Latest 7 issues">Resolved Issues</h3>
				<table id="closed_issues">
				<col style="width: 3em" />
				<col />
				<col style="width: 8em" />
				<col style="width: 8em" />
				<col style="width: 8em" />
				<tr>
					<th>id</th>
					<th>Title</th>
					<th>Status</th>
					<th>Type</th>
					<th>Resolved</th>
				</tr>');

				foreach($result as $var)
				{
					$page->add('<tr class="priority'.$var['bug_priority'].'">
						<td><a href="admin-bugs.php?view='.$var['bug_id'].'">'.$var['bug_id'].'</a></td>
						<td style="overflow: hidden;"><a href="admin-bugs.php?view='.$var['bug_id'].'">'.$var['bug_title'].'</a></td>
						<td title="'.data::bug_status($var['bug_status'], true).'"><a href="admin-bugs.php?view='.$var['bug_id'].'">'.data::bug_status($var['bug_status']).'</a></td>
						<td title="'.data::bug_type($var['bug_type'], true).'"><a href="admin-bugs.php?view='.$var['bug_id'].'">'.data::bug_type($var['bug_type']).'</a></td>
						<td><a href="admin-bugs.php?view='.$var['bug_id'].'">'.data::format_date(max($var['bug_added'], $var['bug_updated']), 5).'</a></td>
					</tr>');
				}

				$page->add('</table>');
			}

			# Output developer summary
			/*if($_SESSION['permission_level'] == 1)
			{
				$page->add('<h3>Developer Summary</h3>
				<table id="dev_summary">
				<col style="width: 8em" />
				<col style="width: 8em" />
				<col style="width: 8em" />
				<col style="width: 8em" />
				<tr>
					<th>Name</th>
					<th>Assigned</th>
					<th>Fixed</th>
					<th>Activity</th>
				</tr>');
				$page->add('</table>');
			}*/
		}


		
	}

	$page->output();
?>