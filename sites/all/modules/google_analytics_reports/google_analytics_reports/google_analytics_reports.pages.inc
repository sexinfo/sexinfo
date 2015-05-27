<?php
/**
 * @file
 * Page callbacks for google analytics.
 */

/**
 * Page callback for admin/reports/google-analytics.
 *
 * @return An HTML summary of the site-wide statistics.
 */
function google_analytics_reports_summary_page() {
  if (!variable_get('google_analytics_reports_oauth_token', FALSE)) {
    drupal_set_message(t('You must <a href="!url">authorize</a> Drupal to use your Analytics account before you can view reports.', array('!url' => url('admin/config/system/google-analytics-reports'))), 'warning');
    return ' ';
  }

  $summary = array(
    'visit_chart' => _google_analytics_reports_visits(),
    'usage' => _google_analytics_reports_usage(),
    'pages' => _google_analytics_reports_top_pages(),
    'referrals' => _google_analytics_reports_top_referrals(),
    'keywords' => _google_analytics_reports_top_keywords(),
  );

  if (in_array(FALSE, $summary, TRUE)) {
    $menu_item = menu_get_item();
    if ($menu_item['href'] == 'admin/reports/google-analytics') {
      drupal_set_message(_google_analytics_reports_error_message(), 'error');
      return ' ';
    }
    else {

    }
    return '<p>' . _google_analytics_reports_error_message() . '</p>';
  }

  return theme('google_analytics_reports_summary', $summary);
}

/**
 * Page callback for admin/reports/google-analytics/detail.
 */
function google_analytics_reports_detail_page() {
  $path = isset($_GET['path']) ? $_GET['path'] : '/index.html';
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
 * Renders an img element with a chart of the number of visits over the past 30 days.
 */
function _google_analytics_reports_visits() {
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
  $output = theme('chart', array('chart' => $chart));
  return $output;
}

/**
 * Renders an img element with a chart of the number of pageviews over the past 30 days.
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
  $feed = google_analytics_api_report_data($params);
  if ($feed->error) {
    return FALSE;
  }
  $max_views = 0;
  foreach ($feed->results as $row) {
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
    '#shape_markers' => array(chart_shape_marker(0, 0, 'B', 0, $color = 'EEEEEE')),
    '#line_styles' => array(chart_line_style(2, 10, 0)),
  );
  $last_day = end($feed->results);
  $title = t('The most views on a single day was @max.  Yesterday there were @yesterday views.', array('@max' => $max_views, '@yesterday' => $last_day['pageviews']));
  $chart['#attributes'] = array('title' => $title, 'style' => 'height:' . $chart['#size']['#height'] . 'px; width:100%');
  $output = theme('chart', array('chart' => $chart));
  return $output;
}


/**
 * Retrieves usage data.
 */
function _google_analytics_reports_usage() {
  // Site usage
  $params = array(
    'metrics' => array('ga:visits', 'ga:bounces', 'ga:entrances', 'ga:pageviews', 'ga:timeOnSite', 'ga:newVisits'),
    'start_date' => strtotime('-31 days'),
    'end_date' => strtotime('-1 day'),
  );
  $feed = google_analytics_api_report_data($params);
  if ($feed->error) {
    return FALSE;
  }
  return $feed->totals;
}

/**
 * Retrieves content detail stats.
 */
function _google_analytics_reports_detail_stats($path) {
  // Site usage
  $params = array(
    'metrics' => array('ga:pageviews', 'ga:uniquePageviews', 'ga:avgTimeOnPage', 'ga:entranceBounceRate', 'ga:exitRate', 'ga:goalValueAll'),
    'start_date' => strtotime('-31 days'),
    'end_date' => strtotime('-1 day'),
    'filters' => _google_analytics_reports_path_filter($path),
  );
  $feed = google_analytics_api_report_data($params);
  if ($feed->error) {
    return FALSE;
  }
  return $feed->totals;
}

/**
 * Retrieves top pages.
 */
function _google_analytics_reports_top_pages() {
  $params = array(
    'metrics' => array('ga:pageviews'),
    'dimensions' => array('ga:pageTitle', 'ga:hostname', 'ga:pagePath'),
    'sort_metric' => array('-ga:pageviews'),
    'start_date' => strtotime('-31 days'),
    'end_date' => strtotime('-1 day'),
    'sort' => '-ga:pageviews',
    'max_results' => 10,
  );
  $feed = google_analytics_api_report_data($params);
  if ($feed->error) {
    return FALSE;
  }
  return $feed->results;
}

/**
 * Retrieves top referrals.
 */
function _google_analytics_reports_top_referrals($path = '') {
  $params = array(
    'metrics' => array('ga:visits'),
    'dimensions' => array('ga:source', 'ga:medium'),
    'sort_metric' => array('-ga:visits'),
    'filters' => 'ga:medium==referral',
    'start_date' => strtotime('-31 days'),
    'end_date' => strtotime('-1 day'),
    'max_results' => 10,
  );
  if ($path) {
    $params['filters'] .= ';' . _google_analytics_reports_path_filter($path);
  }
  $feed = google_analytics_api_report_data($params);
  if ($feed->error) {
    return FALSE;
  }
  return $feed->results;
}

/**
 * Retrieves top keywords.
 */
function _google_analytics_reports_top_keywords($path = '') {
  $params = array(
    'metrics' => array('ga:visits'),
    'dimensions' => array('ga:keyword'),
    'sort_metric' => array('-ga:visits'),
    'start_date' => strtotime('-31 days'),
    'end_date' => strtotime('-1 day'),
    'filters' => 'ga:keyword!=(not set)',
    'max_results' => 10,
  );
  if ($path) {
    $params['filters'] .= ';' . _google_analytics_reports_path_filter($path);
  }
  $feed = google_analytics_api_report_data($params);
  if ($feed->error) {
    return FALSE;
  }
  return $feed->results;
}