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
class LongAnswerQuestion extends QuizQuestion{
  /**
   * Implementation of saveNodeProperties
   *
   * @see QuizQuestion#saveNodeProperties($is_new)
   */
  public function saveNodeProperties($is_new = FALSE) {
    if (!isset($this->node->feedback)) {
      $this->node->feedback = '';
    }
    if ($is_new || $this->node->revision == 1) {
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
    }
  }

  /**
   * Implementation of validateNode
   *
   * @see QuizQuestion#validateNode($form)
   */
  public function validateNode(array &$form) {
  }

  /**
   * Implementation of delete
   *
   * @see QuizQuestion#delete($only_this_version)
   */
  public function delete($only_this_version = FALSE) {
    if ($only_this_version) {
      db_query('DELETE FROM {quiz_long_answer_user_answers} WHERE question_nid = %d AND question_vid = %d', $this->node->nid, $this->node->vid);
      db_query('DELETE FROM {quiz_long_answer_node_properties} WHERE nid = %d AND vid = %d', $this->node->nid, $this->node->vid);
    }
    else {
      db_query('DELETE FROM {quiz_long_answer_node_properties} WHERE nid = %d', $this->node->nid);
      db_query('DELETE FROM {quiz_long_answer_user_answers} WHERE question_nid = %d', $this->node->nid);
    }
    parent::delete($only_this_version);
  }

  /**
   * Implementation of getNodeProperties
   *
   * @see QuizQuestion#getNodeProperties()
   */
  public function getNodeProperties() {
    if (isset($this->nodeProperties)) return $this->nodeProperties;
    $props = parent::getNodeProperties();

    $sql = 'SELECT rubric
      FROM {quiz_long_answer_node_properties}
      WHERE nid = %d AND vid = %d';
    $res = db_query($sql, $this->node->nid, $this->node->vid);
    $res_a = db_fetch_array($res);
    if (is_array($res_a))
    $props = array_merge($props, $res_a);
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

    if ($this->viewCanRevealCorrect()) {
      $rubric_html = check_markup($this->node->rubric, $this->node->format, FALSE);
      $content['answers'] = array(
        '#type' => 'item',
        '#title' => t('Rubric'),
        '#value' => '<div class="quiz-solution">' . $rubric_html .'</div>',
        '#weight' => 1,
      );
    }
    else {
      $content['answers'] = array(
        '#type' => 'markup',
        '#value' => '<div class="quiz-answer-hidden">Answer hidden</div>',
        '#weight' => 1,
      );
    }

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
  public function getCreationForm(array $form_state = NULL) {
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
    return 10;
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
    $results = db_query_range('SELECT result_id
      FROM {quiz_long_answer_user_answers}
      WHERE is_evaluated = 0
      AND question_nid = %d
      AND question_vid = %d',
      $nid, $vid, $offset, $count);
    $unscored = array();
    foreach (db_fetch_object($results) as $row) {
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
      $sql = "SELECT answer_id, answer, is_evaluated, score, question_vid, question_nid, result_id
        FROM {quiz_long_answer_user_answers}
        WHERE question_nid = %d AND question_vid = %d AND result_id = %d";
      $r = db_fetch_object(db_query($sql, $question_node->nid, $question_node->vid, $result_id));

      if (!empty($r)) {
        $this->answer = $r->answer;
        $this->score = $r->score;
        $this->evaluated = $r->is_evaluated;
        $this->answer_id = $r->answer_id;
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
    if (trim($this->answer) == '') return t('You must provide an answer');
    return TRUE;
  }

  /**
   * Implementation of save
   *
   * @see QuizQuestionResponse#save()
   */
  public function save() {
    $sql = "INSERT INTO {quiz_long_answer_user_answers}
            (answer, question_nid, question_vid, result_id)
            VALUES ('%s', %d, %d, %d)";
    db_query($sql, $this->answer, $this->question->nid, $this->question->vid, $this->rid);
    $this->answer_id = db_last_insert_id('quiz_long_answer_user_answers', 'answer_id');
  }

  /**
   * Implementation of save
   *
   * @see QuizQuestionResponse#delete()
   */
  public function delete() {
    $sql = 'DELETE FROM {quiz_long_answer_user_answers}
            WHERE question_nid = %d AND question_vid = %d AND result_id = %d';
    db_query($sql, $this->question->nid, $this->question->vid, $this->rid);
  }

  /**
   * Implementation of score
   *
   * @see QuizQuestionResponse#score()
   */
  public function score() {
    $sql = "SELECT score
            FROM {quiz_long_answer_user_answers}
            WHERE result_id = %d AND question_vid = %d";
    $score = (int)db_result(db_query($sql, $this->rid, $this->question->vid));
    return $score;
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
    if ($this->question && !empty($this->question->answers))
      $answer = (object) current($this->question->answers);
    else
      return $form;
    $form['answer'] = array(
      '#type' => 'markup',
      '#value' => check_markup($answer->answer),
    );
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
        '#value' => check_markup($this->question->rubric, variable_get('long_answer_markup_filter', FILTER_FORMAT_DEFAULT), FALSE),
      );
    }
    if (!empty($feedback)) {
      $form['feedback'] = array(
        '#type' => 'markup',
        '#value' => '<span class="quiz_answer_feedback">' . $feedback . '</span>',
      );
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

