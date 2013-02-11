<?php
/**
 * @file
 * Chart views integration.
 *
 * @author Jurriaan Roelofs (http://drupal.org/user/52638)
 */

/**
 * Implements hook_views_plugins().
 */
function views_dataviz_views_plugins() {
  return array(
    'style'  => array(
      'views_dataviz' => array(
        'title' => t('Dataviz'),
        'help' => t('Display the results as chart, graph or text widget'),
        'handler' => 'views_dataviz_plugin_style_dataviz',
        'uses options' => TRUE,
        'uses row plugin' => FALSE,
        'uses fields' => TRUE,
        'type' => 'normal',
        'path' => drupal_get_path('module', 'views_dataviz'),
      ),
    ),
    'display_extender' => array(
      'views_dataviz_bigtext' => array(
        'title' => t('Dataviz BigText'),
        'help' => t('Load jQuery file for BigText support'),
        'handler' => 'views_dataviz_plugin_display_extender_dataviz_bigtext',
      ),
    ),
  );
}
