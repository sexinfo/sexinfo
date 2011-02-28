<?php

/**
 * admin-templates
 *
 * Word templates that can be downloaded from the website.
 *
 * @author Chris Bednarski <banzaimonkey@gmail.com>
 */

require('../core/sex-core.php');

# Security function - MUST PASS TRUE FOR ADMIN PAGES!
$security = new security(TRUE);

$page = new admin_page('template.html');

if($security->session_logged_in())
{
	$page->title('Templates');
	$page->add('<h1>Document Templates</h1>');

	$page->add('
		<p>The links below point to Word templates that you can use as a starting point for articles and responses to reader questions.</p>

		<h2>Articles</h2>
		<h4>Turn-in Instructions</h4>
		<ol>
			<li>Your Name and Soc 152C</li>
			<li>Category Placement - where your article should appear on the site</li>
			<li>Title</li>
			<li>Abstract – Brief summary for search engines, helps readers skim content</li>
			<li>Body</li>
			<li>References (academic-style reference section; see syllabus)</li>
		</ol>
		<h4>Download Template - <a href="files/Article%20Template.doc">Article Template.doc</a></h4>

		<h2>Question &amp; Answer</h2>
		<h4>Turn-in Instructions</h4>
		<ol>
			<li>Your Name and Soc 152C</li>
			<li>Category Placement</li>
			<li>Title</li>
			<li>User\'s question Include sex, age, and location.  Make sure to remove their name and/or e-mail address.</li>
			<li>Your response</li>
			<li>Signature</li>
			<li>See Related Questions (optional)</li>
			<li>References</li>
		</ol>
		<h4>Download Template - <a href="files/Q&amp;A%20Template.doc">Q&amp;A Template.doc</a></h4>

		<h2>Link-based Answers</h2>
		<h4>Turn-in and E-mail Instructions</h4>
		<ol>
			<li>Your Name and Soc 152C (Don’t e-mail them this part.)</li>
			<li>Address the user</li>
			<li>Validate their concern</li>
			<li>Provide links to answers on SexInfo</li>
			<li>Personal message / summary</li>
			<li>Signature</li>
		</ol>
		<h4>Download Template - <a href="files/Generic%20Link%20Based%20Answer%20Template.doc">Generic Link Based Answer Template.doc</a></h4>
		<h4>Download Instructions - <a href="files/Generic%20Link%20Based%20Answers%20Format.doc">Generic Link Based Answers Format.doc</a></h4>

		<h2>Generic Response Templates</h2>
		<p>The items in the "Generic Response" section are to be used for responses that are e-mailed to readers, but not posted on the website.</p>
		<ul>
			<li>See our Q&amp;As/FAQs</li>
			<li>See a Lawyer</li>
			<li>See a Doctor</li>
			<li>About STDs</li>
			<li>About Pregnancy</li>
			<li>Please Use Good BC</li>
			<li>Do Your Own Research</li>
			<li>We Need Clarification</li>
			<li>Preg after unsafe sex</li>
			<li>Creating New Generics</li>
			<li>Link-filled generics</li>
		</ul>

		<h2>Miscellaneous / Other Templates</h2>
		<ul>
			<li><a href="files/SexInfo%20Flyer%20Template.doc">Sexinfo Flyer Template</a> (for tabling!)</li>
			<li><a href="files/Template%20Email%20to%20Businesses%20for%20Endorsments.doc">Template Email to Businesses for Endorsments.doc</a></li>
			<li><a href="files/Template%20Email%20to%20Businesses%20for%20Product%20Reviews.doc">Template Email to Businesses for Product Reviews.doc</a></li>
		</ul>
		');
}

$page->output();
?>