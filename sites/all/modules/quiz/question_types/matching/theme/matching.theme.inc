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
function theme_matching_question_form($form) {
  $rows = array();
  $header = array(
    'question' => t('Question'),
    'answer' => t('Correct answer'),
    'feedback' => t('Feedback'),
  );

  foreach (element_children($form) as $key) {
    $rows[] = array(
      'question' => drupal_render($form[$key]['question']),
      'answer' => drupal_render($form[$key]['answer']),
      'feedback' => drupal_render($form[$key]['feedback']),
    );
  }
  // Theme output and display to screen.
  return theme('table', $header, $rows);
}

/**
 * Theme the answering form
 */
function theme_matching_subquestion_form($form) {
  $out = '<table class = "matching-tbl">';
  foreach ($form as $key => $value) {
    if (is_int($key)) {
      $out .= '<tr><td class = "matching-question">'. $value['#question'];
      $out .= '</td><td class = "matching-select">'. drupal_render($value) .'</td></tr>';
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
function theme_matching_match_node_view($subquestions) {
  // We know the correct answer for each subquestion
  if (isset($subquestions[0]['correct'])) {
    $header = array(t('Subquestion'), t('Correct match'), t('Feedback'));
  }
  // We do not know the correct answer for each subquestion
  else {
    $header = array(t('Subquestion'), t('Possible match'));
  }
  return theme('table', $header, $subquestions);
}

/**
 * Theme the contents of the matching response form
 *
 * @param $metadata
 *  Can be used as a table header
 * @param $data
 *  Can be used as table rows
 */
function theme_matching_response($metadata, $data) {
  if (isset($data[0]['is_correct'])) {
    foreach ($data as $id => $match_data) {
      $theme = $match_data['is_correct'] ? 'quiz_score_correct' : 'quiz_score_incorrect';
      $data[$id]['is_correct'] = array(
        'data' => theme($theme),
        'class' => 'quiz_summary_qcell',
      );
    }
  }
  return theme('table', $metadata, $data);
}