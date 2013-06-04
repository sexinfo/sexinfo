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


	<a class="btn" href="https://www.facebook.com/SexInfoOnline"><i class="icon-2x icon-facebook-sign"></i></a>
	<a class="btn" href="https://twitter.com/sexinfoonline"><i class="icon-2x icon-twitter-sign"></i></a>
	<a class="btn" href="http://www.youtube.com/user/UCSBSexInfoOnline"><i class="icon-2x icon-facetime-video"></i></a>
	<a class="btn" href="https://github.com/sexinfo/sexinfo"><i class="icon-2x icon-github-alt"></i></a>

    </div> <!-- .social-buttons -->

  </div> <!-- /header-container -->
</div> <!-- /header -->

<!--
<div class="banner new-banner">
  The Sexperts are back and taking questions!
  <a href="<?php #print $base_path . "ask-sexperts" ?>">We'd love to hear from you &raquo;</a>
</div>
-->
