<?php
// $Id: page.tpl.php,v 1.1.2.1 2011/01/23 05:03:51 antsin Exp $

/*
+----------------------------------------------------------------+
|   BlogBuzz for Dupal 7.x - Version 1.0                         |
|   Copyright (C) 2011 Antsin.com All Rights Reserved.           |
|   @license - GNU GENERAL PUBLIC LICENSE                        |
|----------------------------------------------------------------|
|   Theme Name: BlogBuzz                                         |
|   Description: BlogBuzz by Antsin                              |
|   Author: Antsin.com                                           |
|   Website: http://www.antsin.com/                              |
|----------------------------------------------------------------+
*/  
?>

  <div id="page"><div id="page-inner">
    <?php if ($secondary_menu): ?>
      <div id="secondary"><div id="secondary-inner">
        <?php if ($secondary_nav): print $secondary_nav; endif; ?>
      </div></div> <!-- /#secondary-inner, /#secondary -->
    <?php endif; ?>
    <div id="header"><div id="header-inner" class="clearfix">
      <?php if ($logo || $site_name || $site_slogan): ?>
        <div id="logo-title">
          <?php if ($logo): ?>
            <div id="logo"><a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home"><img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" id="logo-image" /></a></div>
          <?php endif; ?>
		  <div id="site-name-slogan">
            <?php if ($site_name): ?>   
              <h1 id="site-name"><a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home"><?php print $site_name; ?></a></h1>
            <?php endif; ?>
            <?php if ($site_slogan): ?>
              <span id="site-slogan"><?php print $site_slogan; ?></span>
            <?php endif; ?>
		  </div>
        </div> <!-- /#logo-title -->
      <?php endif; ?>
	  <?php if ($page['header']): ?>
        <div id="header-blocks">
          <?php print render($page['header']); ?>
        </div> <!-- /#header-blocks -->
      <?php endif; ?>
    </div></div> <!-- /#header-inner, /#header -->

    <?php if ($main_menu || $page['primary_menu']): ?>
      <div id="primary">
	    <div id="primary-inner" class="clearfix">
          <?php if ($main_menu && !$page['primary_menu']): ?>
            <?php print $primary_nav; ?>
		  <?php else: ?>
            <span><?php print render($page['primary_menu']); ?></span>
		  <?php endif; ?>
	    </div>
	  </div>
    <?php endif; ?> <!-- /#primary -->

	<div id="main">
	  <div id="main-inner">
	    <div class="clearfix">
          <?php if ($page['showcase']): ?>
	        <div id="showcase" ><div id="showcase-inner" class="clearfix">
              <?php print render($page['showcase']); ?>
		    </div></div>
          <?php endif; ?>  
          <div id="content">
		    <?php if ($breadcrumb && $breadcrumb != '<div class="breadcrumb"></div>'): print '<div id="breadcrumb">'.t('You are here: ').$breadcrumb.'</div>';
		    else: print '<div id="breadcrumb">'.t('You are here: ').'<a href="/">Home</a></div>'; 
		    endif; ?>	
            <div id="content-inner">	      
		      <?php if ($title || $tabs || $help || $messages): ?>
                <div id="content-header">
                  <?php if ($title): ?>
                    <h1 class="title"><?php print $title; ?></h1>
                  <?php endif; ?>
                  <?php print $messages; ?>
                  <?php if ($tabs): ?>
                    <div class="tabs"><?php print render($tabs); ?></div>
                  <?php endif; ?>
                  <?php print render($page['help']); ?>
                </div> <!-- /#content-header -->
              <?php endif; ?>
              <?php print render($page['content']); ?>
            </div> <!-- /#content-inner -->	
          </div> <!-- /#content -->

          <?php if ($page['sidebar_first']): ?>
            <div id="sidebar-left">
              <?php print render($page['sidebar_first']); ?>
            </div> <!-- /#sidebar-left -->
          <?php endif; ?>

          <?php if ($page['sidebar_second']): ?>
            <div id="sidebar-right">
              <?php print render($page['sidebar_second']); ?>
            </div> <!-- /#sidebar-right -->
          <?php endif; ?>
	    </div>

        <?php if ($page['main_bottom_one'] || $page['main_bottom_two'] || $page['main_bottom_three'] || $page['main_bottom_four']): ?>
          <div id="main-bottom"><div id="main-bottom-inner" class="main_bottom-<?php print (bool) $page['main_bottom_one'] + (bool) $page['main_bottom_two'] + (bool) $page['main_bottom_three'] + (bool) $page['main_bottom_four']; ?> clearfix">
            <?php if ($page['main_bottom_one']): ?>
              <div id="main-bottom-one" class="column">
                <?php print render($page['main_bottom_one']); ?>
              </div><!-- /main-bottom-one -->
            <?php endif; ?>
            <?php if ($page['main_bottom_two']): ?>
              <div id="main-bottom-two" class="column">
                <?php print render($page['main_bottom_two']); ?>
              </div><!-- /main-bottom-two -->
            <?php endif; ?>
	        <?php if ($page['main_bottom_three']): ?>
              <div id="main-bottom-three" class="column">
                <?php print render($page['main_bottom_three']); ?>
              </div><!-- /main-bottom-three -->
            <?php endif; ?>
		    <?php if ($page['main_bottom_four']): ?>
              <div id="main-bottom-four" class="column">
                <?php print render($page['main_bottom_four']); ?>
              </div><!-- /main-bottom-four -->
            <?php endif; ?>
          </div></div> 
	    <?php endif; ?>  
	  </div>
	</div><!-- /#main-inner, /#main --> 

    <?php if ($page['footer_one'] || $page['footer_two'] || $page['footer_three'] || $page['footer_four']): ?>
      <div id="footer"><div id="footer-inner" class="footer-<?php print (bool) $page['footer_one'] + (bool) $page['footer_two'] + (bool) $page['footer_three'] + (bool) $page['footer_four']; ?> clearfix">
        <?php if ($page['footer_one']): ?>
          <div id="footer-one" class="column">
            <?php print render($page['footer_one']); ?>
          </div><!-- /footer-one -->
        <?php endif; ?>
        <?php if ($page['footer_two']): ?>
          <div id="footer-two" class="column">
            <?php print render($page['footer_two']); ?>
          </div><!-- /footer-two -->
        <?php endif; ?>
		<?php if ($page['footer_three']): ?>
          <div id="footer-three" class="column">
            <?php print render($page['footer_three']); ?>
          </div><!-- /footer-three -->
        <?php endif; ?>
		<?php if ($page['footer_four']): ?>
          <div id="footer-four" class="column">
            <?php print render($page['footer_four']); ?>
          </div><!-- /footer-four -->
        <?php endif; ?>
      </div></div> <!-- /#footer-inner, /#footer -->
    <?php endif; ?>  
    
	<div id="closure"><div id="closure-inner"><div id="designed-by"><small><a href="http://www.antsin.com/en/" title="Drupal Theme">Designed by Antsin.com</a></small></div><?php print render($page['footer']); ?></div></div>
  </div></div> <!-- /#page-inner, /#page -->