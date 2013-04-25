<?php

/**
 * @file
 * Theme functions for short_answer.
 */

/**
 * Theme the list of unscored short answer questions.
 *
 * @param $unscored
 *  An array of objects with information about the unscored questions
 */
<<<<<<< HEAD
function theme_short_answer_view_unscored($variables) {
  $unscored = $variables['unscored'];
=======
function theme_short_answer_view_unscored($unscored) {
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  $output = '';
  $header = array(
    t('Question'),
    t('Time Finished'),
    t('Action')
  );
  $rows = array();

  foreach ($unscored as $item) {
    if ($item->time_end > 0) {
      $rows[] = array(
        $item->title,
        date('Y-m-d H:i', $item->time_end),
        l(t('score this response'), 'admin/quiz/reports/score-short-answer/' . $item->question_vid . '/' . $item->result_id),
      );
    }
  }

  if (!empty($rows)) {
<<<<<<< HEAD
    $output .= theme('table', array('header' => $header, 'rows' => $rows));
=======
    $output .= theme('table', $header, $rows);
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  }
  else {
    $output .= t('There are no unscored essays.');
  }
  return $output;
}

<<<<<<< HEAD
/**
 * @todo Please document this function.
 * @see http://drupal.org/node/1354
 */
function theme_short_answer_user_answer($variables) {
  $answer = $variables['answer'];
  $correct = $variables['correct'];
  $header = array(t('Correct Answer'), t('User Answer'));
  $row = array(array($correct, $answer));
  return theme('table', array('header' => $header, 'rows' => $row));
=======
function theme_short_answer_user_answer($answer, $correct) {
  $header = array(t('Correct Answer'), t('User Answer'));
  $row = array(array($correct, $answer));
  return theme('table', $header, $row);
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
}

/**
 * Theme the short_answer response form
 *
 * @param $form
 *  The response form
 */
<<<<<<< HEAD
function theme_short_answer_response_form($variables) {
  $form = $variables['form'];
  return drupal_render_children($form);
}
=======
function theme_short_answer_response_form($form) {
  return drupal_render($form);
}
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
