<?php
	/*
	 * I was going crazy writing code like <?php print "<div class=\"sidebar\">". $content ."</div>"; ?>
	 * so I wrote a utility function for it called print_content_tag(). Just going to leave it here for now. -Andrew
	*/

	function open_tag($tag, $attr) {
		// Utility function for print_content_tag()
		// returns an opening HTML tag with attributes from parameter
		$attr_string = null;
		
		if (!empty($attr)) {			
			foreach ($attr as $key => $value) {
				// If we have attributes, loop through the key/value pairs passed in and append result HTML
				// to a string that gets added into the opening tag
				$attr_string .= $key . "=" . '"' . $value . '" ';
			}
		}
		return "<" . $tag . " " . $attr_string . ">"; 
	}
	
	function close_tag($tag) { 
		// Utility function for print_content_tag(). Returns an closing HTML tag
		return "</" . $tag . ">";
	}
	
	function content_tag($tagName, $content, $attr=array()) {
		/*
		 * Description: Facilitates creating HTML tags with dynamic content.
		 * Parameters: $tagName, $content, $attr=array()
		 *  - $tagName: string; the HTML tag, ex: "div"
		 *  - $content: string; the content to wrap in tags
		 *  - $attr: array; a list of attributes to add to the tag, ex: array( "id" => "sidebar", "class" => "nav" )
		 *     - default value: null
		 * Example call: 
		 *    $content = mysql_query($query); // Anything really
		 *    print_content_tag("div", $content, array("class" => "user-info"));
		*/
		print open_tag($tagName, $attr) . $content . close_tag($tagName);
	}
	
?>

<!-- <div id="heightfix-container"> --><!-- Wrote this to try and fix the error where the footer doesn't stay at the bottom of the window even if the content is shorter than 100%. Doesn't work right now. -->
<!-- ______________________ HEADER _______________________ -->

<div id="header">
  <div class="container clearfix">
    <div id="logo">
      <?php
        // Prepare header
        $site_fields = array();
        if ($site_name) {
          $site_fields[] = check_plain($site_name);
        }
        $site_title = implode(' ', $site_fields);
        if ($logo || $site_title) {
          print '<h1><a href="'. check_url($front_page) .'" title="'. $site_title .'">';
          if ($logo) {
            print '<img src="'. check_url($logo) .'" alt="'. $site_title .'" id="logo-image" />';
          }
          print '</a></h1>';
        }
		print '<h2 class="slogan">'/* $site_slogan.*/.'</h2>';
      ?>
    </div>
    <div id="searchform-header">
     	<?php print render($page['search_area']); ?>
   	</div>
  </div> <!-- /header-container -->    
</div> <!-- /header -->

<!-- ______________________ NAVIGATION _______________________ -->

<?php if (!empty($main_menu)): ?>
  <div id="navigation"><!-- navigation -->
    <div class="container clearfix">
      <?php print theme('links', array('links' => $main_menu, 'attributes' => array('id' => 'main-menu', 'class' => array('links', 'main-menu')))); ?>
      <a title="Subscribe to magazeen RSS" class="rss" href="">Subscribe</a>
    </div> <!-- /navigation-container -->
  </div> <!-- /navigation -->
<?php endif;?>

<!-- ______________________ SLIDESHOW _______________________ -->

<?php
/*
<div id="slideshow"><!-- slideshow -->
  <div class="slideshow container clearfix">
    <div class="force-previous"><a href="#">Previous</a></div>
    <div class="main_view">
      <div class="window">
        <div class="image_reel">
                <a href="#"><img src="<?php print drupal_get_path('theme', 'magazeenlite')?>/images/slideshow/slide3.jpg" alt="" /></a>
                <a href="#"><img src="<?php print drupal_get_path('theme', 'magazeenlite')?>/images/slideshow/slide1.jpg" alt="" /></a>
                <a href="#"><img src="<?php print drupal_get_path('theme', 'magazeenlite')?>/images/slideshow/slide2.jpg" alt="" /></a>
        </div>
      </div>
      <div class="paging">
        <a href="#" rel="1">1</a>
        <a href="#" rel="2">2</a>
        <a href="#" rel="3">3</a>
      </div>
    </div>
    <div class="force-next"><a href="#">Next</a></div>
  </div> <!-- /slideshow-container -->
</div> <!-- /slideshow -->
*/
?>

<!-- ______________________ MAIN _______________________ -->

