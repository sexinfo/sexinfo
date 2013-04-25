<?php


<<<<<<< HEAD
/**
=======
/*
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
 * @file
 * Administration file for Quiz stats Module
 *
 */

/**
 * Page for selecting between several quiz revisions
 *
 * @param $nid
 *  Quiz node id
 * @return
 *  Themed html
 */
function quiz_stats_revision_selector_page($quiz) {
<<<<<<< HEAD
  $res = db_query('SELECT vid FROM {node_revision} WHERE nid = :nid ORDER BY vid', array(':nid' => $quiz->nid));
  $count = 0; //uses this variable to slightly increase performance
  $vids = array();
  while ($res_o = $res->fetch()) {
    $vids[] = $res_o->vid;
    $count++;
  }
  if ($count < 1) {
    return t('Something went wrong. Please try again');
  }
=======
  $sql = 'SELECT vid FROM {node_revisions} WHERE nid = %d ORDER BY vid';
  $res = db_query($sql, $quiz->nid);
  $count = 0; //uses this variable to slightly increase performance
  $vids = array();
  while ($res_o = db_fetch_object($res)) {
    $vids[] = $res_o->vid;
    $count++;
  }
  if ($count < 1)
    return t('Something went wrong. Please try again');
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  elseif ($count == 1) {
    // If there is only one revision we jump directly to that revision
    drupal_goto("node/{$quiz->nid}/statistics/{$vids[0]}");
  }
  else {
    $content = array();
    $content['explanation'] = t('There are !num revisions of this quiz that have been taken.
    Different revisions may have different scoring, difficulity and other differences affecting its statistics.
    Because of this you have to choose the revision you want to see statistics from.', array('!num' => $count));
    $content['links'] = array();
    foreach ($vids as $key => $value) {
      $content['links'][] = 'node/' . $quiz->nid . '/statistics/' . $value;
    }
<<<<<<< HEAD
    return theme('quiz_stats_revision_selector', array('content' => $content));
=======
    return theme('quiz_stats_revision_selector', $content);
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  }
}

/**
 * Returns statistics for all available quizzes
 *
 * @param $uid
 *   User id
 * @return
 *   HTML table
 */
function quiz_stats_get_basic_stats($uid = 0) {
  $results = _quiz_get_quizzes($uid);
<<<<<<< HEAD
  return empty($results) ? t('No Statistics found.') : theme('quiz_stats_get_basic_stats', array('results' => $results));
=======
  return empty($results) ? t('No Statistics found.') : theme('quiz_stats_get_basic_stats', $results);
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
}

/**
 * Get stats for a single quiz. Maybe also for a single user.
 *
 * @param $vid
 *   quiz revision id
 *
 * @param $uid
 *   User id
 *
 * @return
 *   HTML page with charts/graphs
 */
function quiz_stats_get_adv_stats($vid, $uid = 0) {
  $charts = array();
<<<<<<< HEAD
  drupal_add_css(drupal_get_path('module', 'quiz_stats') . '/quiz_stats.css', array('type' => 'file', 'weight' => CSS_THEME));
=======
  drupal_add_css(drupal_get_path('module', 'quiz_stats') .'/quiz_stats.css', 'theme');
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  $charts['takeup'] = _get_date_vs_takeup_count_chart($vid, $uid);
  // line chart/graph showing quiz takeup date along x-axis and count along y-axis
  $charts['status'] = _get_quiz_status_chart($vid, $uid);
  // 3D pie chart showing percentage of pass, fail, incomplete quiz status
  $charts['top_scorers'] = _get_quiz_top_scorers_chart($vid, $uid);
  // Bar chart displaying top scorers
  $charts['grade_range'] = _get_quiz_grade_range_chart($vid, $uid);
<<<<<<< HEAD
  return theme('quiz_stats_charts', array('charts' => $charts));
=======
  return theme('quiz_stats_charts', $charts);
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
}

/**
 * Generates grade range chart
 *
 * @param $uid
 *   User id
 * @param $vid
 *   revision id of quiz node
 * @return
 *   array with chart and metadata
 */
function _get_quiz_grade_range_chart($vid, $uid = 0) {
  // @todo: make the ranges configurable
<<<<<<< HEAD
  $sql = 'SELECT SUM(score >= 0 && score < 20) AS zero_to_twenty,
=======
  $sql = "SELECT SUM(score >= 0 && score < 20) AS zero_to_twenty,
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
    SUM(score >= 20 && score < 40) AS twenty_to_fourty,
    SUM(score >= 40 && score < 60) AS fourty_to_sixty,
    SUM(score >= 60 && score < 80) AS sixty_to_eighty,
    SUM(score >= 80 && score <= 100) AS eighty_to_hundred
    FROM {quiz_node_results}
<<<<<<< HEAD
    WHERE vid = :vid';
  $arg[':vid'] = $vid;
  if ($uid != 0) {
    $sql .= ' AND uid = :uid';
    $arg[':uid'] = $uid;
  }
  $range = db_query($sql, $arg)->fetch();
=======
    WHERE vid = %d";
  if ($uid != 0) {
    $sql .= " AND uid = %d";
  }
  $range = db_fetch_object(db_query($sql, $vid, $uid));
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  $count = $range->zero_to_twenty + $range->twenty_to_fourty + $range->fourty_to_sixty + $range->sixty_to_eighty + $range->eighty_to_hundred;
  if ($count < 2) {
    return FALSE;
  }
  // Get the charts
  $chart = '<div id="quiz_top_scorers" class="quiz-stats-chart-space">';
<<<<<<< HEAD
  $chart .= theme('quiz_grade_range', array('range' => $range));
=======
  $chart .= theme('quiz_grade_range', $range);
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  $chart .= '</div>';
  // Return the chart with some meta data
  return array(
    'chart' => $chart,
    'title' => t('Distribution'),
    'explanation' => t('This chart shows the distribution of the scores on this quiz.'),
  );
}

/**
 * Generates the top scorers chart
 *
 * @param $vid
 *   revision id of quiz node
 *
 * @param $uid
 *   User id
 *
 * @return
 *   array with chart and metadata
 */
function _get_quiz_top_scorers_chart($vid, $uid = 0) {
  $top_scorers = array();
  $sql = 'SELECT name, score FROM {quiz_node_results} qnr
    LEFT JOIN {users} u ON (u.uid = qnr.uid)
<<<<<<< HEAD
    WHERE vid = :vid';
  $arg[':vid'] = $vid;
  if ($uid != 0) {
    $sql .= ' AND qnr.uid = :uid';
    $arg[':uid'] = $uid;
  }
  $sql .= ' ORDER by score DESC';
  $results = db_query($sql, $arg);
  while ($result = $results->fetchAssoc()) {
    $key = $result['name'] . '-' . $result['score'];
    $top_scorers[$key] = $result;
  }
  if (count($top_scorers) == 0) {
    return FALSE;
  }
  $chart = '<div id="quiz_top_scorers" class="quiz-stats-chart-space">';
  $chart .= theme('quiz_top_scorers', array('scorer' => $top_scorers));
=======
    WHERE vid = %d';
  if ($uid != 0) {
    $sql .= " AND qnr.uid = %d";
  }
  $sql .= ' ORDER by score DESC';
  $results = db_query_range($sql, $vid, $uid, 0, 10);
  while ($result = db_fetch_array($results)) {
    $key = $result['name'] . '-' . $result['score'];
    $top_scorers[$key] = $result;
  }
  if (count($top_scorers) == 0) return FALSE;
  $chart = '<div id="quiz_top_scorers" class="quiz-stats-chart-space">';
  $chart .= theme('quiz_top_scorers', $top_scorers);
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  $chart .= '</div>';
  return array(
    'chart' => $chart,
    'title' => t('Top scorers'),
    'explanation' => t('This chart shows what question takers have the highest scores'),
  );
}

/**
 * Generates a chart showing the status for all registered responses to a quiz
 *
 * @param $vid
 *   revision id of quiz node
 *
 * @param $uid
 *   User id
 *
 * @return
 *   HTML to render to chart/graph
 */
function _get_quiz_status_chart($vid, $uid = 0) {
  // get the pass rate of the given quiz by vid
<<<<<<< HEAD
  $pass_rate = db_query("SELECT pass_rate FROM {quiz_node_properties} WHERE vid = :vid", array(':vid' => $vid))->fetchField();
=======
  $pass_rate = db_result(db_query("SELECT pass_rate FROM {quiz_node_properties} WHERE vid = %d", $vid));
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  if (!$pass_rate) {
    return;
  }
  // get the count value of results row above and below pass rate
<<<<<<< HEAD
  $quiz = db_query("SELECT SUM(score > $pass_rate) AS no_pass, SUM(score < $pass_rate) AS no_fail, SUM(score = 0) AS no_incomplete FROM {quiz_node_results} WHERE vid = :vid", array(':vid' => $vid))
    ->fetchAssoc();
=======
  $sql = "SELECT SUM(score > $pass_rate) AS no_pass, SUM(score < $pass_rate) AS no_fail, SUM(score = 0) AS no_incomplete FROM {quiz_node_results} WHERE vid = %d";
  $quiz = db_fetch_array(db_query($sql, intval($vid)));
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  if (($quiz['no_pass'] + $quiz['no_fail'] + $quiz['no_incomplete']) < 3) {
    return FALSE; // no sufficient data
  }
  // generates quiz status chart 3D pie chart
  $chart = '<div id="get_quiz_status_chart" class="quiz-stats-chart-space">';
<<<<<<< HEAD
  $chart .= theme('get_quiz_status_chart', array('quiz' => $quiz));
=======
  $chart .= theme('get_quiz_status_chart', $quiz);
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  $chart .= '</div>';
  return array(
    'chart' => $chart,
    'title' => t('Status'),
    'explanation' => t('This chart shows the status for all attempts made to answer this revision of the quiz.'),
  );
}

/**
 * Generates chart showing how often the quiz has been taken the last days
 *
 * @param $uid
 *   user id
 * @param $vid
 *   revision id of quiz
 * @return
 *   HTML to display chart
 */
function _get_date_vs_takeup_count_chart($vid, $uid = 0) {
  $start = 0;
  $end = 30;
  $takeup = array();
  $sql = "SELECT COUNT(result_id) AS count,
            DATE_FORMAT(FROM_UNIXTIME(time_start), '%Y.%m.%e') AS date
            FROM {quiz_node_results}
<<<<<<< HEAD
            WHERE vid = :vid";
  $sql_args[':vid'] = $vid;
  if ($uid != 0) {
    $sql .= " AND uid = :uid";
    $sql_args[':uid'] = $uid;
  }
  $sql .= " GROUP BY date ORDER BY time_start DESC";
  $days = _quiz_get_last_days($end);
  $results = db_query($sql, $sql_args);
  $res_o = $results->fetch();
  if (!is_object($res_o)) {
    return FALSE;
  }
  foreach ($days as $date => &$value) {
    if (isset($res_o->date) && $date == $res_o->date) {
      $value->value = $res_o->count;
      if ($res_o->count > 0) {
        $valid_data = TRUE;
      }
      $res_o = $results->fetch();
    }
  }
  if (!$valid_data) {
    return FALSE;
  }
  $chart = '<div id="date_vs_takeup_count" class="quiz-stats-chart-space">';
  // wrapping the chart output with div for custom theming.
  $chart .= theme('date_vs_takeup_count', array('takeup' => $days));
=======
            WHERE vid = %d";
  $sql_args = array($vid);
  if ($uid != 0) {
    $sql .= " AND uid = %d";
    $sql_args[] = $uid;
  }
  $sql .=" GROUP BY date ORDER BY time_start DESC";
  $results = db_query_range($sql, $sql_args, $start, $end);
  $days = _quiz_get_last_days($end);
  $res_o = db_fetch_object($results);
  foreach ($days as $date => &$value) {
   if ($date == $res_o->date) {
      $value->value = $res_o->count;
      if ($res_o->count > 0) $valid_data = TRUE;
      $res_o = db_fetch_object($results);
    }
  }
  if (!$valid_data) return FALSE;
  $chart = '<div id="date_vs_takeup_count" class="quiz-stats-chart-space">';
  // wrapping the chart output with div for custom theming.
  $chart .= theme('date_vs_takeup_count', $days);
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  // generate date vs takeup count line chart
  $chart .= '</div>';
  return array(
    'chart' => $chart,
    'title' => t('Activity'),
    'explanation' => t('This chart shows how many times the quiz has been taken the last !days days.', array('!days' => $end)),
  );
}

/**
 * Computes quiz attempt count
 *
 * @param $vid
<<<<<<< HEAD
 *   quiz node id
=======
 *   quiz revision id
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
 * @param $uid
 *   user id for filtering
 * @return
 *   Integer containing number of times that a quiz has
 *   been attended, can be filtered by uid.
 */
<<<<<<< HEAD
function _quiz_get_attempt_count($nid, $uid = 0) {
  $sql = 'SELECT COUNT(result_id) FROM {quiz_node_results} WHERE nid = :nid';
  $arg[':nid'] = $nid;
  if ($uid != 0) {
    $sql .= ' AND uid = :uid';
    $arg[':uid'] = $uid;
  }
  return db_query($sql, $arg)->fetchField();
=======
function _quiz_get_attempt_count($vid, $uid = 0) {
  $sql = "SELECT COUNT(result_id) FROM {quiz_node_results} WHERE nid = %d";
  if ($uid != 0) {
    $sql .= " AND uid = %d";
  }
  return db_result(db_query($sql, $vid, $uid));
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
}

// Theme functions

/**
 * Theme function for quiz grade range chart generates Chart using CHART API function
 *
 * @param $range
 *   array containg quiz results data structure
 *
 * @return
 *   HTML to render/display chart
 */
<<<<<<< HEAD
function theme_quiz_grade_range($variables) {
  $range = $variables['range'];
=======
function theme_quiz_grade_range($range) {
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  $chart_width = 600;
  $chart_height = 400;

  $chart = array(
    '#chart_id' => 'quiz_grade_range',
    '#type' => CHART_TYPE_BAR_V_GROUPED,
    '#size' => chart_size($chart_width, $chart_height),
    '#grid_lines' => chart_grid_lines(10, 10),
    '#bar_size' => chart_bar_size(20, 15),
    '#adjust_resolution' => TRUE,
<<<<<<< HEAD
    '#title' => '',
=======
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  );
  // chart data
  $chart['#data'][][] = $range->eighty_to_hundred;
  $chart['#data'][][] = $range->sixty_to_eighty;
  $chart['#data'][][] = $range->fourty_to_sixty;
  $chart['#data'][][] = $range->twenty_to_fourty;
  $chart['#data'][][] = $range->zero_to_twenty;

  $max = max($range->zero_to_twenty,
             $range->twenty_to_fourty,
             $range->fourty_to_sixty,
             $range->sixty_to_eighty,
             $range->eighty_to_hundred);
  // chart color
  $chart['#data_colors'][] = '00ff00';
  $chart['#data_colors'][] = '66ff00';
  $chart['#data_colors'][] = 'ffff00';
  $chart['#data_colors'][] = 'ff6600';
  $chart['#data_colors'][] = 'ff0000';
  // chart x-axis label
  $chart['#mixed_axis_labels'][CHART_AXIS_X_BOTTOM][2][] = chart_mixed_axis_label(t('Quiz Grade Range'), 50);
  // chart y-axis label and data
  $chart['#mixed_axis_labels'][CHART_AXIS_Y_LEFT][1][] = chart_mixed_axis_label(t('Attempts'), 100);
  $chart['#mixed_axis_labels'][CHART_AXIS_Y_LEFT][0][] = chart_mixed_axis_range_label(0, $max);
  // chart description on the right side
  $chart['#legends'][] = t('80 to 100 % - ') . $range->eighty_to_hundred;
  $chart['#legends'][] = t('60 to 80 % - ') . $range->sixty_to_eighty;
  $chart['#legends'][] = t('40 to 60 % - ') . $range->fourty_to_sixty;
  $chart['#legends'][] = t('20 to 40 % - ') . $range->twenty_to_fourty;
  $chart['#legends'][] = t('0 to 20 % - ') . $range->zero_to_twenty;
<<<<<<< HEAD
  return theme('chart', array('chart' => $chart));
=======

  return chart_render($chart);
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
}

/**
 * generates a chart of quiz top scorers
 *
 * @param $attendees
 *   array containing quiz data structure
 *
 * @return
 *   HTML to render quiz top scorers chart
 */
<<<<<<< HEAD
function theme_quiz_top_scorers($variables) {
  $attendees = $variables['scorer'];
  $chart = array(
    '#chart_id' => 'quiz_top_scorers',
    '#title' => t('Quiz Top Scorers'),
=======
function theme_quiz_top_scorers($attendees) {
  $chart = array(
    '#chart_id' => 'quiz_top_scorers',
    //'#title' => t('Quiz Top Scorers'),
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
    '#type' => CHART_TYPE_BAR_V_GROUPED,
    '#size' => chart_size(600, 400),
    '#grid_lines' => chart_grid_lines(10, 10),
    '#bar_size' => chart_bar_size(20, 15),
  );
  foreach ($attendees as $attendee) {
    $chart['#data'][][] = $attendee['score'];
    $chart['#data_colors'][] = chart_unique_color(count($chart['#data']));
    $chart['#mixed_axis_labels'][CHART_AXIS_X_BOTTOM][2][] = chart_mixed_axis_label(t('Quiz Attendees'), 50);
<<<<<<< HEAD
    $chart['#legends'][] = $attendee['name'] . ' ' . $attendee['score'] . ' %';
  }
  $chart['#mixed_axis_labels'][CHART_AXIS_Y_LEFT][1][] = chart_mixed_axis_label(t('Score') . ' (%)', 100);
  $chart['#mixed_axis_labels'][CHART_AXIS_Y_LEFT][0][] = chart_mixed_axis_range_label(0, 100);
  return theme('chart', array('chart' => $chart));
=======
    $chart['#legends'][] = $attendee['name'] . ' ' . $attendee['score'] .' %';
  }
  $chart['#mixed_axis_labels'][CHART_AXIS_Y_LEFT][1][] = chart_mixed_axis_label(t('Score') .' (%)', 100);
  $chart['#mixed_axis_labels'][CHART_AXIS_Y_LEFT][0][] = chart_mixed_axis_range_label(0, 100);
  return chart_render($chart);
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
}

/**
 * generates quiz status chart number of pass, fail, incomplete
 *
 * @param $quiz
 *   array of quiz data structure
 *
 * @return
 *   HTML to render quiz status chart
 */
<<<<<<< HEAD
function theme_get_quiz_status_chart($variables) {
  $quiz = $variables['quiz'];
  $quiz['total'] = $quiz['no_pass'] + $quiz['no_fail'] + $quiz['no_incomplete'];
  $chart = array(
    '#chart_id' => 'quiz_status_chart',
    '#title' => t('Quiz Status Chart'),
=======
function theme_get_quiz_status_chart($quiz) {
  $quiz['total'] = $quiz['no_pass'] + $quiz['no_fail'] + $quiz['no_incomplete'];
  $chart = array(
    '#chart_id' => 'quiz_status_chart',
    //'#title' => t('Quiz Status Chart'),
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
    '#type' => CHART_TYPE_PIE_3D,
    '#size' => chart_size(500, 200),
    '#adjust_resolution' => TRUE,
  );
  $chart['#data'] = array(
    'pass' => $quiz['no_pass'],
    'incomplete' => $quiz['no_incomplete'],
    'fail' => $quiz['no_fail'],
  );
  $chart['#data_colors'] = array(
    'pass' => '00ff00',
    'incomplete' => 'ffff00',
    'fail' => 'ff0000',
  );
  ini_set('precision', '3');
  $chart['#legends'] = array(
    'pass' => ($quiz['no_pass'] / $quiz['total']) * 100 . t('% pass'),
    'incomplete' => ($quiz['no_incomplete'] / $quiz['total']) * 100 . t('% incomplete'),
    'fail' => ($quiz['no_fail'] / $quiz['total']) * 100 . t('% fail'),
  );
<<<<<<< HEAD
  return theme('chart', array('chart' => $chart));
=======
  return chart_render($chart);
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
}

/**
 * Generates date vs takeup count chart
 *
 * @param $takeup
 *   Array quiz data structure
 * @return
 *   HTML to render/display chart
 */
<<<<<<< HEAD
function theme_date_vs_takeup_count($variables) {
  $days = $variables['takeup'];
=======
function theme_date_vs_takeup_count($days) {
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  $days = array_reverse($days);
  $max_count = 0;
  $chart = array(
    '#chart_id' => 'test_chart',
<<<<<<< HEAD
    '#title' => '',
=======
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
    '#type' => CHART_TYPE_LINE,
    '#size' => chart_size(600, 400),
    //'#chart_fill' => chart_fill('c', 'eeeeee'),
    '#grid_lines' => chart_grid_lines(10, 10, 1, 5),
    '#adjust_resolution' => TRUE,
  );
  $interval = 1;
  foreach ($days as $date => $obj) {
    $count = $obj->value;
<<<<<<< HEAD
    $chart['#data'][] = $count;
=======
    $chart['#data'][]= $count;
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
    $x_label = '|';
    if ($interval == 7) {
      $x_label = chart_mixed_axis_label(format_date($obj->timestamp, 'custom', 'd M'));
      $interval = 0;
    }
    $chart['#mixed_axis_labels'][CHART_AXIS_X_BOTTOM][0][] = $x_label;
    $interval++;
    $max_count = ($max_count > $count) ? $max_count : $count;
  }
  $chart['#mixed_axis_labels'][CHART_AXIS_Y_LEFT][0][] = chart_mixed_axis_range_label(0, $max_count);

  // The chart module has some bugs. If we have the time we should provide patches instead of tricking it to work...
  $chart['#mixed_axis_labels'][CHART_AXIS_Y_LEFT][1] = array('|', chart_mixed_axis_label(t('Attempts')));
  $chart['#mixed_axis_labels'][CHART_AXIS_X_BOTTOM][1] = array('|', chart_mixed_axis_label(t('Date')), '|');
<<<<<<< HEAD
  return theme('chart', array('chart' => $chart));
=======

  return chart_render($chart);
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
}

/**
 * Generates table of results from quiz data structure.
 *
 * @param @results
 *   Array containing quiz results data structure
 * @return
 *   HTML table containing quiz title, author, questions count, attempt count
 */
<<<<<<< HEAD
function theme_quiz_stats_get_basic_stats($variables) {
  $results = $variables['results'];
=======
function theme_quiz_stats_get_basic_stats($results = array()) {
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  if (empty($results)) {
    return '<p>' . t('No questions were found.') . '</p>';
  }
  $header = $rows = array();
  $header = array(
    'title' => t('Quiz'),
    'creator' => t('Author'),
    'created' => t('Created'),
  );
  if (user_access('access author stats')) {
    $header['questions_count'] = t('Questions');
    $header['attempt_count'] = t('Attempts');
  }
  $p = drupal_get_path('module', 'quiz_stats');
<<<<<<< HEAD
  $chart_icon = theme('image', array('path' => "$p/chart.png", 'width' => t('Charts'), 'height' => t('See charts')));
  foreach ($results as $result) {
    $title_link = user_access('access author stats') ? 'node/' . $result['nid'] . '/statistics' : 'user/' . arg(1) . '/stats/';
    $row = array(
      'title' => l($chart_icon . ' ' . $result['title'], $title_link, array('html' => TRUE)),
      'creator' => l($result['name'], 'user/' . $result['uid']),
      'created' => format_date($result['created'], 'short'),
=======
  $chart_icon = theme('image', "$p/chart.png", t('Charts'), t('See charts'));
  foreach ($results as $result) {
    $title_link = user_access('access author stats') ? 'node/' . $result['nid'] . '/statistics' : 'user/'. arg(1) .'/stats/';
    $row = array(
      'title' => l($chart_icon .' '. $result['title'], $title_link, array('html' => TRUE)),
      'creator' => l($result['name'], 'user/'. $result['uid']),
      'created' => format_date($result['created'], 'small'),
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
    );
    if (user_access('access author stats')) {
      $row['questions_count'] = quiz_get_number_of_questions($result['vid']);
      $row['attempt_count'] = _quiz_get_attempt_count($result['nid']);
    }
    $rows[] = $row;
  }
  module_load_include('inc', 'quiz', 'quiz.pages');
<<<<<<< HEAD
  $cc = '<em>' . t('Chart icon from !url', array('!url' => 'pinvoke.com')) . '</em>';
  return theme('table', array('header' => $header, 'rows' => $rows, 'attributes' => array('id' => 'tablesorter'))) . $cc;
=======
  $cc = '<em>'. t('Chart icon from !url', array('!url' => 'pinvoke.com')) .'</em>';
  return theme('table', $header, $rows, array('id' => 'tablesorter')) . $cc;
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
}

/**
 * Get the timestamps for the last days
 *
 * @param $num_days
 *  How many of the last days we need timestamps for
 * @return
 *  Array of objects with timestamp and value. The value has '0' as its default value.
 */
function _quiz_get_last_days($num_days) {
  $to_return = array();
<<<<<<< HEAD
  $now = REQUEST_TIME;
=======
  $now = time();
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  $one_day = 86400;
  for ($i = 0; $i < $num_days; $i++) {
    $timestamp = $now - ($one_day * $i);
    $to_add = new stdClass();
    $to_add->timestamp = $timestamp;
    $to_add->value = '0';
    $to_return[date('Y.m.j', $timestamp)] = $to_add;
  }
  return $to_return;
}
