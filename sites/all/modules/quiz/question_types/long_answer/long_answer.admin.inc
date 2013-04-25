<?php

/**
 * Administration pages for the long answer questions module.
 * @file
 */

/**
 * Generate a view of all unscored answer questions.
 *
 * @see theme_long_answer_view_unscored()
 */
function long_answer_view_unscored() {
<<<<<<< HEAD
  $unscored = LongAnswerResponse::fetchAllUnscoredAnswers();
  return theme('long_answer_view_unscored', array('unscored' => $unscored));
=======
  $unscored = long_answer_get_all_unscored_answers();
  return theme('long_answer_view_unscored', $unscored);
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
}

/**
 * Page handler for displaying a scoring form.
 * This function is called directly from the menu router. It generates a form for
 * scoring a quiz.
 *
 * @param $vid
 *  The VID of the question and answer to load.
 * @param $rid
 *  The result ID of the answer to load.
 * @return
 *  Text to display.
 */
function long_answer_edit_score($vid, $rid) {

  // We have to do the vid -> nid lookup ourselves because node_load uses only node.vid,
<<<<<<< HEAD
  // and we need to be able to access old nodes in node_revision.vid.
  $nid = db_query('SELECT nid FROM {node_revision} WHERE vid = :vid', array(':vid' => $vid))->fetchField();
=======
  // and we need to be able to access old nodes in node_revisions.vid.
  $nid = db_result(db_query("SELECT nid FROM {node_revisions} WHERE vid = %d", $vid));
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  if (!$nid) {
    drupal_not_found();
    return;
  }

  $node = node_load($nid, $vid);
  if (!$node || $node->type != 'long_answer') {
    drupal_not_found();
    return;
  }

<<<<<<< HEAD
  $answer = (object) long_answer_get_answer($node->nid, $node->vid, $rid);
=======
  $answer = long_answer_get_answer($node->nid, $node->vid, $rid);

>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  if (!$answer) {
    drupal_not_found();
    return;
  }
  if ($node->max_score > 0) {
    $answer->rel_score = round($answer->score * $answer->rel_max_score / $node->max_score);
  }
  else {
    $answer->rel_score = 0;
  }
<<<<<<< HEAD
  drupal_set_title(t('Score answer to "@title"', array('@title' => $node->title)), PASS_THROUGH);
=======
  drupal_set_title(t('Score answer to "@title"', array('@title' => $node->title)));
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  return drupal_get_form('long_answer_score_form', $node, $answer);
}

/**
 * Build a form for scoring long-answer questions.
 *
 * @param $node
 *  The question node.
 * @param $answer
 *  An object containing an answer to the question. This form is for scoring that answer.
 * @return
 *  The form (as a FAPI array).
 */
<<<<<<< HEAD
function long_answer_score_form($form, $form_state, $node, $answer) {
=======
function long_answer_score_form($form_state, $node, $answer) {
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  if (!$node || $node->type != 'long_answer' || !$answer) {
    drupal_not_found();
    return;
  }
<<<<<<< HEAD
=======

>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  // Set up the form
  $form['question'] = array(
    '#type' => 'item',
    '#title' => t('Question'),
<<<<<<< HEAD
    '#markup' => check_markup($node->body['und'][0]['value'], $node->body['und'][0]['format']),
=======
    '#value' => check_markup($node->body, $node->format, FALSE),
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  );
  $form['rubric'] = array(
    '#type' => 'item',
    '#title' => t('Rubric'),
<<<<<<< HEAD
    '#markup' => check_markup($node->rubric),
=======
    '#value' => check_markup($node->rubric, variable_get('long_answer_markup_filter', FILTER_FORMAT_DEFAULT), FALSE),
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  );
  $form['show_max_score'] = array(
    '#type' => 'item',
    '#title' => t('Maximum Score'),
<<<<<<< HEAD
    '#markup' => (int) $answer->rel_max_score,
=======
    '#value' => (int) $answer->rel_max_score,
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  );
  $form['score_answer'] = array(
    '#type' => 'fieldset',
    '#title' => t('Score answer'),
  );
  $form['score_answer']['answer'] = array(
    '#type' => 'item',
    '#title' => t('Answer'),
<<<<<<< HEAD
    '#markup' => check_markup($answer->answer),
=======
    '#value' => check_markup($answer->answer, variable_get('long_answer_markup_filter', FILTER_FORMAT_DEFAULT), FALSE),
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  );
  $form['score_answer']['score'] = array(
    '#type' => 'textfield',
    '#title' => t('Score'),
<<<<<<< HEAD
    '#description' => t('The score for this essay, between 0 and @max', array('@max' => $answer->rel_max_score)),
=======
    '#description' => t('The score for this essay, between 0 and @max', array('@max' => $answer->rel_max_score)) ,
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
    '#size' => 3,
    '#maxlength' => 3,
    '#default_value' => (int) $answer->rel_score,
    '#required' => TRUE,
  );
<<<<<<< HEAD
  $form['score_answer']['answer_feedback'] = array(
    '#title' => t('Feedback'),
    '#type' => 'textarea',
    '#description' => t('The text entered here would be shown to attendees'),
    '#default_value' => $answer->answer_feedback,
  );

=======
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  $form['score_answer']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save this score'),
  );

  // Save some work by keeping these.
  $form['max_score'] = array(
    '#type' => 'value',
    '#value' => $node->max_score,
  );
  $form['rel_max_score'] = array(
    '#type' => 'value',
    '#value' => $answer->rel_max_score,
  );
  $form['question_nid'] = array(
    '#type' => 'value',
    '#value' => $answer->question_nid,
  );
  $form['question_vid'] = array(
    '#type' => 'value',
    '#value' => $answer->question_vid,
  );
  $form['result_id'] = array(
    '#type' => 'value',
    '#value' => $answer->result_id,
  );

  return $form;
}

/**
 * Validates the long answer score form
 */
function long_answer_score_form_validate($form, $form_state) {
  // Check to make sure that entered score is not higher than max allowed score.
<<<<<<< HEAD
  $max = (int) $form_state['values']['rel_max_score'];
=======
  $max = (int)$form_state['values']['rel_max_score'];
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  $given = $form_state['values']['score'];
  if (!_quiz_is_int($given, 0, $max)) {
    $args = array('@score' => $given, '@max' => $max);
    form_set_error('score', t('The given score (@score) must be an integer between 0 and @max', $args));
  }
}

/**
 * Submit handler for the long answer score form
 */
function long_answer_score_form_submit($form, &$form_state) {
<<<<<<< HEAD
  $result = db_query('SELECT nid, vid FROM {quiz_node_results} WHERE result_id = :result_id', array(':result_id' => $form_state['values']['result_id']))->fetch();
=======
  $sql = 'SELECT nid, vid FROM {quiz_node_results} WHERE result_id = %d';
  $result = db_fetch_object(db_query($sql, $form_state['values']['result_id']));
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2

  $result = long_answer_score_an_answer(array(
    'quiz' => node_load($result->nid, $result->vid),
    'nid' => $form_state['values']['question_nid'],
    'vid' => $form_state['values']['question_vid'],
    'rid' => $form_state['values']['result_id'],
    'score' => $form_state['values']['score'],
    'max_score' => $form_state['values']['rel_max_score'],
<<<<<<< HEAD
    'answer_feedback' => $form_state['values']['answer_feedback']
=======
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  ));

  if ($result == 1) {
    drupal_set_message(t('The score has been saved.'));
    $form_state['redirect'] = 'admin/quiz/reports/score-long-answer';
  }
  else {
    drupal_set_message(t('Error saving the score. The selected answer was not scored.'), 'error');
  }
}
