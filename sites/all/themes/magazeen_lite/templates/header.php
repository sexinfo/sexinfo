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
    </div><!-- .social-buttons -->

  </div> <!-- /header-container -->
</div> <!-- /header -->

<div class="banner new-banner">
  The Sexperts are taking questions!
  <a href="<?php print $base_path . "ask-sexperts" ?>">We'd love to hear from you &raquo;</a>
</div>
