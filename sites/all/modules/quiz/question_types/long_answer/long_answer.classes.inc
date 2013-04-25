<?php

/**
 * Long answer classes.
 *
 * @file
 *  Classes modelling the long answer question and the long answer question response
 */


/**
 * Extension of QuizQuestion.
 */
<<<<<<< HEAD
class LongAnswerQuestion extends QuizQuestion {
  /**
   * Implementation of saveNodeProperties
=======
class LongAnswerQuestion extends QuizQuestion{
  /**
   * Implementation of saveNodeProperties
   *
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
   * @see QuizQuestion#saveNodeProperties($is_new)
   */
  public function saveNodeProperties($is_new = FALSE) {
    if (!isset($this->node->feedback)) {
      $this->node->feedback = '';
    }
    if ($is_new || $this->node->revision == 1) {
<<<<<<< HEAD
      $id = db_insert('quiz_long_answer_node_properties')
        ->fields(array(
          'nid' => $this->node->nid,
          'vid' => $this->node->vid,
          'rubric' => $this->node->rubric,
        ))
        ->execute();
    }
    else {
      db_update('quiz_long_answer_node_properties')
        ->fields(array('rubric' => $this->node->rubric))
        ->condition('nid', $this->node->nid)
        ->condition('vid', $this->node->vid)
        ->execute();
=======
      $sql = "INSERT INTO {quiz_long_answer_node_properties}
        (nid, vid, rubric)
        VALUES (%d, %d, '%s')";
      db_query($sql, $this->node->nid, $this->node->vid, $this->node->rubric);
    }
    else {
      $sql = "UPDATE {quiz_long_answer_node_properties}
        SET rubric = '%s'
        WHERE nid = %d AND vid = %d";
      db_query($sql, $this->node->rubric, $this->node->nid, $this->node->vid);
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
    }
  }

  /**
   * Implementation of validateNode
   *
   * @see QuizQuestion#validateNode($form)
   */
<<<<<<< HEAD
  public function validateNode(array &$form) { }
=======
  public function validateNode(array &$form) {
  }
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2

  /**
   * Implementation of delete
   *
   * @see QuizQuestion#delete($only_this_version)
   */
  public function delete($only_this_version = FALSE) {
    if ($only_this_version) {
<<<<<<< HEAD
      db_delete('quiz_long_answer_user_answers')
        ->condition('question_nid', $this->node->nid)
        ->condition('question_vid', $this->node->vid)
        ->execute();
      db_delete('quiz_long_answer_node_properties')
        ->condition('nid', $this->node->nid)
        ->condition('vid', $this->node->vid)
        ->execute();
    }
    else {
      db_delete('quiz_long_answer_node_properties')
        ->condition('nid', $this->node->nid)
        ->execute();
      db_delete('quiz_long_answer_user_answers')
        ->condition('question_nid', $this->node->nid)
        ->execute();
=======
      db_query('DELETE FROM {quiz_long_answer_user_answers} WHERE question_nid = %d AND question_vid = %d', $this->node->nid, $this->node->vid);
      db_query('DELETE FROM {quiz_long_answer_node_properties} WHERE nid = %d AND vid = %d', $this->node->nid, $this->node->vid);
    }
    else {
      db_query('DELETE FROM {quiz_long_answer_node_properties} WHERE nid = %d', $this->node->nid);
      db_query('DELETE FROM {quiz_long_answer_user_answers} WHERE question_nid = %d', $this->node->nid);
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
    }
    parent::delete($only_this_version);
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

    $res_a = db_query('SELECT rubric FROM {quiz_long_answer_node_properties}
      WHERE nid = :nid AND vid = :vid', array(':nid' => $this->node->nid, ':vid' => $this->node->vid))->fetchAssoc();

    if (is_array($res_a)) {
      $props = array_merge($props, $res_a);
    }

=======
    if (isset($this->nodeProperties)) return $this->nodeProperties;
    $props = parent::getNodeProperties();

    $sql = 'SELECT rubric
      FROM {quiz_long_answer_node_properties}
      WHERE nid = %d AND vid = %d';
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
      $content['answers'] = array(
        '#type' => 'item',
        '#title' => t('Rubric'),
        '#markup' => '<div class="quiz-solution">' . check_markup($this->node->rubric, $this->getFormat()) . '</div>',
=======

    if ($this->viewCanRevealCorrect()) {
      $rubric_html = check_markup($this->node->rubric, $this->node->format, FALSE);
      $content['answers'] = array(
        '#type' => 'item',
        '#title' => t('Rubric'),
        '#value' => '<div class="quiz-solution">' . $rubric_html .'</div>',
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
        '#weight' => 1,
      );
    }
    else {
      $content['answers'] = array(
<<<<<<< HEAD
        '#markup' => '<div class="quiz-answer-hidden">Answer hidden</div>',
        '#weight' => 1,
      );
    }
=======
        '#type' => 'markup',
        '#value' => '<div class="quiz-answer-hidden">Answer hidden</div>',
        '#weight' => 1,
      );
    }

>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
    return $content;
  }

