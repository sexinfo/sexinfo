<?php
/**
 * @file
 * Provides API documentation.
 *
 * @author Jurriaan Roelofs (http://drupal.org/user/52638)
 */

/**
 * Alter a views chart before it is rendered.
 *
 * This hook is executed before views_dataviz_alter() so additional changes may be made
 * in views_dataviz_alter()
 *
 * @param $chart
 *   An associative array defining a chart.
 * @param $view
 *   The name of the view to which the chart belongs.
 * @param $display
 *   The name of the display to which the chart belongs.
 * @see views_dataviz_alter()
 */
function hook_views_dataviz_alter(&$chart, $view, $display) {
  $chart['#title'] .= ' (altered)';
}
