<?php
/**
 * short_answer.admin
 *
 * @file
 */

/**
 * Generate a view of all unscored short_answer questions.
 *
 * @see theme_short_answer_view_unscored()
 */
function short_answer_view_unscored() {
  $unscored = ShortAnswerResponse::fetchAllUnscoredAnswers();
  return theme('short_answer_view_unscored', $unscored);
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
function short_answer_edit_score($vid, $rid) {
  $nid = db_result(db_query("SELECT nid FROM {node_revisions} WHERE vid = %d", $vid));
  if (!$nid) {
    drupal_not_found();
    return;
  }

  $node = node_load($nid, $vid);
  if (!$node || $node->type != 'short_answer') {
    drupal_not_found();
    return;
  }

  $answer = short_answer_get_answer($node->nid, $node->vid, $rid);

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

  drupal_set_title(t('Score answer to "@title"', array('@title' => $node->title)));

  return drupal_get_form('short_answer_score_form', $node, $answer);
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
function short_answer_score_form($context, $node, $answer) {

  if (!$node || $node->type != 'short_answer' || !$answer) {
    drupal_not_found();
    return;
  }

  // Set up the form
  $form['question'] = array(
    '#type' => 'item',
    '#title' => t('Question'),
    '#value' => check_markup($node->body, $node->format, FALSE),
  );
  $form['answer'] = array(
    '#type' => 'item',
    '#title' => t('Answer'),
    '#value' => check_markup($answer->answer, variable_get('short_answer_markup_filter', FILTER_FORMAT_DEFAULT), FALSE),
  );
  $form['show_max_score'] = array(
    '#type' => 'item',
    '#title' => t('Maximum Score'),
    '#value' => (int) $answer->rel_max_score,
  );
  $form['score'] = array(
    '#type' => 'textfield',
    '#title' => t('Score'),
    '#description' => t('The score for this essay, between 0 and @max', array('@max' => $answer->rel_max_score)) ,
    '#size' => 3,
    '#maxlength' => 3,
    '#default_value' => (int) $answer->rel_score,
    '#required' => TRUE,
  );
  $form['submit'] = array(
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
 * Validate the score form
 */
function short_answer_score_form_validate($form, $form_state) {
  // Check to make sure that entered score is not higher than max allowed score.
  $max = (int)$form_state['values']['rel_max_score'];
  $given = (int)$form_state['values']['score'];

  if (!_quiz_is_int($form_state['values']['score'], 0, $max)) {
    $args = array('@score' => $given, '@max' => $max);
    form_error($form['score'], t('The score needs to be a number between 0 and @max', $args));
  }
}

/**
 * Submit the score form
 */
function short_answer_score_form_submit($form, &$form_state) {

  $sql = 'SELECT nid, vid FROM {quiz_node_results} WHERE result_id = %d';
  $result = db_fetch_object(db_query($sql, $form_state['values']['result_id']));

  $result = short_answer_score_an_answer(array(
    'quiz' => node_load($result->nid, $result->vid),
    'nid' => $form_state['values']['question_nid'],
    'vid' => $form_state['values']['question_vid'],
    'rid' => $form_state['values']['result_id'],
    'score' => $form_state['values']['score'],
    'max_score' => $form_state['values']['rel_max_score'],
  ));

  if ($result == 1) {
    drupal_set_message("The score has been saved.");
    $form_state['redirect'] = 'admin/quiz/reports/score-short-answer';
  }
  else {
    drupal_set_message(t('Error saving the score. The selected answer was not scored.'), 'error');
  }
}