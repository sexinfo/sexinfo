<?php include "content-tag.php" ?>

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
		//print '<h2 class="slogan">'/* $site_slogan.*/.'</h2>';
      ?>
    </div>
    <div id="searchform-header">
     	<?php print render($page['search_area']); ?>
   	</div>

    <div class="social-buttons">
      <a class="soc-yt" href="http://www.youtube.com/user/UCSBSexInfoOnline">YouTube</a>
      <a class="soc-fb" href="https://www.facebook.com/SexInfoOnline">Facebook</a>
      <iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.sexinfoonline.com&amp;send=false&amp;layout=button_count&amp;width=50&amp;show_faces=true&amp;action=like&amp;colorscheme=light&amp;font=lucida+grande&amp;height=21&amp;appId=228872410538762" scrolling="no" frameborder="0" style="border:none; border-height:10px; padding-top:5px; overflow:hidden; width:75px; height:21px;" allowtransparency="true"></iframe>
    <!-- <a class="soc-rss" href="#">RSS</a> -->
    </div><!-- .social-buttons -->

  </div> <!-- /header-container -->
</div> <!-- /header -->

<!--<div class="banner uc-banner">
     <a href="http://www.ucsb.edu">University of California<a/>
</div>--><!-- .banner.uc-banner -->

<div class="banner new-banner">
	The Sexperts are back and answering questions again!
  <a href="<?php print $base_path . "ask-sexperts" ?>">We'd love to hear from you &raquo;</a>
</div>

<!-- ______________________ NAVIGATION _______________________ -->

<?php if (!empty($main_menu)): ?>
  <div id="navigation"><!-- navigation -->
    <div class="container clearfix">
      <?php print theme('links', array('links' => $main_menu, 'attributes' => array('id' => 'main-menu', 'class' => array('links', 'main-menu')))); ?>
    </div>
  </div><!-- #navigation -->
<?php endif;?>

<!-- ______________________ SLIDESHOW _______________________ -->

<?php #include "modules/slideshow.php" ?>

<!-- ______________________ MAIN _______________________ -->

