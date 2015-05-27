<?php
/**
 * @file
 * Callbacks for building blocks.
 */

/**
 * Page callback for google-analytics-reports/ajax/path-mini.
 */
function google_analytics_reports_path_mini_ajax() {
  $path = isset($_GET['path']) ? $_GET['path'] : '/index.html';
  return drupal_json_output(array('content' => google_analytics_reports_path_mini_build($path)));
}

/**
 * Page callback for google-analytics-reports/ajax/dashboard.
 */
function google_analytics_reports_dashboard_ajax() {
  return drupal_json_output(array('content' => google_analytics_reports_dashboard_build()));
}

/**
 * Generates a block with the current page statistics.
 */
function google_analytics_reports_path_mini_build($path) {
  if (!variable_get('google_analytics_reports_oauth_token', FALSE)) {
    return '<p>' . t('You must <a href="!url">authorize</a> Drupal to use your Analytics account before you can view reports.', array('!url' => url('admin/config/system/google-analytics-reports'))) . '</p>';
  }

  if ($path == '/') {
    $path = '/index.html';
  }

  $params = array(
    'metrics' => array('ga:pageviews'),
    'dimensions' => array('ga:date'),
    'sort_metric' => array('ga:date'),
    'filters' => _google_analytics_reports_path_filter($path),
    'start_date' => strtotime('-31 days'),
    'end_date' => strtotime('-1 day'),
  );
  $feed = google_analytics_api_report_data($params);
  if ($feed->error) {
    return '<p>' . _google_analytics_reports_error_message() . '</p>';
  }
  $max_visits = 0;
  foreach ($feed->results as $row) {
    $data[] = $row['pageviews'];
    $max_visits = max($row['pageviews'], $max_visits);
  }
  $chart = array(
    '#title' => '',
    '#chart_id' => 'pageviews_small_30d',
    '#data' => $data,
    '#type' => CHART_TYPE_LINE . ':nda',
    '#size' => chart_size(500, 40),
    '#adjust_resolution' => TRUE,
    '#data_colors' => array('AAAAAA'),
    '#chart_fill' => chart_fill('bg', '00000000'),
    '#shape_markers' => array(chart_shape_marker(0, 0, 'B', 0, $color = 'EEEEEE')),
    '#line_styles' => array(chart_line_style(2, 10, 0)),
  );
  $last_day = end($feed->results);
  $title = t('The most views on a single day was @max.  Yesterday there were @yesterday views.', array('@max' => $max_visits, '@yesterday' => $last_day['pageviews']));

  $chart['#attributes'] = array('title' => $title, 'style' => 'height:' . $chart['#size']['#height'] . 'px; width:100%');
  $report['chart'] = l(theme('chart', array('chart' => $chart)), 'admin/reports/google-analytics/detail', array('query' => array('path' => $path), 'html' => TRUE));
  $report['views'] = l(t('@views views this month', array('@views' => number_format($feed->totals['pageviews']))), 'admin/reports/google-analytics/detail', array('query' => array('path' => $path)));
  return theme('google_analytics_reports_path_mini', $report);
}

/**
 * Generates the dashboard block.
 */
function google_analytics_reports_dashboard_build() {
  $params = array(
    'metrics' => array('ga:visits'),
    'dimensions' => array('ga:date'),
    'sort_metric' => array('ga:date'),
    'start_date' => strtotime('-31 days'),
    'end_date' => strtotime('-1 day'),
  );
  $feed = google_analytics_api_report_data($params);
  if ($feed->error) {
    return FALSE;
  }
  $max_visits = 0;
  foreach ($feed->results as $row) {
    $data[] = $row['visits'];
    $max_visits = max($row['visits'], $max_visits);
  }
  $chart = array(
    '#title' => '',
    '#chart_id' => 'visits_large_30d',
    '#data' => $data,
    '#type' => CHART_TYPE_LINE . ':nda',
    '#size' => chart_size(1000, 80),
    '#adjust_resolution' => TRUE,
    '#data_colors' => array('AAAAAA'),
    '#chart_fill' => chart_fill('bg', '00000000'),
    '#shape_markers' => array(chart_shape_marker(0, 0, 'B', 0, $color = 'EEEEEE')),
    '#line_styles' => array(chart_line_style(2, 10, 0)),
  );
  $last_day = end($feed->results);
  $title = t('The most visits on a single day was @max.  Yesterday there were @yesterday visits.', array('@max' => $max_visits, '@yesterday' => $last_day['visits']));
  $chart['#attributes'] = array('title' => $title, 'style' => 'height:' . $chart['#size']['#height'] . 'px; width:100%');
  $report['chart'] = theme('chart', array('chart' => $chart));
  $report['chart'] = l($report['chart'], 'admin/reports/google-analytics', array('html' => TRUE));
  $report['visits'] = l(t('@visits visits this month', array('@visits' => number_format($feed->totals['visits']))), 'admin/reports/google-analytics');
  return theme('google_analytics_reports_dashboard', $report);
}