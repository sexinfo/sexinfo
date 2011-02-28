<?php
/**********************************************************************//**\file
	SexInfo Homepage

	Description:  Displays the site homepage and handles some $_GET argument
		processing.
*******************************************************************************/

	require_once('./core/sex-core.php');

	$mysqli = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);
	
	if(isset($_GET['article']))
	{
		# hard coded legacy support
		if($_GET['article'] == 'emergency')
		{
			header('HTTP/1.1 301 Moved Permanently');
			header('Location: ' . '/sexinfo/article/emergency-numbers');
			exit();
		}
		if($_GET['article'] == 'submit')
		{
			header('HTTP/1.1 301 Moved Permanently');
			header('Location: ' . '/sexinfo/submit.php');
			exit();
		}

		else
		{
			$article = $mysqli->real_escape_string($_GET['article']);
			
			if(isset($_GET['refid']))
			{
				$refid = $mysqli->real_escape_string($_GET['refid']);
	
	/*			if(strpos($refid, 'menu'))
				{
					$refid = str_replace('menu', 'm', $refid);
				}*/
	
				$result = $mysqli->query("
					SELECT content_slug, content_type, type_slug
					FROM sex_content
					JOIN sex_legacy
						ON content_id = legacy_content_id
					JOIN sex_type
						ON content_type = type_id
					WHERE legacy_ref_category = '$article' AND legacy_ref_id = '$refid'
					LIMIT 1
				");
	
	
			$hack = $result->fetch_assoc();
	
			if($hack == false)
			{
				$article = "etc";
					$result = $mysqli->query("
					SELECT content_slug, content_type, type_slug
					FROM sex_content
					JOIN sex_legacy
						ON content_id = legacy_content_id
					JOIN sex_type
						ON content_type = type_id
					WHERE legacy_ref_category = '$article' AND legacy_ref_id = '$refid'
					LIMIT 1");
			}
		}
		else
		{
			$result = $mysqli->query("
				SELECT content_slug, content_type, type_slug
				FROM sex_content
				JOIN sex_legacy
					ON content_id = legacy_content_id
				JOIN sex_type
					ON content_type = type_id
				WHERE legacy_hash_id = '$article'
				LIMIT 1
			");
            $hack = $result->fetch_assoc();

		}
	
            if($hack == true)
		{
			if($row = $result->fetch_assoc())
			{
				$url = '/sexinfo/'.$hack['type_slug'].'/'.$hack['content_slug'];
	
				header('HTTP/1.1 301 Moved Permanently');
				header('Location: ' . $url);
			}
			else
			{
				error::code(404, __LINE__);
			}
			}
		}
    }
	else
	{
		$page = new page();
		$security = new security();
		$page->title('Sex, Pregnancy, Relationships, and More!');
		
		
		

		
		
		##### QUESTIONS AND ANSWERS #####
		
		$page->add('<div class="col-300 right" clearfix>');
		$page->add('<h5>&nbsp;Recent Questions</h5><div id="sidebar"><li>');
		
		$result = $mysqli->query("
			SELECT content_id, content_title, content_slug, content_body, content_published, type_name, type_slug
			FROM sex_content
			JOIN sex_type
				ON content_type = type_id
			WHERE content_type = '2'
			ORDER BY content_published DESC
			LIMIT 3
		");

		if($result)
		{
			while($row = $result->fetch_assoc())
			{
				$page->add('<div id="sidebar"><ul>');

				# Title + Link
				$page->add('<h4><a href="'.$row['type_slug'].'/'.$row['content_slug'].'">'.$row['content_title'].'<br></a></h4>');

				# Meta
				$page->add('<div class="metadata" align=right>'.data::format_date($row['content_published'], 1).'</div>');

				# Content Preview
				$page->add('<p>'.substr(strip_tags($row['content_body']), 0, 360).'&hellip; <a href="'.$row['type_slug'].'/'.$row['content_slug'].'"; ><br />Read More ></a></p><div class="post-meta"></div>');

				$page->add('</ul></div>');
			}
		}
		$page->add('</li></ul></div></div>');
		$page->add('</li>');
		
		
		
		
		
		
		##### HIGHLIGHTED ARTICLES MODULE #####
		
		$page->add('<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js" ></script>	
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.5.3/jquery-ui.min.js" ></script>
	<script type="text/javascript">
		$(document).ready(function(){
		$("#featured > ul").tabs({fx:{opacity: "toggle"}}).tabs("rotate", 1000, true);
		});
	</script>

		<div class="round-featurele">
		
		
		<div class="round-featuresi">
		
		
		
		<div id="featured">
		  <ul class="ui-tabs-nav">
	        <li class="ui-tabs-nav-item ui-tabs-selected" id="nav-fragment-1"><a href="#fragment-1"><img src="images/pregnant-small.jpg" alt="" /><span>Can I get pregnant if...</span></a></li>
	        <li class="ui-tabs-nav-item" id="nav-fragment-2"><a href="#fragment-2"><img src="images/09-07-stdtalking-small.jpg" alt="" /><span>Sexual Behaviors</span></a></li>
	        <li class="ui-tabs-nav-item" id="nav-fragment-3"><a href="#fragment-3"><img src="images/holding-hands-small.jpg" alt="" /><span>Love & Building Relationships</span></a></li>
	        <li class="ui-tabs-nav-item" id="nav-fragment-4"><a href="#fragment-4"><img src="images/std-small.jpg" alt="" /><span>Sexually Transmitted Diseases</span></a></li>
	      </ul>

	    <!-- First Content -->
	    <div id="fragment-1" class="ui-tabs-panel" style="">
			<img src="images/pregnant.jpg" alt="" />
			 <div class="info" >
				<h2><a href="http://www.soc.ucsb.edu/sexinfo/article/can-i-get-pregnant-if" >Can I get pregnant if...</a></h2>
				<p>There are many situations when the question of pregnancy can occur. If you are having any doubts on...........<a href="http://www.soc.ucsb.edu/sexinfo/article/can-i-get-pregnant-if" ><button style="font: bold 10px Arial">Read More</button></a></p>
			 </div>
	    </div>

	    <!-- Second Content -->
	    <div id="fragment-2" class="ui-tabs-panel ui-tabs-hide" style="">
			<img src="images/09-07-stdtalking.jpg" alt="" />
			 <div class="info" >
				<h2><a href="http://www.soc.ucsb.edu/sexinfo/category/sexual-behaviors" >Sexual Behaviors</a></h2>
				<p>Sexual techniques are methods for increasing a partner\'s sexual arousal. These include activities that.........<a href="http://www.soc.ucsb.edu/sexinfo/category/sexual-behaviors" ><button style="font: bold 10px Arial">Read More</button></a></p>
			 </div>
	    </div>

	    <!-- Third Content -->
	    <div id="fragment-3" class="ui-tabs-panel ui-tabs-hide" style="">
			<img src="images/holding-hands.jpg" alt="" />
			 <div class="info" >
				<h2><a href="http://www.soc.ucsb.edu/sexinfo/category/building-relationships" >Love & Building Relationships</a></h2>
				<p>There are five components that determine with whom you fall in love, and if your love will last. They are......<a href="http://www.soc.ucsb.edu/sexinfo/category/building-relationships" ><button style="font: bold 10px Arial">Read More</button></a></p>
	         </div>
	    </div>

	    <!-- Fourth Content -->
	    <div id="fragment-4" class="ui-tabs-panel ui-tabs-hide" style="">
			<img src="images/std.jpg" alt="" />
			 <div class="info" >
				<h2><a href="http://www.soc.ucsb.edu/sexinfo/article/std-symptom-chart1" >Sexual Transmitted Diseases</a></h2>
				<p>Have a concern regarding STD\'s? Have a look at the STD Symptom Chart to learn more about sexual...........<a href="http://www.soc.ucsb.edu/sexinfo/article/std-symptom-chart1" ><button style="font: bold 10px Arial">Read More</button></a></p>
	       		</div>
		
	    </div>
		
		</div>
		<div class="round-featureri">
		</div>
		
		</div>
		
		
		</div>');
		
		


		

	
	##### POPULAR TOPICS MODULE #####
	
	$page->add('
	<div id="main-content" class="clearfix">
	<div class="single clearfix">');
		
	
	$page->add('
	
	<div class="post-meta clearfix">
	
		<h3 class="post-title left">Popular Topics</h3>
	
	</div>
	
	<div class="post-box">
	
		<div class="post-content">



		<ul>
		<li><a href="/sexinfo/category/abortion"><b>Abortion</b></a></li>
		<li><a href="/sexinfo/category/basics-of-sexuality"><b>Basics of Sexuality</b></a></li>
	
		<li><a href="/sexinfo/category/the-body"><b>The Body</b></a></li>  
		<li><a href="/sexinfo/category/contraception"><b>Contraception</b></a></li></br>
	
	
		<li><a href="/sexinfo/category/sexual-orientations"><b>Sexual Orientations</b></a></li>
		<li><a href="/sexinfo/category/love-relationships"><b>Love & Relationships</b></a></li></br>

		

		<li><a href="/sexinfo/category/pregnancy"><b>Pregnancy</b></a></li>
		<li><a href="/sexinfo/category/sex-and-the-law"><b>Legislation</b></a></li></br>
	
	
		<li><a href="/sexinfo/category/sexual-activity"><b>Sexual Activity</b></a></li>
		<li><a href="/sexinfo/category/sexual-difficulties"><b>Sexual Difficulties</b></a></li></br>
	
	
		<li><a href="/sexinfo/category/sexually-transmitted-infections"><b>Sexually Transmitted Infections</b></a></li>
		<li><a href="/sexinfo/category/sexual-violence"><b>Sexual Violence</b></a></li>
		</ul>

	
		</div>
	
	
	</div>


	
		<div class="post-footer">
			<div class="continue-reading">
	
				<h4 class="post-content"><a href="/sexinfo/category">All Topics ></a></h4>
	
			</div>
		</div>
	</div>');
	
	
	
	
	##### FREQUENTLY ASKED QUESTIONS MODULE #####
	
	$page->add('
	
	<div class="single clearfix" style="margin:0px 0px 0px 6px">
	
					<div class="post-meta clearfix">
		
						<h3 class="post-title right">Frequently Asked Question</h3>
		
					</div>
		
	
			<div class="post-box">
				<div class="post-content">
		
		
		
			<div id="capslide_img_cont" class="ic_container" style="margin-left:20px;">
			<a href="/sexinfo/article/what-can-i-do-about-my-fetish">
              <img src="images/09-07-sadomasochism.jpg" width="228" height="158" alt=""/>
                <div class="overlay"></div>
				
                <div class="ic_caption">
                    <h7>Sexual Fetishism</h7>
                    <p class="ic_text">There are many different types of fetishes. Some people get sexually excited by seeing boots...
                </div>
				</a>
		
				</div>


				</div>
			</div>


	<div class="post-footer">
	<div class="continue-reading">

	<h4 class="post-content"><a href="/sexinfo/category">All Frequently Asked Questions ></a></h4>

	</div>
	</div>



	</div>
	
	<div style="clear:left;"> </div>');
	
	

	
	###### ARTICLES 3 (Articles listed) #####
	
	$result = $mysqli->query("
		SELECT content_id, content_title, content_slug, content_body, content_published, type_name, type_slug
		FROM sex_content
		JOIN sex_type
			ON content_type = type_id
		WHERE content_type = '1'
		ORDER BY content_published DESC
		LIMIT 4
	");
	




	$page->add('<div class="last">');
	
	if($result)
	{
		while($row = $result->fetch_assoc())
		{
			#Title + Link
			$page->add('
			
			<div class="post-meta clearfix">
			
			<h3 class="post-title-small left">
			<a href="'.$row['type_slug'].'/'.$row['content_slug'].'">'.$row['content_title'].'</a>
			</h3>
			
			<p class="post-info right">'.data::format_date($row['content_published'], 1).'</p>
			
			
			
			</div>');
			

			
		}
	}
	$page->add('</div>');
	
	$page->add('</div>');
	
	
	
	/*  

			$page->add('<center><h1>Welcome to Sex Info Online!</h1></center><br />');

			$page->add('<div class="col-660 left"><p>Sex Info Online is a website devoted to comprehensive sex education based on the best research we have to date. Our primary goal is to ensure that people around the world have access to useful and accurate information about all aspects of human sexuality.</p>');
			$page->add('<p> We have over 2000 entries on SexInfo, and to make it easier for you to find what you\'re looking for we\'ve arranged them into <a href="/sexinfo/article/">encyclopedia-style articles</a> and <a href="/sexinfo/question/">questions and answers</a>.  If you don\'t know where you start, you may like to take a look at our <a href="/sexinfo/article/frequently-asked-questions">frequently asked questions</a>.</p>');

			$page->add('<p>We hope you find SexInfo useful!  If you can\'t find what you\'re looking for, try <a href="http://www.google.com/cse?cx=016229344819287620609%3A57qdqcwlna8&amp;ie=UTF-8">searching</a> for it or <a href="/sexinfo/submit.php">ask us a question</a>.  And if you find our site useful, don\'t forget that your friends, family, and coworkers may find it useful too!</p>');

			*/
		
		
		
		/* 
		
		from  <a href="/sexinfo/category/contraception">contraceptives</a> to <a href="/sexinfo/category/pregnancy">pregnancy</a>, <a href="/sexinfo/category/sexually-transmitted-infections">sexually transmitted infections</a> to <a href="/sexinfo/category/sexual-difficulties">sexual disorders</a>, and <a href="/sexinfo/category/basics-of-sexuality">social views</a> to <a href="/sexinfo/category/sex-and-the-law">legislation</a>.
		
		*/



		
		
		
		/*
		## ARTICLES 2 (Second Column)
		
		$result = $mysqli->query("
			SELECT content_id, content_title, content_slug, content_body, content_published, type_name, type_slug
			FROM sex_content
			JOIN sex_type
				ON content_type = type_id
			WHERE content_type = '1'
			ORDER BY content_published  ASC
			LIMIT 2
		");
		
		$page->add('<div class="single">');
			
		
		if($result)
		{
			while($row = $result->fetch_assoc())
			{
				#Title + Link
				$page->add('<div class="post-meta clearfix"> <h3 class="post-title right"><a href="'.$row['type_slug'].'/'.$row['content_slug'].'">'.$row['content_title'].'</a></h3></div>');
				
				

				# Content Preview
				$page->add('<div class="post-box"><div class="post-content"');
				$page->add('<p>'.substr(strip_tags($row['content_body']), 0, 420).'&hellip;</p>');

				$page->add('</div></div>');
				
				# Continue Reading
				$page->add('<div class="post-footer clearfix"><div class="continue-reading"><h4 class="post-content"><a href="'.$row['type_slug'].'/'.$row['content_slug'].'">Continue Reading</h4></a></div></div>');
			}
		}
		$page->add('</div><div style="clear:left;"> </div>');
		
		
		*/
		
		
		##### ARTICLES #####

		/*
		$result = $mysqli->query("
			SELECT content_id, content_title, content_slug, content_body, content_published, type_name, type_slug
			FROM sex_content
			JOIN sex_type
				ON content_type = type_id
			WHERE content_type = '1'
			ORDER BY content_published DESC
			LIMIT 2
		");

		$page->add('<div class="single">');


		if($result)
		{
			while($row = $result->fetch_assoc())
			{
				#Title + Link
				$page->add('<div class="post-meta clearfix"><h3 class="post-title-small left"><a href="'.$row['type_slug'].'/'.$row['content_slug'].'">'.$row['content_title'].'</a></h3><p class="post-info right"></p></div>');



				# Content Preview
				$page->add('<div class="post-box"><div class="post-content"');
				$page->add('<p>'.substr(strip_tags($row['content_body']), 0, 420).'&hellip;</p>');

				$page->add('</div></div>');

				# Continue Reading
				$page->add('<div class="post-footer clearfix"><div class="continue-reading"><h4 class="post-content"><a href="'.$row['type_slug'].'/'.$row['content_slug'].'">Continue Reading</h4></a></div></div>');
			}
		}


		*/

		
		$page->output();
	}
?>