<div id="main-front" class="clearfix">
	<div class="container clearfix">
		<!--<div class="main">-->
			<?php if ($breadcrumb): print $breadcrumb; endif; ?>

			<?php 
				# View and Edit buttons
				# if ($tabs): print '<div id="tabs-wrapper" class="clear-block">' . render($tabs) .'</div>'; endif; 
			?>

			<?php 
				# "has been successfully updated", etc
				if ($show_messages && $messages): print $messages; endif;
			?>

			<?php 				
				print render($page['help']); 
			?>
			
			<div class="clearfix">
				
				<?php
					# Main content goes here
					# This line renders node.tpl.php and the text for this node (which is empty - harcoded instead)
					# print render($page['content']); 
				?>								
				
				<div id="column-large">
					<div class="column-container">
						<div id="image-carousel">

							<div id="carousel-window">
								<div id="window-inner">
							
									<!-- SLIDE 1 -->
									<div class="carousel-frame">
										<img src="<?php print path_to_theme() . '/images/modules/' . 'pregnant.jpg'; ?>" alt="Can I get pregnant if..." />										
										<div class="carousel-caption">
											<h3>Can I get pregnant if...</h3>
											<p>There are many situations when the question of pregnancy can occur.. If you are having any doubts on&hellip;
											<a href="#" class="more-button">Read More</a></p>
										</div>
									</div><!-- .carousel-frame -->
									
									<!-- SLIDE 2 -->
									<div class="carousel-frame">
										<img src="<?php print path_to_theme() . '/images/modules/' . 'stdtalking.jpg'; ?>" alt="Sexual Behaviors" />
										<div class="carousel-caption">
											<h3>Sexual Behaviors</h3>
											<p>Sexual techniques are methods for increasing a partner's sexual arousal. These include activites that&hellip;
											<a href="#" class="more-button">Read More</a></p>
										</div>
									</div><!-- .carousel-frame -->
									
									<!-- SLIDE 3 -->
									<div class="carousel-frame">
										<img src="<?php print path_to_theme() . '/images/modules/' . 'holding-hands.jpg'; ?>" alt="Love and Building Relationships" />
										<div class="carousel-caption">
											<h3>Love &amp; Building Relationships</h3>
											<p>There are five components that determine with whom you fall in love, and if your love will last. They are&hellip;
											<a href="#" class="more-button">Read More</a></p>
										</div>
									</div><!-- .carousel-frame -->
									
									<!-- SLIDE 4 -->
									<div class="carousel-frame">
										<img src="<?php print path_to_theme() . '/images/modules/' . 'std.jpg'; ?>" alt="Sexually Transmitted Diseases" />
										<div class="carousel-caption">
											<h3>Sexually Transmitted Diseases</h3>
											<p>Have a concern regarding STD's? Have a look at the STD Symptom Chart to learn more about sexual&hellip;
											<a href="#" class="more-button">Read More</a></p>
										</div>
									</div><!-- .carousel-frame -->
								
								</div><!-- #window-inner -->
							</div><!-- .carousel-window -->
							
							<div id="carousel-nav">
								<ul>
									<li>
										<img class="carousel-thumb" src="<?php print path_to_theme() . '/images/modules/' . 'pregnant-small.jpg'; ?>" />
										<a class="current" rel="1" href="#">Can I get pregnant if...</a>
									</li>
									<li>
										<img class="carousel-thumb" src="<?php print path_to_theme() . '/images/modules/' . 'stdtalking-small.jpg'; ?>" />
										<a rel="2" href="#">Sexual Behaviors</a>
									</li>
									<li>
										<img class="carousel-thumb" src="<?php print path_to_theme() . '/images/modules/' . 'holding-hands-small.jpg'; ?>" />
										<a rel="3" href="#">Love &amp; Building Relationships</a>
									</li>
									<li>
										<img class="carousel-thumb" src="<?php print path_to_theme() . '/images/modules/' . 'std-small.jpg'; ?>" />
										<a rel="4" href="#">Sexually Transmitted Diseases</a>
									</li>
								</ul>
							</div><!-- .carousel-nav -->
						
						</div><!-- #image-carousel -->
					</div><!-- .column-container -->			
					
					<div class="column-third">
						<!------------ Popular Topics box ------------>
						<h4 class="module-title">Popular Topics</h4>
						
						<div class="module">
							<div class="node-box">
							
								<div class="node-content">									
									<div class="list-half">
										<a href="#">Abortion</a>
										<a href="#">The Body</a>
										<a href="#">Sexual Orientations</a>
										<a href="#">Pregnancy</a>
										<a href="#">Sexual Activity</a>
										<a href="#">Sexually Transmitted Infections</a>
									</div>
									
									<div class="list-half">
										<a href="#">Basics of Sexuality</a>
										<a href="#">Love &amp; Relationships</a>
										<a href="#">Legislation</a>
										<a href="#">Sexual Difficulties</a>
										<a href="#">Sexual Violence</a>
									</div>
								</div><!--.node-content-->
								
								<div class="node-footer">																								
									<a href="#">All Topics &raquo;</a>									
								</div><!--.node-footer-->
								
							</div><!--.node-box-->
						</div><!-- .module -->
					</div><!-- .column-third -->
					
					<div class="column-third">					
						<!------------ Frequently Asked Questions box ------------>						
						<h4 class="module-title">Frequently Asked Questions</h4>
						
						<div class="module">
							<div class="node-box">
							
								<div class="node-content">									
									<div class="faq-image">
										<img class="" src="<?php print path_to_theme() . '/images/modules/' . 'sadomasochism.jpg'; ?>" />
										<div class="caption-slide">
											<h3>Sexual Fetishism</h3>
											<p>There are many types of fetishes. Some people get sexually excited by seeing boots&hellip;
											<a href="#" class="more-button">Read More</a></p>
										</div><!-- .caption-slide -->
									</div><!-- .faq-image -->								
								</div><!--.node-content-->															
								
								
								
								<div class="node-footer">																								
									<a href="#">All Questions &raquo;</a>									
								</div><!--.node-footer-->
								
							</div><!--.node-box-->
						</div><!-- .module -->							
					</div><!-- .column-third -->
																				
				</div><!-- #column-large -->
											
				<div id="column-small">
					<div class="column-container">
						<h2>Recent Questions</h2>						
						
						<div class="column-container dark">
							<?php				
								// Recent Questions module
								// Displays the three most recently authored Q/A's and a link to the full node														
																
								function format_time($time) {
									// Utility function to render a Unix timestamp in a readable format
									// Format: October 17, 2012
									// Note that %e (day as 1-31) does not work on Windows, so %#d is apparently a workaround.
									return strftime("%B %#d, %Y", $time);
								}
								function slice_teaser($body) {
									// Utility function that takes the full body of a question (string)
									// and cuts it off into a 350-char preview chunk
									return substr($body,0,350);
								}
																
								// This is an absolutely horrible way to go about things.
								// Drupal provides DB wrapper functions that I can't figure out for the life of me, and I'm
								// pretty sure this sort of DB code should never be in a template file anyways. Bummer.								
								mysql_connect("localhost", "sexweb00m", "249APWan") or die("Could not connect: " . mysql_error());
								mysql_select_db("sexweb00");
								/*
								 * Question title+timestamp is stored in table:node
								 * but the question body is stored in table:field_data_field_question,
								 * so we have to SQL join the two by id so that we have access to fields
								 * from both tables.
								 * See http://www.codinghorror.com/blog/2007/10/a-visual-explanation-of-sql-joins.html
								*/
								$query = "
									SELECT * FROM node										
									INNER JOIN field_data_field_question
									ON node.nid = field_data_field_question.entity_id
									ORDER BY created DESC
									LIMIT 3
								";
								$result = mysql_query($query) or die(mysql_error());
								
								while($row = mysql_fetch_array($result) ) {
									// Loop through returned Question rows
									// Initialize content variables to be passed into print_content_tag()
									$nid = $row['nid'];
									$time = format_time($row['created']);
									$title = $row['title'];
									$teaser = slice_teaser($row['field_question_value']);									
									/*
									 - OUTPUTTED CODE STRUCTURE -
									<div class="question">
										<h4>Question Title</h4>							
										<p class="date">October 30, 2011</p>
										<p>Body of the question (Not the answer)</p>
										<a href="/sexinfo/node/$nid" class="readmore">Read More &raquo;</a>
									</div>									
									*/									
									print open_tag("div", array("class" => "question"));
										content_tag("h4", $title);
										content_tag("p", $time, array("class" => "date"));																			
										content_tag("p", $teaser);
										content_tag("a", "Read More &raquo;", array("href" => "/sexinfo/node/" . $nid, "class" => "readmore"));								
									print close_tag("div");									
								}															
							?>
							
						</div><!-- .column-container.dark -->						
					</div><!-- .column-container -->
				</div><!-- #column-small -->
				
				
			</div>
		
			<div id="sidebar" class="right">
				<?php print render($page['sidebar_second']); ?> 
			</div><!-- #sidebar.right -->
		
		<!--</div>--><!-- .main --><!-- Not being used anymore -->
	</div><!-- .container.clearfix -->
