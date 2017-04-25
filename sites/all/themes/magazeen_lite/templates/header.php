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
    </div><!--

    --><?php include 'navigation.php' ?>


  </div> <!-- /header-container -->
</div> <!-- /header -->
