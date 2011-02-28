<?php
/**********************************************************************//**\file
	152A Index

	Description: Displays information for 152A Students
*******************************************************************************/

	require('core/sex-core.php');

	$page = new page();
	$security = new security();
	
	$page->title('152A Exam Review Schedule');
	$page->add('<h1>152A Exam Review Schedule</h1><br /><br />');
	$page->add('
			<table class="exams" border="1" width="600px" style="border-style:solid; border-width:3px">
				<thead>
					<tr>
						<th>Monday</th>
						<th>Tuesday</th>
						<th>Wednesday</th>
						<th>Thursday</th>
						<th>Friday</th>
						<th>Saturday</th>
						<th>Sunday</th>
					</tr>
				</thead>
				<tbody>
					<tr class="day">
						<th>31</th>
						<th>1</th>
						<th>2</th>
						<th>3</th>
						<th>4</th>
						<th>5</th>
						<th>6</th>
					</tr>
					<tr class="info">
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td>Review<br /> 5:00pm - 7:00pm<br />Buchanon 1910</td>
						<td></td>
						<td>Review<br /> 4:00pm - 6:00pm<br />Broida 1610</td>
					</tr>
				</tbody>
			</table>
			<br />
			<br />
			<h3>Study Materials</h3>
			<ul>
				<li><a href="/sexinfo/152A/152A-Review1-Spring10.doc">Midterm 1 Study Guide for Spring \'10 (doc)</a></li>
                <li><a href="/sexinfo/152A/152A-Review2-Spring10.doc">Midterm 2 Study Guide for Spring \'10 (doc)</a></li>
                <li><a href="/sexinfo/152A/152A-Review3-Spring10.doc">Midterm 3 Study Guide for Spring \'10 (doc)</a></li>
			<li><a href="/sexinfo/152A/152A-StudyGuide-Spring10.doc">Final Study Guide for Spring \'10 (doc)</a> <a href="/sexinfo/152A/152A-StudyGuide-Spring10.pdf">(pdf)</a></li>
            </li>
			</ul>
			<br />
	');
	
	$page->output();	
?>