</div><!-- #main.clearfix -->
 
<!-- ______________________ FOOTER _______________________ -->

<div id="footer">
  <div class="container footer-divider clearfix">
  
    <div id="footer-left">
      <h4>Categories</h4>
	  <!-- Most of these links are deprecated and should be changed later - purely for positioning -->
        <a href="#" class="footer-pill">Emergency Info</a>
		<a href="#" class="footer-pill">UCSB Soc 152A</a>
		<a href="#" class="footer-pill">Product Reviews</a>
		<a href="#" class="footer-pill">Sexual Myths</a>
		<a href="#" class="footer-pill">Extras</a>
				
      <?php print render($page['footer_left']); ?>
	  
    </div>
	
    <div id="footer-right">
      <h4>About SexInfo Online</h4>
		<p><?print '<a href="'. check_url($front_page) .'">';?>SexInfo Online</a> is a website devoted to comprehensive sex education based on the best research we have to date. The site is maintained by university students from the University of California, Santa Barbara who have studied advanced topics in human sexuality.</p>
		<p>Our primary goal is to ensure that people around the world have access to useful and accurate information about all aspects of human sexuality. If you find our site useful, don't forget that your friends, family, and coworkers may find it useful too!</p>
		
      <?php print render($page['footer-right']); ?>
	  
    </div>
	
  </div><!-- /footer-container -->