  /**
   * Implementation of getAnweringForm
   *
   * @see QuizQuestion#getAnsweringForm($form_state, $rid)
   */
  public function getAnsweringForm(array $form_state = NULL, $rid) {
    $form = parent::getAnsweringForm($form_state, $rid);
    $form['#theme'] = 'long_answer_answering_form';

    $form['tries'] = array(
      '#type' => 'textarea',
      '#title' => t('Answer'),
      '#description' => t('Enter your answer here. If you need more space, click on the grey bar at the bottom of this area and drag it down.'),
      '#rows' => 15,
      '#cols' => 60,
      '#required' => FALSE, // See isValid() in LongAnswerResponse
    );
    if (isset($rid)) {
      $response = new LongAnswerResponse($rid, $this->node);
      $form['tries']['#default_value'] = $response->getResponse();
    }
    return $form;
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
    $form['rubric'] = array(
       '#type' => 'textarea',
       '#title' => t('Rubric'),
       '#description' => t('Specify the criteria for grading the response.'),
       '#default_value' => isset($this->node->rubric) ? $this->node->rubric : '',
       '#size' => 60,
       '#maxlength' => 512,
       '#required' => FALSE,
    );
    return $form;
  }

  /**
   * Implementation of getMaximumScore
   *
   * @see QuizQuestion#getMaximumScore()
   */
  public function getMaximumScore() {
<<<<<<< HEAD
    return variable_get('long_answer_default_max_score', 10);
=======
    return 10;
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  }

}

/**
 * Extension of QuizQuestionResponse
 */
class LongAnswerResponse extends QuizQuestionResponse {

  /**
   * Get all scores that have not yet been evaluated.
   *
   * @param $count
   *  Number of items to return (default: 50).
   * @param $offset
   *  Where in the results we should start (default: 0).
   *
   * @return
   *  Array of objects describing unanswered questions. Each object will have result_id, question_nid, and question_vid.
   */
  public static function fetchAllUnscoredAnswers($count = 50, $offset = 0) {
<<<<<<< HEAD
    global $user;
    $query = db_select('quiz_long_answer_user_answers', 'a');
    $query->fields('a', array('result_id', 'question_nid', 'question_vid', 'answer_feedback'));
    $query->fields('r', array('title'));
    $query->fields('qnr', array('time_end', 'time_start', 'uid'));
    $query->join('node_revision', 'r', 'a.question_vid = r.vid');
    $query->join('quiz_node_results', 'qnr', 'a.result_id = qnr.result_id');
    $query->join('node', 'n', 'qnr.nid = n.nid');
    $query->condition('a.is_evaluated', 0);
    if (user_access('score own quiz') && user_access('score taken quiz answer')) {
      $query->condition(db_or()->condition('n.uid', $user->uid)->condition('qnr.uid', $user->uid));
    }
    else if (user_access('score own quiz')) {
      $query->condition('n.uid', $user->uid);
    }
    else if (user_access('score taken quiz answer')) {
      $query->condition('qnr.uid', $user->uid);
    }
    $results = $query->execute();
    $unscored = array();
    foreach ($results as $row) {
      $unscored[] = $row;
    }
=======
    $sql = 'SELECT a.result_id, a.question_nid, a.question_vid, r.title, n.time_end, n.time_start, n.uid
      FROM {quiz_long_answer_user_answers} AS a
      INNER JOIN {node_revisions} AS r ON a.question_vid = r.vid
      INNER JOIN {quiz_node_results} AS n ON a.result_id = n.result_id
      WHERE a.is_evaluated = 0';
    $results = db_query_range($sql, $offset, $count);
    $unscored = array();

    if ($results) {
      while ($row = db_fetch_object($results)) {
        $unscored[] = $row;
      }
    }

>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
    return $unscored;
  }

