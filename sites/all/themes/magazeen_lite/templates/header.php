<div id="header">
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

<?php
  $result     = db_query("select * from {node} where status = 1 and promote = 1 order by rand() limit 1");
  $randomNode = $result->fetch()
?>

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
          print '<a href="'. check_url($front_page) .'" title="'. $site_title .'">';
          if ($logo) {
            print '<img src="'. check_url($logo) .'" alt="'. $site_title .'" />';
          }
          print '</a>';
        }
    //print '<h2 class="slogan">'. 'A website devoted to sex, health, & relationships.' .'</h2>';
      ?>
      <!--img src="/sexinfo/sites/default/files/slogan.png" alt="A website devoted to sex, health, and relationships." id="slogan-image"/-->
    </div>

    <?php include 'navigation.php' ?> 

    <div id="searchform-header">
      <?php print render($page['search_area']); ?>
    </div>

    <!--<div class="social-buttons">
    	<a class="btn" href="https://www.facebook.com/SexInfoOnline"><i class="icon-2x icon-facebook-sign"></i></a>
    	<a class="btn" href="https://twitter.com/sexinfoonline"><i class="icon-2x icon-twitter-sign"></i></a>
    	<a class="btn" href="https://instagram.com/ucsbsexperts/"><i class="icon-2x fa-instagram"></i></i></a>
    	<a class="btn" href="https://github.com/sexinfo/sexinfo"><i class="icon-2x icon-github-alt"></i></a>
    </div>--> <!-- .social-buttons -->

  </div> <!-- /header-container -->
</div> <!-- /header -->

<!--
<div class="banner new-banner">
  The Sexperts are back and taking questions!
  <a href="<?php #print $base_path . "ask-sexperts" ?>">We'd love to hear from you &raquo;</a>
</div>
-->
