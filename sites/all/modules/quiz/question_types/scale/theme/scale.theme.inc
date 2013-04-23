<?php

/**
 * The theme file for scale.
 *
 * @file
 * Theming functions for the scale question type.
 */

/**
 * Theme function for the scale creation form.
 *
 * This function only adds js to the page. Js should be added to forms through theme functions
 * like this one. Js can't be added to the page from the form function because sometimes the form
 * is returned from cache.
 *
 * @param $form
 *  The scale creation form
 */
function theme_scale_creation_form($form) {
  $path = drupal_get_path('module', 'scale') .'/scale.js';
  drupal_add_js($path, 'module');
}

/**
 * Theme function for the answer part of the node view
 *
 * @param $alternatives
 *  Array of alternatives as text
 */
function theme_scale_answer_node_view($alternatives) {
  $header = array(t('Alternatives'));
  $rows = array();
  foreach($alternatives as $alternative) {
    $rows[] = array($alternative);
  }
  return theme('table', $header, $rows);
}

/**
 * Theme the scale response form
 *
 * @param $form
 *  The response form
 */
function theme_scale_response_form($form) {
  return drupal_render($form);
}