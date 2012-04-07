<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language ?>" lang="<?php print $language->language ?>" dir="<?php print $language->dir ?>">

<head>
  <title><?php print $head_title; ?></title>
  <?php print $head; ?>
  <?php print $styles; ?>
  <?php print $scripts; ?>
</head>
<body class="<?php print $classes; ?>">
  
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
    

    <div class="social-buttons">
      <a class="soc-fb" href="https://www.facebook.com/SexInfoOnline">Facebook</a>
      <a class="soc-yt" href="http://www.youtube.com/user/UCSBSexInfoOnline">YouTube</a>
      <!-- <a class="soc-rss" href="#">RSS</a> -->
    </div><!-- .social-buttons -->

  </div> <!-- /header-container -->    
</div> <!-- /header -->


<!-- ______________________ NAVIGATION _______________________ -->

<?php if (!empty($main_menu)): ?>
  <div id="navigation"><!-- navigation -->
    <div class="container clearfix">
      <?php print theme('links', array('links' => $main_menu, 'attributes' => array('id' => 'main-menu', 'class' => array('links', 'main-menu')))); ?>
    </div>
  </div><!-- #navigation -->
<?php endif;?>

<!-- ______________________ MAIN _______________________ -->

<div id="main-front" class="clearfix">
  <div class="container clearfix">

      <h1 style="font-size: 2.5em; margin-bottom: 15px">SexInfo Online is currently down for maintenance!</h1>
      <p style="font-size: 1.6em; margin-bottom: 100px;">We're working hard to bring you a new and improved site. We'll be back soon - thanks for your patience!</p>
    
  </div><!-- .container.clearfix -->
</div><!-- #main.clearfix -->
 
<!-- ______________________ FOOTER _______________________ -->

<div id="footer">
  <div class="container clearfix">
  
   <h4>About SexInfoOnline</h4>
    <p><?print '<a href="'. check_url($front_page) .'">';?>SexInfo Online</a> is a website devoted to comprehensive sex education based on the best research we have to date. The site is maintained by university students from the University of California, Santa Barbara who have studied advanced topics in human sexuality.</p>
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

</body>
</html>