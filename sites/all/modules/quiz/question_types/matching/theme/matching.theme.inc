<?php


/**
 * @file
 *   Themes for the matching module.
 */

/**
 * Theme the matching question form.
 *
 * @param $form
 *   drupal form array
 */
<<<<<<< HEAD
function theme_matching_question_form($variables) {
  $form = $variables['form'];
=======
function theme_matching_question_form($form) {
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  $rows = array();
  $header = array(
    'question' => t('Question'),
    'answer' => t('Correct answer'),
    'feedback' => t('Feedback'),
  );
<<<<<<< HEAD
=======

>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  foreach (element_children($form) as $key) {
    $rows[] = array(
      'question' => drupal_render($form[$key]['question']),
      'answer' => drupal_render($form[$key]['answer']),
      'feedback' => drupal_render($form[$key]['feedback']),
    );
  }
  // Theme output and display to screen.
<<<<<<< HEAD
  return theme('table', array('header' => $header, 'rows' => $rows));
=======
  return theme('table', $header, $rows);
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
}

/**
 * Theme the answering form
 */
<<<<<<< HEAD
function theme_matching_subquestion_form($variables) {
  $form = $variables['form'];
  $out = '<table class = "matching-tbl">';
  foreach ($form as $key => $value) {
    if (is_int($key)) {
      $out .= '<tr><td class = "matching-question">' . $value['#question'];
      $out .= '</td><td class = "matching-select">' . drupal_render_children($value) . '</td></tr>';
=======
function theme_matching_subquestion_form($form) {
  $out = '<table class = "matching-tbl">';
  foreach ($form as $key => $value) {
    if (is_int($key)) {
      $out .= '<tr><td class = "matching-question">'. $value['#question'];
      $out .= '</td><td class = "matching-select">'. drupal_render($value) .'</td></tr>';
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
    }
  }
  $out .= '</table>';
  return $out;
}

/**
 * Theme the answer part of the node view
 *
 * @param $subquestions
 *  Array with all the data in the subquestions.
 */
<<<<<<< HEAD
function theme_matching_match_node_view($variables) {
  $subquestions = $variables['subquestions'];
=======
function theme_matching_match_node_view($subquestions) {
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  // We know the correct answer for each subquestion
  if (isset($subquestions[0]['correct'])) {
    $header = array(t('Subquestion'), t('Correct match'), t('Feedback'));
  }
  // We do not know the correct answer for each subquestion
  else {
    $header = array(t('Subquestion'), t('Possible match'));
  }
<<<<<<< HEAD
  return theme('table', array('header' => $header, 'rows' => $subquestions));
=======
  return theme('table', $header, $subquestions);
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
}

/**
 * Theme the contents of the matching response form
 *
 * @param $metadata
 *  Can be used as a table header
 * @param $data
 *  Can be used as table rows
 */
<<<<<<< HEAD
function theme_matching_response($variables) {
  $metadata = $variables['metadata'];
  $data = $variables['data'];
  if (isset($data[0]['is_correct'])) {
    foreach ($data as $id => $match_data) {
      $theme = $match_data['is_correct'] ? 'quiz_score_correct' : 'quiz_score_incorrect';
      // TODO Please change this theme call to use an associative array for the $variables parameter.
=======
function theme_matching_response($metadata, $data) {
  if (isset($data[0]['is_correct'])) {
    foreach ($data as $id => $match_data) {
      $theme = $match_data['is_correct'] ? 'quiz_score_correct' : 'quiz_score_incorrect';
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
      $data[$id]['is_correct'] = array(
        'data' => theme($theme),
        'class' => 'quiz_summary_qcell',
      );
    }
  }
<<<<<<< HEAD
  return theme('table', array('header' => $metadata, 'rows' => $data));
}
=======
  return theme('table', $metadata, $data);
}
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
