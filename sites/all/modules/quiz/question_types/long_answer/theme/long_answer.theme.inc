<?php

/**
 * @file
 * Theme functions for long_answer.
 */


/**
 * Theme the list of unscored long answer questions.
 *
 * @param $unscored
 *  An array of objects, each with the question_nid, question_vid, and result_id of an unscored question.
 */
<<<<<<< HEAD
function theme_long_answer_view_unscored($variables) {
  $unscored = $variables['unscored'];
=======
function theme_long_answer_view_unscored($unscored) {
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  $output = '';
  $header = array(
    t('Question'),
    t('Finished'),
    t('Action')
  );
  $rows = array();

  foreach ($unscored as $item) {
    if ($item->time_end > 0) {
      $rows[] = array(
        check_plain($item->title),
        date('Y-m-d H:i', $item->time_end),
        l(t('Score this response'), 'admin/quiz/reports/score-long-answer/' . $item->question_vid . '/' . $item->result_id),
      );
    }
  }
<<<<<<< HEAD
  $output .= !empty($rows) ? theme('table', array('header' => $header, 'rows' => $rows)) : t('There are no unscored essays.');
=======
  $output .= !empty($rows) ? theme('table', $header, $rows) : t('There are no unscored essays.');
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  return $output;
}

/**
 * Theme the long_answer response form
 *
 * @param $form
 *  The response form
 */
<<<<<<< HEAD
function theme_long_answer_response_form($variables) {
  $form = $variables['form'];
  return drupal_render_children($form);
}
=======
function theme_long_answer_response_form($form) {
  return drupal_render($form);
}
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