<div id="main-front" class="clearfix">
	<div class="container clearfix">

			<?php if ($breadcrumb): print $breadcrumb; endif; ?>

			<?php
				# View and Edit buttons
				# if ($tabs): print '<div id="tabs-wrapper" class="clear-block">' . render($tabs) .'</div>'; endif;
			?>

			<?php
				# "has been successfully updated", etc
				if ($show_messages && $messages): print $messages; endif;
			?>

			<?php print render($page['help']); ?>

			<div class="clearfix">

				<?php
					# Main content goes here
					# This line renders node.tpl.php and the text for this node (which is empty - harcoded instead)
					# print render($page['content']);
				?>

        <!--=========================
          IMAGE CAROUSEL
        =============================-->
        <div class="column-container">
          <div id="image-carousel">
              <div id="carousel-window">
                <div id="window-inner">
                  <?php include "modules/carousel-frames.php" ?>
                </div><!-- #window-inner -->
              </div><!-- .carousel-window -->

              <div id="carousel-nav">
                <?php include "modules/carousel-links.php" ?>
              </div><!-- .carousel-nav -->

            </div><!-- #image-carousel -->
        </div><!-- .column-container -->


        <!--=========================
          TRI BOX MODULES
        =============================-->
				<div class="box-module-container">

          <!-- Left box
          ========================-->
					<div class="column-third">
						<!--Popular Topics -->
						<h4 class="module-title">Popular Topics</h4>
						<div class="module">
							<div class="node-box">
								<div class="node-content">
									<div class="list-half">
										<a href="<?php print $base_path . "category/abortion" ?>">Abortion</a>
										<a href="<?php print $base_path . "category/body" ?>">The Body</a>
										<a href="<?php print $base_path . "category/sexual-orientations" ?>">Sexual Orientations</a>
										<a href="<?php print $base_path . "category/pregnancy" ?>">Pregnancy</a>
										<a href="<?php print $base_path . "category/sexual-activity" ?>">Sexual Activity</a>
									</div>
									<div class="list-half">
                    <a href="<?php print $base_path . "category/sexually-transmitted-infections" ?>">Sexually Transmitted Infections</a>
										<a href="<?php print $base_path . "category/basics-sexuality" ?>">Basics of Sexuality</a>
										<a href="<?php print $base_path . "category/love-relationships" ?>">Love &amp; Relationships</a>
										<!-- <a href="<?php #print $base_path . "category/abortion" ?>">Legislation</a> -->
										<a href="<?php print $base_path . "category/sexual-difficulties" ?>">Sexual Difficulties</a>
										<a href="<?php print $base_path . "category/sexual-violence" ?>">Sexual Violence</a>
									</div>
								</div><!--.node-content-->
								<div class="node-footer">
									<a href="<?php print $base_path . "category" ?>">All Topics &raquo;</a>
								</div><!--.node-footer-->
							</div><!--.node-box-->
						</div><!-- .module -->
					</div><!-- .column-third -->


          <!-- Midle box
          ========================-->
					<div class="column-third">
						<!-- Frequently Asked Questions -->
						<h4 class="module-title">Frequently Asked Questions</h4>
						<div class="module">
							<div class="node-box">
								<div class="node-content">
									<div class="faq-image">
										<img class="" src="<?php print path_to_theme() . '/images/modules/' . 'masturbation.jpg'; ?>" />
										<div class="caption-slide">
											<h3>Is Masturbation Dangerous?</h3>
											<p>Masturbation, or self-stimulation of the genitals for pleasure, is not a dangerous or bad activity for men or women. In facts &hellip;
											<a href="http://www.soc.ucsb.edu/sexinfo/question/faq-masturbation-dangerous" class="more-button">Read More</a></p>
										</div><!-- .caption-slide -->
									</div><!-- .faq-image -->
								</div><!--.node-content-->
								<div class="node-footer">
									<a href="<?php print $base_path . "question" ?>">All Questions &raquo;</a>
								</div><!--.node-footer-->
							</div><!--.node-box-->
						</div><!-- .module -->
					</div><!-- .column-third -->


          <!-- Right box
          ========================-->
          <div class="column-third">
            <!-- BOX TITLE -->
            <h4 class="module-title">Ask the Sexperts</h4>
            <div class="module ask-module">
              <div class="node-box">
                <div class="node-content">
                  <p>Can't find an answer to your question? Want to send in feedback about the new site? We'd love to hear from you! Click <a href="<?php print $base_path . "ask-sexperts" ?>">here</a> to send a question to the Sexperts.</p>
                </div><!--.node-content-->
                <div class="node-footer">
                  <a href="<?php print $base_path . "ask-sexperts" ?>">Ask a Question &raquo;</a>
                </div><!--.node-footer-->
              </div><!--.node-box-->
            </div><!-- .module -->
          </div><!-- .column-third -->

				</div><!-- .box-module-container -->


			</div><!-- .clearfix -->

      <?php
      /*
			<div id="sidebar" class="right">
				<?php print render($page['sidebar_second']); ?>
			</div><!-- #sidebar.right -->
      */
      ?>

	</div><!-- .container.clearfix -->
</div><!-- #main.clearfix -->

<!-- ______________________ FOOTER _______________________ -->

<div id="footer">
  <div class="container clearfix">

	 <h4>About SexInfoOnline</h4>
		<p><?print '<a href="'. check_url($front_page) .'">';?>SexInfoOnline</a> is a website devoted to comprehensive sex education based on the best research we have to date. The site is maintained by university students from the University of California, Santa Barbara who have studied advanced topics in human sexuality.</p>
		<p>Our primary goal is to ensure that people around the world have access to useful and accurate information about all aspects of human sexuality. If you find our site useful, don't forget that your friends, family, and coworkers may find it useful too!</p>

		<a href="<?php print $base_path . "login" ?>" class="login">Writer Login &raquo;</a>

  </div><!-- /footer-container -->
</div><!-- /footer -->


<!-- ______________________ SECONDARY NAVIGATION _______________________ -->
  <div id="snavigation">
    <div class="container clearfix">
	  <p>UCSB SexInfo Copyright &copy; 2012 University of California, Santa Barbara. All Rights Reserved.</p>
    </div> <!-- /snavigation-container -->
  </div> <!-- /snavigation -->

<!-- ______________________ PAGE SCRIPTS _______________________ -->

<script type="text/javascript">
	// Code to run on page load

	(function ($) {

		//$(".new-banner").slideDown("slow");

		//----- Image carousel module ---//
		var loopRotate = true; // A boolean determining whether or not to automatically cycle. Disabled once user clicks one of the nav links.
    var numLinks = 8;

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
			if (i == (numLinks+1)) { i = 1; }
		} // end cycle()

		if (loopRotate == true) {
			// Cycle the frame every 5 seconds if the user hasn't clicked a link
			var loop = setInterval(cycle, 5000);
		}


		//----- Sliding captions for FAQ module ---//
		$(".caption-slide").hover(
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
