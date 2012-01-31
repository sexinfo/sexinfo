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
  </div> <!-- /header-container -->    
</div> <!-- /header -->

<!-- ______________________ NAVIGATION _______________________ -->

<?php if (!empty($main_menu)): ?>
  <div id="navigation"><!-- navigation -->
    <div class="container clearfix">
      <?php print theme('links', array('links' => $main_menu, 'attributes' => array('id' => 'main-menu', 'class' => array('links', 'main-menu')))); ?>
    </div> <!-- /navigation-container -->
  </div> <!-- /navigation -->
<?php endif;?>

<!-- ______________________ SLIDESHOW _______________________ -->
<?php
/*
  * By default, the slideshow was set to appear on all pages. This seems pointless,
  * but if we want to use it, just copy the code over to page-front.tpl.php or wherever
  * else you want to use it.
  * -Andrew
*/
?>
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
<div id="main" class="clearfix">
	<div class="container clearfix">
		<div class="main">
			<?php if ($breadcrumb): print $breadcrumb; endif; ?>

			<?php 
				# View and Edit buttons
				if ($tabs): print '<div id="tabs-wrapper" class="clear-block">' . render($tabs) .'</div>'; endif; 
			?>

			<?php 
				# "has been successfully updated, etc"
				if ($show_messages && $messages): print $messages; endif;
			?>

			<?php print render($page['help']); ?>
			
			<div class="clearfix">
				<?php print render($page['content']); ?>
			</div>
		
			<div id="sidebar" class="right">
				<?php print render($page['sidebar_second']); ?> 
			</div><!-- #sidebar.right -->
		
		</div><!-- .main -->
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
  <div id="snavigation"><!-- snavigation -->
    <div class="container clearfix">
      <?php #print theme('links', array('links' => $main_menu, 'attributes' => array('id' => 'secondary-links', 'class' => array('slinks', 'main-menu')))); ?>
	  <p>UCSB SexInfo Copyright &copy; 2012 University of California, Santa Barbara. All Rights Reserved.</p>
    </div> <!-- /snavigation-container -->
  </div> <!-- /snavigation -->
<?php endif;?>

<!-- ______________________ LINK-BACK _______________________ -->
<?php
/*
<div id="link-back">
  <div class="container clearfix">
    <a title="Brought To You By: www.SmashingMagazine.com" class="smashing" href="http://www.smashingmagazine.com" target="_blank">Brought to you By: www.SmashingMagazine.com</a>
    <a title="In Partner With: www.WeFunction.com" class="function" href="http://www.wefunction.com" target="_blank">In Partner with: www.WeFunction.com</a>
    <a title="Drupalizing" class="drupalizing" href="http://www.drupalizing.com" target="_blank">Drupalizing</a>
    <div id="footer-message" style="clear:both;">
      <div class="description">Ported to Drupal for the Open Source Community by <a href="http://www.drupalizing.com">Drupalizing</a>, a Project of <a href="http://www.morethanthemes.com">More than Themes</a></div>
    </div>
  </div> <!-- /link-back-container -->
</div> <!-- /link-back -->
*/
?>