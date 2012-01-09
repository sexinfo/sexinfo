<div id="heightfix-container">
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
								# These need to be generated dynamically - three most recent questions (or articles, etc)							
							?>
							
							<div class="question">
								<h4>Example question for formatting</h4>
								<p class="date">October 30, 2011</p>
								<p>Body of the question goes here. This is all static for styling, and needs to be dynamically generated.</p>
								<p><a href="#" class="readmore">Read More &raquo;</a></p>
							</div>
							
							<div class="question">
								<h4>Is this a real question?</h4>
								<p class="date">October 30, 2011</p>
								<p>No. It is not. The webdevs are lazy and need to make this a dynamic module. Go hassle at them, but look how pretty the rest of the site is in the meantime.</p>
								<p><a href="#" class="readmore">Read More &raquo;</a></p>
							</div>
							
							<div class="question">
								<h4>Some other question title</h4>
								<p class="date">October 30, 2011</p>
								<p>Question: Dear Sexperts, My friends told me that masturbating will make me taller. Is this true? At age eighteen, I started ejaculating at night and it makes...</p>
								<p><a href="#" class="readmore">Read More &raquo;</a></p>
							</div>
														
						</div><!-- .column-container.dark -->
						
					</div><!-- .column-container -->
				</div><!-- #column-small -->
				
				
			</div>
		
			<div id="sidebar" class="right">
				<?php print render($page['sidebar_second']); ?> 
			</div><!-- #sidebar.right -->
		
		<!--</div>--><!-- .main -->
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
		var loopRotate = true; // A boolean determining whether or not to automatically cycle. 
										// Disabled once user clicks.
		
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
			var id = $(this).attr('rel'); // Not a very semantic way of doing it, but oh well
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
			$('#carousel-nav a').removeClass('current');
			
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
</div><!-- heightfix-container -->