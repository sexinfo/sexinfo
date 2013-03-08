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

      <div style="display: inline-block; margin-top: 3px">
        <iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2FSexInfoOnline&amp;send=false&amp;layout=standard&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=dark&amp;action=like&amp;height=35" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:35px;" allowTransparency="true"></iframe>
      </div>
    </div><!-- .social-buttons -->

  </div> <!-- /header-container -->
</div> <!-- /header -->

<!-- TODO update header on questions close -->
<!--<div class="banner new-banner">
  The Sexperts are taking questions!
  <a href="<?php print $base_path . "ask-sexperts" ?>">We'd love to hear from you &raquo;</a>
</div>-->
