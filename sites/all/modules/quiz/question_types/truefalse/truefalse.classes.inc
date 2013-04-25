<?php


/**
 * Defines the classes necessary for a True/False quiz.
 *
 * @file
 */

/**
 * Extension of QuizQuestion.
 */
class TrueFalseQuestion extends QuizQuestion {

  /**
   * Implementation of saveNodeProperties
   *
   * @see QuizQuestion#saveNodeProperties($is_new)
   */
  public function saveNodeProperties($is_new = FALSE) {
    if (!isset($this->node->feedback)) {
      $this->node->feedback = '';
    }
<<<<<<< HEAD
    if ($is_new || $this->node->revision == 1) {
      $id = db_insert('quiz_truefalse_node')
        ->fields(array(
          'nid' => $this->node->nid,
          'vid' => $this->node->vid,
          'correct_answer' => (int) $this->node->correct_answer,
          'feedback' => $this->node->feedback,
        ))
        ->execute();
    }
    else {
      db_update('quiz_truefalse_node')
        ->fields(array(
          'correct_answer' => (int) $this->node->correct_answer,
          'feedback' => $this->node->feedback,
        ))
        ->condition('nid', $this->node->nid)
        ->condition('vid', $this->node->vid)
        ->execute();
=======

    if ($is_new || $this->node->revision == 1) {
      $sql = "INSERT INTO {quiz_truefalse_node} (nid, vid, correct_answer, feedback) VALUES (%d, %d, %d, '%s')";
      db_query($sql, $this->node->nid, $this->node->vid, (int)$this->node->correct_answer, $this->node->feedback);
    }
    else {
      $sql = "UPDATE {quiz_truefalse_node} SET correct_answer = %d, feedback = '%s' WHERE nid = %d AND vid = %d";
      db_query($sql, (int)$this->node->correct_answer, $this->node->feedback, $this->node->nid, $this->node->vid);
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
    }
  }

  /**
   * Implementation of validateNode
   *
   * @see QuizQuestion#validateNode($form)
   */
  public function validateNode(array &$form) {
    // This space intentionally left blank. :)
  }

  /**
   * Implementation of delete
   *
   * @see QuizQuestion#delete($only_this_version)
   */
  public function delete($only_this_version = FALSE) {
    parent::delete($only_this_version);

<<<<<<< HEAD
    $delete_ans = db_delete('quiz_truefalse_user_answers');
    $delete_ans->condition('question_nid', $this->node->nid);

    $delete_node = db_delete('quiz_truefalse_node');
    $delete_node->condition('nid', $this->node->nid);

    if ($only_this_version) {
      $delete_ans->condition('question_vid', $this->node->vid);
      $delete_node->condition('vid', $this->node->vid);
    }

    $delete_ans->execute();
    $delete_node->execute();
=======
    // Only delete a nid/vid.
    if ($only_this_version) {
      $sql = 'DELETE FROM {quiz_truefalse_user_answers} WHERE question_nid = %d AND question_vid = %d';
      db_query($sql, $this->node->nid, $this->node->vid);
      $sql = 'DELETE FROM {quiz_truefalse_node} WHERE nid = %d AND vid = %d';
      db_query($sql, $this->node->nid, $this->node->vid);
    }
    // Delete all versions of the quiz question.
    else {
      $sql = 'DELETE FROM {quiz_truefalse_user_answers} WHERE question_nid = %d';
      db_query($sql, $this->node->nid);
      $sql = 'DELETE FROM {quiz_truefalse_node} WHERE nid = %d';
      db_query($sql, $this->node->nid, $this->node->vid);
    }
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  }

  /**
   * Implementation of getNodeProperties
   *
   * @see QuizQuestion#getNodeProperties()
   */
  public function getNodeProperties() {
<<<<<<< HEAD
    if (isset($this->nodeProperties)) {
      return $this->nodeProperties;
    }
    $props = parent::getNodeProperties();

    $res_a = db_query('SELECT correct_answer, feedback FROM {quiz_truefalse_node} WHERE nid = :nid AND vid = :vid', array(':nid' => $this->node->nid, ':vid' => $this->node->vid))->fetchAssoc();

    if (is_array($res_a)) {
      $props = array_merge($props, $res_a);
    }
=======
    if (isset($this->nodeProperties)) return $this->nodeProperties;
    $props = parent::getNodeProperties();

    $sql = 'SELECT correct_answer, feedback FROM {quiz_truefalse_node} WHERE nid = %d AND vid = %d';
    $res = db_query($sql, $this->node->nid, $this->node->vid);
    $res_a = db_fetch_array($res);
    if (is_array($res_a))
      $props = array_merge($props, $res_a);
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
    $this->nodeProperties = $props;
    return $props;
  }

  /**
   * Implementation of getNodeView
   *
   * @see QuizQuestion#getNodeView()
   */
  public function getNodeView() {
    $content = parent::getNodeView();
<<<<<<< HEAD
    if ($this->viewCanRevealCorrect()) {
      $answer = ($this->node->correct_answer) ? t('True') : t('False');
      $content['answers']['#markup'] = '<div class="quiz-solution">' . $answer . '</div>';
      $content['answers']['#weight'] = 2;
    }
    else {
      $content['answers'] = array(
      '#markup' => '<div class="quiz-answer-hidden">' . t('Answer hidden') . '</div>',
=======

    if ($this->viewCanRevealCorrect()) {
      $answer = ($this->node->correct_answer) ? t('True') : t('False');
      $content['answers']['#type'] = 'markup';
      $content['answers']['#value'] = '<div class="quiz-solution">'. $answer .'</div>';
    }
    else {
      $content['answers'] = array(
      '#type' => 'markup',
      '#value' => '<div class="quiz-answer-hidden">Answer hidden</div>',
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
      '#weight' => 2,
      );
    }
    return $content;
  }

  /**
   * Implementation of getAnsweringForm
   *
   * @see QuizQuestion#getAnsweringForm($form_state, $rid)
   */
  public function getAnsweringForm(array $form_state = NULL, $rid) {
    $form = parent::getAnsweringForm($form_state, $rid);
<<<<<<< HEAD
    //$form['#theme'] = 'truefalse_answering_form';
=======
    $form['#theme'] = 'truefalse_answering_form';
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2

    // 'tries' is unfortunately required by quiz.module
    $form['tries'] = array(
      '#type' => 'radios',
      '#title' => t('Choose one'),
      '#options' => array(
        1 => t('True'),
        0 => t('False')
      ),
<<<<<<< HEAD
      '#default_value' => NULL, // prevent default value set to NULL
=======
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
    );

    if (isset($rid)) {
      $response = new TrueFalseResponse($rid, $this->node);
<<<<<<< HEAD
      $default = $response->getResponse();
      $form['tries']['#default_value'] = is_null($default) ? NULL : $default;
=======
      $form['tries']['#default_value'] = $response->getResponse();
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
    }
    return $form;
  }

  /**
   * Implementation of getBodyFieldTitle
   *
   * @see QuizQuestion#getBodyFieldTitle()
   */
  public function getBodyFieldTitle() {
    return t('True/false statement');
  }

  /**
   * Implementation of getCreationForm
   *
   * @see QuizQuestion#getCreationForm($form_state)
   */
<<<<<<< HEAD
  public function getCreationForm(array &$form_state = NULL) {
=======
  public function getCreationForm(array $form_state = NULL) {
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
    $form['correct_answer'] = array(
      '#type' => 'radios',
      '#title' => t('Correct answer'),
      '#options' => array(
        1 => t('True'),
        0 => t('False'),
      ),
      '#default_value' => isset($this->node->correct_answer) ? $this->node->correct_answer : 1,
      '#required' => TRUE,
      '#weight' => -4,
      '#description' => t('Choose if the correct answer for this question is "true" or "false".')
    );
    $form['feedback_fields'] = array(
      '#type' => 'fieldset',
      '#title' => t('Feedback Settings'),
      '#description' => t('Settings pertaining to feedback given along with results.'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
      '#weight' => -3,
    );
<<<<<<< HEAD
    $form['feedback_fields']['feedback'] = array(// @todo: Does this make sense?
=======
    $form['feedback_fields']['feedback'] = array( // @todo: Does this make sense?
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
      '#type' => 'textarea',
      '#title' => t('Feedback Text'),
      '#description' => t('Text to be displayed when the results are displayed'),
      '#rows' => 5,
      '#cols' => 60,
      '#required' => FALSE,
      '#default_value' => isset($this->node->feedback) ? $this->node->feedback : '',
    );
    return $form;
  }

  /**
   * Implementation of getMaximumScore
   *
   * @see QuizQuestion#getMaximumScore()
   */
  public function getMaximumScore() {
    return 1;
  }


  /**
   * Get the answer to this question.
   *
   * This is a utility function. It is not defined in the interface.
   */
  public function getCorrectAnswer() {
<<<<<<< HEAD
    return db_query('SELECT correct_answer FROM {quiz_truefalse_node} WHERE nid = :nid AND vid = :vid', array(':nid' => $this->node->nid, ':vid' => $this->node->vid))->fetchField();
=======
    $sql = "SELECT correct_answer FROM {quiz_truefalse_node} WHERE nid = %d AND vid = %d";
    return db_result(db_query($sql, $this->node->nid, $this->node->vid));
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  }
}

/**
 * Extension of QuizQuestionResponse
 */
class TrueFalseResponse extends QuizQuestionResponse {

  /**
   * Constructor
   */
  public function __construct($result_id, stdClass $question_node, $answer = NULL) {
    parent::__construct($result_id, $question_node, $answer);
    if (!isset($answer)) {
      $r = $this->getCorrectAnswer();
      if (!empty($r)) {
        $this->answer = $r->answer;
        $this->score = $r->score;
      }
    }
    else {
      $this->answer = $answer;
    }
  }

  /**
   * Implementation of isValid
   *
   * @see QuizQuestionResponse#isValid()
   */
  public function isValid() {
<<<<<<< HEAD
    return ($this->answer === NULL) ? t('You must provide an answer') : TRUE;
=======
    if ($this->answer['answer'] === NULL) return t('You must provide an answer');
    else return TRUE;
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  }

  /**
   * Implementation of save
   *
   * @see QuizQuestionResponse#save()
   */
  public function save() {
<<<<<<< HEAD
    db_insert('quiz_truefalse_user_answers')
      ->fields(array(
        'question_nid' => $this->question->nid,
        'question_vid' => $this->question->vid,
        'result_id' => $this->rid,
        'answer' => (int) $this->answer,
        'score' => (int) $this->getScore(),
      ))
      ->execute();
=======
    $sql = "INSERT INTO {quiz_truefalse_user_answers} (question_nid, question_vid, result_id, answer, score) VALUES (%d, %d, %d, %d, %d)";
    db_query($sql, $this->question->nid, $this->question->vid, $this->rid, (int)$this->answer, (int)$this->getScore());
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  }

  /**
   * Implementation of delete
   *
   * @see QuizQuestionResponse#delete()
   */
  public function delete() {
<<<<<<< HEAD
    db_delete('quiz_truefalse_user_answers')
      ->condition('question_nid', $this->question->nid)
      ->condition('question_vid', $this->question->vid)
      ->condition('result_id', $this->rid)
      ->execute();
=======
    $sql = "DELETE FROM {quiz_truefalse_user_answers} WHERE question_nid = %d AND question_vid = %d AND result_id = %d";
    db_query($sql, $this->question->nid, $this->question->vid, $this->rid);
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  }

  /**
   * Implementation of score
   *
   * @see QuizQuestionResponse#score()
   */
  public function score() {
    $tfQuestion = new TrueFalseQuestion($this->question);
    return ($this->getResponse() == $tfQuestion->getCorrectAnswer()) ? 1 : 0;
  }

  /**
   * Implementation of getResponse
   *
   * @see QuizQuestionResponse#getResponse()
   */
  public function getResponse() {
    if (!isset($this->answer)) {
      $correct_answer = $this->getCorrectAnswer();
<<<<<<< HEAD
      $this->answer = isset($correct_answer->answer) ? $correct_answer->answer : NULL;
=======
      $this->answer = $correct_answer->answer;
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
    }
    return $this->answer;
  }

  /**
   * Implementation of getCorrectAnswer
   */
  public function getCorrectAnswer() {
<<<<<<< HEAD
    return db_query('SELECT answer, score FROM {quiz_truefalse_user_answers} WHERE question_vid = :qvid AND result_id = :rid', array(':qvid' => $this->question->vid, ':rid' => $this->rid))->fetch();
=======
    $sql = "SELECT answer, score FROM {quiz_truefalse_user_answers} WHERE question_vid = %d AND result_id = %d";
    return db_fetch_object(db_query($sql, $this->question->vid, $this->rid));
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  }

  /**
   * Implementation of getReportFormResponse
   *
   * @see QuizQuestionResponse#getReportFormResponse($showpoints, $showfeedback, $allow_scoring)
   */
  public function getReportFormResponse($showpoints = TRUE, $showfeedback = TRUE, $allow_scoring = FALSE) {
    if (empty($this->question->answers)) {
      return array(
<<<<<<< HEAD
        '#markup' => t('Missing question.'),
=======
        '#type' => 'markup',
        '#value' => t('Missing question.'),
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
      );
    }
    $metadata = array();
    $data = array();
    // Build the question answers header (add blank space for IE).
    if ($showpoints) {
      $metadata[] = t('Correct Answer');
    }
    $metadata[] = t('User answer');
<<<<<<< HEAD
    if ($showfeedback && !empty($this->question->feedback)) {
      $metadata[] = 'Feedback';
=======
    if ($showfeedback) {
      $metadata[] = '&nbsp;';
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
    }

    $answer = $this->question->answers[0];
    $correct_answer = $answer['is_correct'] ? $answer['answer'] : !$answer['answer'];
    $user_answer = $answer['answer'];

    if ($showpoints) {
      $data[0]['correct_answer'] = ($correct_answer ? t('True') : t('False'));
    }
<<<<<<< HEAD
    $data[0]['user_answer'] = (($user_answer === NULL) ? '' : ($user_answer ? t('True') : t('False')));

    if ($showfeedback && !empty($this->question->feedback)) {
      $data[0]['feedback'] = check_markup($this->question->feedback, $this->getFormat());
=======
    $data[0]['user_answer'] = (($user_answer === NULL) ? '': ($user_answer ? t('True') : t('False')));

    if ($showfeedback && !empty($this->question->feedback)) {
      $data[0]['feedback'] = check_markup($this->question->feedback, $this->question->format, FALSE);
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
    }

    // Return themed output
    return array(
<<<<<<< HEAD
      '#markup' => theme('truefalse_response', array('metadata' => $metadata, 'data' => $data)),
=======
      '#type' => 'markup',
      '#value' => theme('truefalse_response', $metadata, $data),
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
    );
  }
}
