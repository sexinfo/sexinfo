<?php
// $Id$

/**
 * @file
 * Contains the functions used to theme the outputs of this module.
 *
 * Currently this modules implements two custom form elements. The first
 * is a slider that is used on the configuration page to set the thresholds.
 *
 * The second element is used to display the results of the checks after
 * submitting the node_form.
 */


/**
 * Defines the slider form element and its default values
 */
function seo_checker_element_info() {
  $type['seo_slider_at_least'] = array(
    '#input' => TRUE,
    '#steps' => 100,
    '#size' => 3,
    '#theme' => 'seo_slider_at_least',
  );
  $type['seo_slider_range'] = array(
    '#input' => TRUE,
    '#steps' => 100,
    '#size' => 3,
    '#theme' => 'seo_slider_range',
  );
  $type['seo_check_results'] = array(
    '#results' => array(),
    '#theme' => 'seo_check_results',
  );
  return $type;
}

/**
 * Implements hook_theme().
 */
function seo_checker_theme($existing, $type, $theme, $path) {
  return array(
    'seo_slider_at_least' => array(
       'render element' => 'element',
       'template' => 'slider-atleast',
       'path' => "$path/theme",
    ),
    'seo_slider_range' => array(
       'render element' => 'element',
       'template' => 'slider-range',
       'path' => "$path/theme",
    ),
    'seo_check_results' => array(
      'render element' => 'element',
    )
  );
}

/**
 * By default the check results are themed as a normal form item whose
 * value contains the check results, rendered as a table.
 */
function theme_seo_check_results($element) {
  drupal_add_css(drupal_get_path('module', 'seo_checker') . '/css/seo_checker.css');
  $element = $element['element'];
  $output = '<div class="seo-check-results">';
  $output .= '<b>' . t('SEO Check Results') . ':</b>';
  $output .= theme(
    'table',
    array(
      'header' => array(t('Rule'), t('Message'), t('Achieved'), t('Required'), t('Passed')),
      'rows' => $element['#results'],
      'attributes' => array('class' => array('asdf')),
    )
  );
  $output .= '</div>';
  return $output;
}