</div><!-- /footer -->


<!-- ______________________ SECONDARY NAVIGATION _______________________ -->

<?php if (!empty($secondary_menu)): ?>
  <div id="snavigation">
    <div class="container clearfix">     
	  <p>UCSB SexInfo Copyright &copy; 2010 University of California, Santa Barbara. All Rights Reserved.</p>
    </div> <!-- /snavigation-container -->
  </div> <!-- /snavigation -->
<?php endif;?>


<!-- ______________________ PAGE SCRIPTS _______________________ -->

<script type="text/javascript">
	// Code to run on page load
	
	(function ($) {
		
		//----- Image carousel module ---//
		var loopRotate = true; // A boolean determining whether or not to automatically cycle. Disabled once user clicks one of the nav links.
		
		function rotate(id) {
			var offset = Math.abs(id-1),
				distance = 250 * offset; // 250 = hardcoded image height
			
			$('#window-inner').animate({
				top: -distance
			}, 550);
		}
		
		$('#carousel-nav a').click(function() {
			clearInterval(loop); // Disable auto cycle once user clicks
			$('#carousel-nav a').removeClass('current'); // Remove from all tabs, not just $(this)
			$(this).addClass("current");
			var id = $(this).attr('rel'); // Not a very semantic way of doing it, but oh well. TODO - CHANGE TO IDS
			rotate(id);			
			return false; //Prevent browser jump to anchor link
		});
		
		/* Automatic carousel cycling */
		var i = 1,
			links = $("#carousel-nav a"); // Get all of the nav links for looping through later
				
		function cycle() {
			// Rotate the image frame
			rotate(i);			
			
			// Remove 'current' class from all tabs
			links.removeClass('current');
			
			// Loop through all links in nav list
			// If the link's rel attribute (used as an identifier) matches current frame, add class 'current'
			$.each(links, function() {
				if ( $(this).attr("rel") == i ) {
					$(this).addClass("current");
				}			
			});
			
			// Increment the current frame and reset to first if at end 			
			i++;
			if (i == 5) { i = 1; }			
		} // end cycle()
		
		if (loopRotate == true) {
			// Cycle the frame every 5 seconds if the user hasn't clicked a link
			var loop = setInterval(cycle, 5000);
		}

		
		//----- Sliding captions for FAQ module ---//
		$("#column-large .caption-slide").hover(
			function () {
				// Hover on - slide up for full view
				$(this).animate({bottom: '0'}, 300);
			}, 
			function () {
				// Hover off - slide back down
				$(this).animate({bottom: '-40px'}, 300);
			}
		);
											  
	})(jQuery);
</script>
<!-- </div> --><!-- heightfix-container -->