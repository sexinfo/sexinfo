<?php

/**
 * @file
 * Page callbacks for Google Analytics.
 */

/**
 * Page callback for admin/reports/google-analytics/detail.
 */
function google_analytics_reports_detail_page() {
  $path = isset($_GET['path']) ? $_GET['path'] : variable_get('google_analytics_reports_api_default_page', '/index.html');
  drupal_set_title(t('Content detail: @path', array('@path' => $path)));
  $report = array(
    'pageviews_chart' => _google_analytics_reports_pageviews($path),
    'stats' => _google_analytics_reports_detail_stats($path),
    'referrals' => _google_analytics_reports_top_referrals($path),
    'keywords' => _google_analytics_reports_top_keywords($path),
  );
  if (in_array(FALSE, $report, TRUE)) {
    drupal_set_message(_google_analytics_reports_error_message(), 'error');
    return ' ';
  }
  return theme('google_analytics_reports_detail', $report);
}

/**
 * Renders image with a chart of the number of visits over the past 30 days.
 */
function _google_analytics_reports_visits() {
  $params = array(
    'metrics' => array('ga:sessions'),
    'dimensions' => array('ga:date'),
    'sort_metric' => array('ga:date'),
    'start_date' => strtotime('-31 days'),
    'end_date' => strtotime('-1 day'),
  );
  $feed = google_analytics_reports_api_report_data($params);
  if ($feed->error) {
    return FALSE;
  }
  $max_visits = 0;
  $data = array();
  foreach ($feed->results->rows as $row) {
    $data[] = $row['sessions'];
    $max_visits = max($row['sessions'], $max_visits);
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
    '#shape_markers' => array(
      chart_shape_marker(0, 0, 'B', 0, $color = 'EEEEEE')),
    '#line_styles' => array(chart_line_style(2, 10, 0)),
  );
  $last_day = end($feed->results->rows);
  $title = t('The most sessions on a single day was @max. Yesterday there were @yesterday sessions.',
    array(
      '@max' => $max_visits,
      '@yesterday' => $last_day['sessions'],
    ));
  $chart['#attributes'] = array(
    'title' => $title,
    'style' => 'height:' . $chart['#size']['#height'] . 'px; width:100%',
  );
  $output = theme('chart', array('chart' => $chart));
  return $output;
}

/**
 * Renders image with a chart of the number of page views over the past 30 days.
 *
 * @param string $path
 *   Page path.
 */
function _google_analytics_reports_pageviews($path) {
  $params = array(
    'metrics' => array('ga:pageviews'),
    'dimensions' => array('ga:date'),
    'sort_metric' => array('ga:date'),
    'start_date' => strtotime('-31 days'),
    'end_date' => strtotime('-1 day'),
    'filters' => _google_analytics_reports_path_filter($path),
  );
  $feed = google_analytics_reports_api_report_data($params);
  if ($feed->error) {
    return FALSE;
  }
  $max_views = 0;
  $data = array();
  foreach ($feed->results->rows as $row) {
    $data[] = $row['pageviews'];
    $max_views = max($row['pageviews'], $max_views);
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
    '#shape_markers' => array(
      chart_shape_marker(0, 0, 'B', 0, $color = 'EEEEEE')),
    '#line_styles' => array(chart_line_style(2, 10, 0)),
  );
  $last_day = end($feed->results->rows);
  $title = t('The most views on a single day was @max. Yesterday there were @yesterday views.',
    array(
      '@max' => $max_views,
      '@yesterday' => $last_day['pageviews'],
    ));
  $chart['#attributes'] = array(
    'title' => $title,
    'style' => 'height:' . $chart['#size']['#height'] . 'px; width:100%',
  );
  $output = theme('chart', array('chart' => $chart));
  return $output;
}


/**
 * Retrieves usage data.
 */
function _google_analytics_reports_usage() {
  // Site usage.
  $params = array(
    'metrics' => array(
      'ga:sessions',
      'ga:bounces',
      'ga:entrances',
      'ga:pageviews',
      'ga:sessionDuration',
      'ga:newUsers',
    ),
    'start_date' => strtotime('-31 days'),
    'end_date' => strtotime('-1 day'),
  );
  $feed = google_analytics_reports_api_report_data($params);
  if ($feed->error) {
    return FALSE;
  }
  return $feed->results->totalsForAllResults;
}

/**
 * Retrieves content detail stats.
 *
 * @param string $path
 *  Page path.
 */
function _google_analytics_reports_detail_stats($path) {
  // Site usage.
  $params = array(
    'metrics' => array(
      'ga:pageviews',
      'ga:uniquePageviews',
      'ga:avgTimeOnPage',
      'ga:bounceRate',
      'ga:exitRate',
      'ga:goalValueAll',
    ),
    'start_date' => strtotime('-31 days'),
    'end_date' => strtotime('-1 day'),
    'filters' => _google_analytics_reports_path_filter($path),
  );
  $feed = google_analytics_reports_api_report_data($params);
  if ($feed->error) {
    return FALSE;
  }
  return $feed->results->totalsForAllResults;
}

/**
 * Retrieves top pages.
 *
 * @param int $max_results
 *  Maximum number of results [default=10].
 */
function _google_analytics_reports_top_pages($max_results = 10) {
  $params = array(
    'metrics' => array('ga:pageviews'),
    'dimensions' => array('ga:pageTitle', 'ga:hostname', 'ga:pagePath'),
    'sort_metric' => array('-ga:pageviews'),
    'start_date' => strtotime('-31 days'),
    'end_date' => strtotime('-1 day'),
    'sort' => '-ga:pageviews',
    'max_results' => $max_results,
  );
  $feed = google_analytics_reports_api_report_data($params);
  if ($feed->error) {
    return FALSE;
  }
  return $feed->results->rows;
}

/**
 * Retrieves top referrals.
 *
 * @param string $path
 *   Page path [default=''].
 */
function _google_analytics_reports_top_referrals($path = '') {
  $params = array(
    'metrics' => array('ga:sessions'),
    'dimensions' => array('ga:source', 'ga:medium'),
    'sort_metric' => array('-ga:sessions'),
    'filters' => 'ga:medium==referral',
    'start_date' => strtotime('-31 days'),
    'end_date' => strtotime('-1 day'),
    'max_results' => 10,
  );
  if ($path) {
    $params['filters'] .= ';' . _google_analytics_reports_path_filter($path);
  }
  $feed = google_analytics_reports_api_report_data($params);
  if ($feed->error) {
    return FALSE;
  }
  return $feed->results->rows;
}

/**
 * Retrieves top keywords.
 *
 * @param string $path
 *   Page path.
 */
function _google_analytics_reports_top_keywords($path = '') {
  $params = array(
    'metrics' => array('ga:sessions'),
    'dimensions' => array('ga:keyword'),
    'sort_metric' => array('-ga:sessions'),
    'start_date' => strtotime('-31 days'),
    'end_date' => strtotime('-1 day'),
    'filters' => 'ga:keyword!=(not set)',
    'max_results' => 10,
  );
  if ($path) {
    $params['filters'] .= ';' . _google_analytics_reports_path_filter($path);
  }
  $feed = google_analytics_reports_api_report_data($params);
  if ($feed->error) {
    return FALSE;
  }
  return $feed->results->rows;
}
