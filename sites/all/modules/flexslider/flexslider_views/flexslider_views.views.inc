<?php
/**
 * @file
 * Contains core functions for the Views module support.
 */

/*
 * Implements hook_views_plugins().
 *
 * This function annnounces the style plugin for flexslider views.
 */
function flexslider_views_plugins() {
  return array(
    'module' => 'flexslider', // This just tells our themes are elsewhere.
    'style' => array(
      // Style plugin for the navigation.
      'flexslider' => array(
        'title' => t('Flex Slide'),
        'help' => t('Display the results as a flexslider slider.'),
        'handler' => 'views_plugin_style_flexslider',
        'theme' => 'views_view_flexslider',
        'theme file' => 'theme.inc',
        'theme path' => drupal_get_path('module', 'flexslider') . '/theme',
        'uses row plugin' => FALSE,
        'uses fields' => TRUE,
        'uses options' => TRUE,
        'type' => 'normal',
        'even empty' => FALSE,
      ),
    ),
  );
}