  /**
   * Given a quiz, return a list of all of the unscored answers.
   *
   * @param $nid
   *  Node ID for the quiz to check.
   * @param $vid
   *  Version ID for the quiz to check.
   * @param $count
   *  Number of items to return (default: 50).
   * @param $offset
   *  Where in the results we should start (default: 0).
   *
   * @return
   *  Indexed array of result IDs that need to be scored.
   */
  public static function fetchUnscoredAnswersByQuestion($nid, $vid, $count = 50, $offset = 0) {
<<<<<<< HEAD
    $results = db_query('SELECT result_id FROM {quiz_long_answer_user_answers}
      WHERE is_evaluated = :is_evaluated
      AND question_nid = :question_nid
      AND question_vid = :question_vid', array(':is_evaluated' => 0, ':question_nid' => $nid, ':question_vid' => $vid));
    $unscored = array();
    foreach ($results as $row) {
=======
    $results = db_query_range('SELECT result_id
      FROM {quiz_long_answer_user_answers}
      WHERE is_evaluated = 0
      AND question_nid = %d
      AND question_vid = %d',
      $nid, $vid, $offset, $count);
    $unscored = array();
    foreach (db_fetch_object($results) as $row) {
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
      $unscored[] = $row->result_id;
    }
    return $unscored;
  }

  /**
   * ID of the answer.
   */
  protected $answer_id = 0;

  /**
   * Constructor
   */
  public function __construct($result_id, stdClass $question_node, $answer = NULL) {
    parent::__construct($result_id, $question_node, $answer);

    if (!isset($answer)) {
      // Question has been answered allready. We fetch the answer data from the database.
<<<<<<< HEAD
      $r = db_query('SELECT answer_id, answer, is_evaluated, score, question_vid, question_nid, result_id, answer_feedback
        FROM {quiz_long_answer_user_answers}
        WHERE question_nid = :qnid AND question_vid = :qvid AND result_id = :rid', array(':qnid' => $question_node->nid, ':qvid' => $question_node->vid, ':rid' => $result_id))->fetch();
=======
      $sql = "SELECT answer_id, answer, is_evaluated, score, question_vid, question_nid, result_id
        FROM {quiz_long_answer_user_answers}
        WHERE question_nid = %d AND question_vid = %d AND result_id = %d";
      $r = db_fetch_object(db_query($sql, $question_node->nid, $question_node->vid, $result_id));
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2

      if (!empty($r)) {
        $this->answer = $r->answer;
        $this->score = $r->score;
        $this->evaluated = $r->is_evaluated;
        $this->answer_id = $r->answer_id;
<<<<<<< HEAD
        $this->answer_feedback = $r->answer_feedback;
=======
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
      }
    }
    else {
      $this->answer = $answer;
      $this->evaluated = FALSE;
    }
  }

  /**
   * Implementation of isValid
   *
   * @see QuizQuestionResponse#isValid()
   */
  public function isValid() {
<<<<<<< HEAD
    if (trim($this->answer) == '') {
      return t('You must provide an answer');
    }
=======
    if (trim($this->answer) == '') return t('You must provide an answer');
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
    return TRUE;
  }

  /**
   * Implementation of save
   *
   * @see QuizQuestionResponse#save()
   */
  public function save() {
<<<<<<< HEAD
    $this->answer_id = db_insert('quiz_long_answer_user_answers')
      ->fields(array(
        'answer' => $this->answer,
        'question_nid' => $this->question->nid,
        'question_vid' => $this->question->vid,
        'result_id' => $this->rid,
      ))
      ->execute();
  }

  /**
   * Implementation of delete
=======
    $sql = "INSERT INTO {quiz_long_answer_user_answers}
            (answer, question_nid, question_vid, result_id)
            VALUES ('%s', %d, %d, %d)";
    db_query($sql, $this->answer, $this->question->nid, $this->question->vid, $this->rid);
    $this->answer_id = db_last_insert_id('quiz_long_answer_user_answers', 'answer_id');
  }

  /**
   * Implementation of save
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
   *
   * @see QuizQuestionResponse#delete()
   */
  public function delete() {
<<<<<<< HEAD
    db_delete('quiz_long_answer_user_answers')
      ->condition('question_nid', $this->question->nid)
      ->condition('question_vid', $this->question->vid)
      ->condition('result_id', $this->rid)
      ->execute();
=======
    $sql = 'DELETE FROM {quiz_long_answer_user_answers}
            WHERE question_nid = %d AND question_vid = %d AND result_id = %d';
    db_query($sql, $this->question->nid, $this->question->vid, $this->rid);
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  }

  /**
   * Implementation of score
   *
   * @see QuizQuestionResponse#score()
   */
  public function score() {
<<<<<<< HEAD
    return (int) db_query('SELECT score FROM {quiz_long_answer_user_answers}
      WHERE result_id = :result_id AND question_vid = :question_vid', array(':result_id' => $this->rid, ':question_vid' => $this->question->vid))->fetchField();
=======
    $sql = "SELECT score
            FROM {quiz_long_answer_user_answers}
            WHERE result_id = %d AND question_vid = %d";
    $score = (int)db_result(db_query($sql, $this->rid, $this->question->vid));
    return $score;
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  }

  /**
   * Implementation of getResponse
   *
   * @see QuizQuestionResponse#getResponse()
   */
  public function getResponse() {
    return $this->answer;
  }

  /**
   * Implementation of getReportFormResponse
   *
   * @see QuizQuestionResponse#getReportFormResponse($showpoints, $showfeedback, $allow_scoring)
   */
  public function getReportFormResponse($showpoints = TRUE, $showfeedback = TRUE, $allow_scoring = FALSE) {
    $form = array();
    $form['#theme'] = 'long_answer_response_form';
<<<<<<< HEAD
    if ($this->question && !empty($this->question->answers)) {
      $answer = (object) current($this->question->answers);
    }
    else {
      return $form;
    }
    $form['answer'] = array('#markup' => check_markup($answer->answer));
=======
    if ($this->question && !empty($this->question->answers))
      $answer = (object) current($this->question->answers);
    else
      return $form;
    $form['answer'] = array(
      '#type' => 'markup',
      '#value' => check_markup($answer->answer),
    );
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
    if ($answer->is_evaluated == 1) {
      // Show feedback, if any.
      if ($showfeedback && !empty($answer->feedback)) {
        $feedback = check_markup($answer->feedback);
      }
    }
    else {
      $feedback = t('This answer has not yet been scored.') .
        '<br/>' .
        t('Until the answer is scored, the total score will not be correct.');
    }
    if ($allow_scoring) {
      $form['rubric'] = array(
        '#type' => 'item',
        '#title' => t('Rubric'),
<<<<<<< HEAD
        '#markup' => check_markup($this->question->rubric),
      );
    }
    if (!$allow_scoring && !empty($this->answer_feedback)) {
      $form['answer_feedback'] = array(
        '#title' => t('Feedback'),
        '#type' => 'item',
        '#markup' => '<span class="quiz_answer_feedback">' . $this->answer_feedback . '</span>',
      );
    }
    if (!empty($feedback)) {
      $form['feedback'] = array('#markup' => '<span class="quiz_answer_feedback">' . $feedback . '</span>');
=======
        '#value' => check_markup($this->question->rubric, variable_get('long_answer_markup_filter', FILTER_FORMAT_DEFAULT), FALSE),
      );
    }
    if (!empty($feedback)) {
      $form['feedback'] = array(
        '#type' => 'markup',
        '#value' => '<span class="quiz_answer_feedback">' . $feedback . '</span>',
      );
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
    }
    return $form;
  }

  /**
   * Implementation of getReportFormScore
   *
   * @see QuizQuestionResponse#getReportFormScore($showpoints, $showfeedback, $allow_scoring)
   */
  public function getReportFormScore($showfeedback = TRUE, $showpoints = TRUE, $allow_scoring = FALSE) {
    // The score will be shown as a questionmark if the question haven't been scored already
    $score = ($this->isEvaluated()) ? $this->getScore() : '?';
    // We show a textfield if the quiz shall be scored. Markup otherwise
    if (quiz_access_to_score() && $allow_scoring) {
      return array(
        '#type' => 'textfield',
        '#default_value' => $score,
        '#size' => 3,
        '#maxlength' => 3,
<<<<<<< HEAD
        '#attributes' => array('class' => array('quiz-report-score')),
      );
    }
    else {
      return array('#markup' => $score);
    }
  }

  public function getReportFormAnswerFeedback($showpoints = TRUE, $showfeedback = TRUE, $allow_scoring = FALSE) {
    if (quiz_access_to_score() && $allow_scoring) {
      return array(
        '#title' => t('Enter feedback'),
        '#type' => 'textarea',
        '#default_value' => $this->answer_feedback,
        '#attributes' => array('class' => array('quiz-report-score')),
      );
    }
    return FALSE;
  }


=======
        '#attributes' => array('class' => 'quiz-report-score'),
      );
    }
    else {
      return array(
        '#type' => 'markup',
        '#value' => $score,
      );
    }
  }

>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  /**
   * Implementation of getReportFormSubmit
   *
   * @see QuizQuestionResponse#getReportFormSubmit($showfeedback, $showpoints, $allow_scoring)
   */
  public function getReportFormSubmit($showfeedback = TRUE, $showpoints = TRUE, $allow_scoring = FALSE) {
    return $allow_scoring ? 'long_answer_report_submit' : FALSE;
  }

  /**
   * Implementation of getReportFormValidate
   *
   * @see QuizQuestionResponse#getReportFormValidate($showfeedback, $showpoints, $allow_scoring)
   */
  public function getReportFormValidate($showfeedback = TRUE, $showpoints = TRUE, $allow_scoring = FALSE) {
    return $allow_scoring ? 'long_answer_report_validate' : FALSE;
  }
}

