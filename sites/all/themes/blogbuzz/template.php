<?php
// $Id: template.php,v 1.3.2.1 2011/01/23 05:03:13 antsin Exp $

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

/**
 * HTML preprocessing
 */
function blogbuzz_preprocess_html(&$vars) {

  function get_blogbuzz_style() {
    $style = theme_get_setting('style');
    return $style;
  }

  drupal_add_css(drupal_get_path('theme','blogbuzz') . '/css/' . get_blogbuzz_style() . '.css', array('group' => CSS_THEME, 'every_page' => TRUE));
  // Add conditional stylesheets for IE
  drupal_add_css(path_to_theme() . '/css/ie7.css', array('group' => CSS_THEME, 'browsers' => array('IE' => 'IE 7', '!IE' => FALSE), 'preprocess' => FALSE));
  drupal_add_css(path_to_theme() . '/css/ie8.css', array('group' => CSS_THEME, 'browsers' => array('IE' => 'IE 8', '!IE' => FALSE), 'preprocess' => FALSE));
}

/**
 * Override or insert variables into the page template.
 */
function blogbuzz_process_page(&$vars) {

  if (isset($vars['main_menu'])) {
    $vars['primary_nav'] = theme('links__system_main_menu', array(
      'links' => $vars['main_menu'],
      'attributes' => array(
        'class' => array('links', 'inline', 'main-menu'),
      ),
      'heading' => array(
        'text' => t('Main menu'),
        'level' => 'h2',
        'class' => array('element-invisible'),
      )
    ));
  }
  else {
    $vars['primary_nav'] = FALSE;
  }
  if (isset($vars['secondary_menu'])) {
    $vars['secondary_nav'] = theme('links__system_secondary_menu', array(
      'links' => $vars['secondary_menu'],
      'attributes' => array(
        'class' => array('links', 'inline', 'secondary-menu'),
      ),
      'heading' => array(
        'text' => t('Secondary menu'),
        'level' => 'h2',
        'class' => array('element-invisible'),
      )
    ));
  }
  else {
    $vars['secondary_nav'] = FALSE;
  }
}

/**
 * Override or insert variables into the node template.
 */
function blogbuzz_preprocess_node(&$vars) {

  if ($vars['view_mode'] != 'full' && $vars['id'] == 1) {
    $vars['classes_array'][] = 'node-first';
  }

  if ($vars['view_mode'] == 'full' && node_is_page($vars['node'])) {
    $vars['classes_array'][] = 'node-full';
  }
}

/**
 * Implements theme_menu_tree().
 */
function blogbuzz_menu_tree($vars) {
  return '<ul class="menu clearfix">' . $vars['tree'] . '</ul>';
}

function blogbuzz_button($vars) {
  $element = $vars['element'];
  $element['#attributes']['type'] = 'submit';
  element_set_attributes($element, array('id', 'name', 'value'));

  $element['#attributes']['class'][] = 'form-' . $element['#button_type'];
  if (!empty($element['#attributes']['disabled'])) {
    $element['#attributes']['class'][] = 'form-button-disabled';
  }
  return '<span class="button"><input' . drupal_attributes($element['#attributes']) . ' /></span>';
}