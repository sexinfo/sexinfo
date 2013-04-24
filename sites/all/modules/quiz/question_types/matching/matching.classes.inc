<?php
/**
 * matching.classes
 *
 * This module uses the question interface to define the Matching question type.
 *
 * A Matching node defines a series of questions and answers and requires the user
 * to associate each answer with a question.
 *
 * @file
 */

/**
 * Extension of QuizQuestion.
 */
class MatchingQuestion extends QuizQuestion {

  /**
   * Constructor
   *
   * @param $node
   *  matching node
   */
  public function __construct(stdClass $node) {
    parent::__construct($node);
    if (empty($this->node->match)) $this->node->match = array();
  }

  /**
   * Implementation of saveNodeProperties
   *
   * @see QuizQuestion#saveNodeProperties($is_new)
   */
  public function saveNodeProperties($is_new = FALSE) {
    // Loop through each question-answer combination
    foreach ($this->node->match as $match) {
      $match['feedback'] = (isset($match['feedback'])) ? $match['feedback'] : '';
      // match_id is not so it is a new question.
      if (empty($match['match_id']) || $this->node->revision) {
        if (!empty($match['question']) && !empty($match['answer'])) {
          $sql = "INSERT INTO {quiz_matching_node} (nid, vid, question, answer, feedback) VALUES (%d, %d, '%s', '%s', '%s')";
          db_query($sql, $this->node->nid, $this->node->vid, $match['question'], $match['answer'], $match['feedback']);
        }
      }
      // match_id is set, user may remove or update existing question.
      else {
        if (empty($match['question']) && empty($match['answer'])) {
          // remove sub question.
          db_query("DELETE FROM {quiz_matching_node} WHERE match_id = %d", $match['match_id']);
        }
        else {
          // update sub question.
          $sql = "UPDATE {quiz_matching_node} SET question = '%s', answer = '%s', feedback = '%s' WHERE match_id = %d";
          db_query($sql, $match['question'], $match['answer'], $match['feedback'], $match['match_id']);
        }
      }
    }
  }

  /**
   * Implementation of validateNode
   *
   * @see QuizQuestion#validateNode($form)
   */
  public function validateNode(array &$form) {
    // No validation is required
    // The first two pairs are required in the form, if there are other errors we forgive them
  }

  /**
   * Implementation of delete
   *
   * @see QuizQuestion#delete($only_this_version)
   */
  public function delete($only_this_version = FALSE) {
    parent::delete($only_this_version);

    if ($only_this_version) {
      $sql = 'DELETE FROM {quiz_matching_user_answers}
              WHERE match_id IN(
                SELECT match_id
                FROM {quiz_matching_node}
                WHERE nid = %d AND vid = %d
              )';
      db_query($sql, $this->node->nid, $this->node->vid);
      $sql = 'DELETE FROM {quiz_matching_node} WHERE nid = %d AND vid = %d';
      db_query($sql, $this->node->nid, $this->node->vid);
    }
    // Delete all versions of this question.
    else {
      $sql = 'DELETE FROM {quiz_matching_user_answers}
              WHERE match_id IN(
                SELECT match_id
                FROM {quiz_matching_node}
                WHERE nid = %d
              )';
      db_query($sql, $this->node->nid);
      $sql = 'DELETE FROM {quiz_matching_node} WHERE nid = %d';
      db_query($sql, $this->node->nid, $this->node->vid);
    }
  }

  /**
   * Implementation of getNodeProperties
   *
   * @see QuizQuestion#getNodeProperties()
   */
  public function getNodeProperties() {
    if (isset($this->nodeProperties)) return $this->nodeProperties;
    $props = parent::getNodeProperties();

    $sql = "SELECT match_id, question, answer, feedback FROM {quiz_matching_node} WHERE nid = %d AND vid = %d";
    $results = db_query($sql, $this->node->nid, $this->node->vid);
    while ($result = db_fetch_object($results)) {
      $props['match'][] = array(
        'match_id' => $result->match_id,
        'question' => $result->question,
        'answer' => $result->answer,
        'feedback' => $result->feedback,
      );
    }
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

    list($matches, $select_option) = $this->getSubquestions();
    $subquestions = array();
    if ($this->viewCanRevealCorrect()) {
      foreach ($matches as $match) {
        $subquestions[] = array(
          'question' =>$match['question'],
          'correct' => $match['answer'],
          'feedback' => $match['feedback']
        );
      }
    }
    else {
      // shuffle the answer column
      foreach ($matches as $match) {
        $sub_qs[] = $match['question'];
        $sub_as[] = $match['answer'];
      }
      shuffle($sub_as);
      foreach ($sub_qs as $i => $sub_q) {
        $subquestions[] = array(
          'question' => $sub_q,
          'random' => $sub_as[$i],
        );
      }
    }
    $content['answers'] = array(
       '#value' => theme('matching_match_node_view', $subquestions),
       '#weight' => 2,
    );
    return $content;
  }

  /**
   * Implementation of getAnsweringForm
   *
   * @see QuizQuestion#getAnsweringForm($form_state, $rid)
   */
  public function getAnsweringForm(array $form_state = NULL, $rid) {
    $form = parent::getAnsweringForm($form_state, $rid);
    $form['#theme'] = 'matching_answering_form';

    if (isset($rid)) {
      // The question has already been answered. We load the answers
      $response = new MatchingResponse($rid, $this->node);
      $responses = $response->getResponse();
    }
    list($matches, $select_option) = $this->getSubquestions();
    $form['subquestions']['#theme'] = 'matching_subquestion_form';
    foreach ($matches as $match) {
      $form['subquestions'][$match['match_id']]['#question'] = check_markup($match['question'], FILTER_FORMAT_DEFAULT, FALSE);

      $form['subquestions'][$match['match_id']]['tries[' . $match['match_id'] . ']'] = array(
        '#type' => 'select',
        '#options' => $this->customShuffle($select_option),
      );
      if (isset($rid)) {
        // If this question already has been answered
        $form['subquestions'][$match['match_id']]['tries[' . $match['match_id'] . ']']['#default_value'] = $responses[$match['match_id']];
      }
    }
    $form['scoring_info'] = array(
      '#type' => 'markup',
      '#value' => '<p><em>'. t('You lose points by selecting incorrect options. You may leave an option blank to avoid losing points.') .'</em></p>',
    );
    if (variable_get('quiz_matching_shuffle_options', TRUE)) {
      $form['subquestions'] = $this->customShuffle($form['subquestions']);
    }
    return $form;
  }

  /**
   * Shuffles an array, but keeps the keys, and makes sure the first element is the default element
   *
   * @param $array
   *  Array to be shuffled
   * @return
   *  A shuffled version of the array with $array['def'] = '' as the first element
   */
  private function customShuffle(array $array = array()) {
    $new_array = array();
    $new_array['def'] = '';
    while (count($array)) {
      $element = array_rand($array);
      $new_array[$element] = $array[$element];
      unset($array[$element]);
    }
    return $new_array;
  }

  /**
   * Helper function to fetch subquestions
   *
   * @return
   *  Array with two arrays, matches and selected options
   */
  private function getSubquestions() {
    $matches = $select_option = array();
    $sql = "SELECT match_id, question, answer, feedback FROM {quiz_matching_node} WHERE nid = %d AND vid = %d";
    $results = db_query($sql, $this->node->nid, $this->node->vid);
    while ($result = db_fetch_object($results)) {
      $matches[] = array(
        'match_id' => $result->match_id,
        'question' => $result->question,
        'answer' => $result->answer,
        'feedback' => $result->feedback,
      );
      $select_option[$result->match_id] = $result->answer;
    }
    return array($matches, $select_option);
  }

  /**
   * Implementation of getBodyFieldTitle
   *
   * @see QuizQuestion#getBodyFieldTitle()
   */
  public function getBodyFieldTitle() {
    return t('Instruction');
  }

  /**
   * Implementation of getCreationForm
   *
   * @see QuizQuestion#getCreationForm($form_state)
   */
  public function getCreationForm(array $form_state = NULL) {
    $form['match'] = array(
      '#type' => 'fieldset',
      '#title' => t('Answer'),
      '#weight' => -4,
      '#tree' => TRUE,
      '#theme' => 'matching_question_form',
      '#description' => t('Write your pairs in the question and answer columns. For the user the question will be fixed and the answers will be shown as alternatives in a dropdown box.'),
    );
    for ($i = 1; $i <= variable_get('quiz_matching_form_size', 5); ++$i) {
      $form['match'][$i] = array(
        '#type' => 'fieldset',
        '#title' => t('Question '. $i),
      );
      $form['match'][$i]['match_id'] = array(
        '#type' => 'hidden',
        '#default_value' => isset($this->node->match[$i-1]['match_id']) ? $this->node->match[$i-1]['match_id'] : ''
      );
      $form['match'][$i]['question'] = array(
        '#type' => 'textarea',
        '#rows' => 2,
        '#default_value' => isset($this->node->match[$i-1]['question']) ? $this->node->match[$i-1]['question'] : '',
        '#required' => $i < 3,
      );
      $form['match'][$i]['answer'] = array(
        '#type' => 'textarea',
        '#rows' => 2,
        '#default_value' => isset($this->node->match[$i-1]['answer']) ? $this->node->match[$i-1]['answer'] : '',
        '#required' => $i < 3,
      );

      $form['match'][$i]['feedback'] = array(
        '#type' => 'textarea',
        '#rows' => 2,
        '#default_value' => isset($this->node->match[$i-1]['feedback']) ? $this->node->match[$i-1]['feedback'] : ''
      );
    }
    return $form;
  }

  /**
   * Implementation of getMaximumScore
   *
   * @see QuizQuestion#getMaximumScore()
   */
  public function getMaximumScore() {
    $to_return = 0;
    foreach ($this->node->match as $match) {
      if (empty($match['question']) || empty($match['answer'])) {
        continue;
      }
      $to_return++;
    }
    // The maximum score = the number of sub-questions
    return $to_return;
  }

  /**
   * Get the correct answers for this question
   *
   * @return
   *  Array of correct answers
   */
  public function getCorrectAnswer() {
    $correct_answers = array();

    $results = db_query('SELECT match_id, question, answer, feedback
      FROM {quiz_matching_node}
      WHERE nid = %d AND vid = %d',
      $this->node->nid, $this->node->vid);

    while ($result = db_fetch_object($results)) {
      $correct_answers[$result->match_id] = array(
        'match_id'  => $result->match_id,
        'question'  => $result->question,
        'answer'    => $result->answer,
        'feedback'  => $result->feedback,
      );
    }
    return $correct_answers;
  }
}

/**
 * Extension of QuizQuestionResponse
 */
class MatchingResponse extends QuizQuestionResponse {

  /**
   * Constructor
   */
  public function __construct($result_id, stdClass $question_node, $answer = NULL) {
    parent::__construct($result_id, $question_node, $answer);
    if (!isset($answer)) {
      $sql = 'SELECT ua.answer, score, ua.match_id
              FROM {quiz_matching_user_answers} ua
              JOIN {quiz_matching_node} n
              ON n.match_id = ua.match_id
              WHERE n.nid = %d AND n.vid = %d AND ua.result_id = %d';
      $res = db_query($sql, $question_node->nid, $question_node->vid, $result_id);
      $this->answer = array();
      while ($obj = db_fetch_object($res)) {
        $this->answer[$obj->match_id] = $obj->answer;
      }
    }
    $this->is_correct = $this->isCorrect();
  }

  /**
   * Implementation of isValid
   *
   * @see QuizQuestionResponse#isValid()
   */
  public function isValid() {
    foreach ($this->answer as $value)
      if ($value != 'def') return TRUE;
    return t('You need to match at least one of the items.');
  }

  /**
   * Implementation of save
   *
   * @see QuizQuestionResponse#save()
   */
  public function save() {
    $user_answers = isset($this->answer) ? $this->answer : array();
    // to prevent warning : Invalid argument supplied for foreach()
    foreach ((array)$user_answers as $key => $value) {
      $score = ($key == $value)? 1 : 0;
      $sql = 'INSERT INTO {quiz_matching_user_answers} (match_id, result_id, answer, score) VALUES (%d, %d, %d, %d)';
      db_query($sql, $key, $this->rid, (int)$value, $score);
    }
  }

  /**
   * Implementation of delete
   *
   * @see QuizQuestionResponse#delete()
   */
  public function delete() {
    $sql = 'DELETE FROM {quiz_matching_user_answers}
            WHERE match_id IN (
              SELECT match_id
              FROM {quiz_matching_node}
              WHERE nid = %d AND vid = %d
            )
            AND result_id = %d';
    db_query($sql, $this->question->nid, $this->question->vid, $this->rid);
  }

  /**
   * Implementation of score
   *
   * @see QuizQuestionResponse#score()
   */
  public function score() {
    $wrong_answer = 0;
    $correct_answer = 0;
    $user_answers = isset($this->answer['answer']) ? $this->answer['answer'] : $this->answer;
    foreach ((array)$user_answers as $key => $value) {
      if ($key == $value) {
        $correct_answer++;
      }
      elseif ($value == 0 || $value == 'def') {
      }
      else {
        $wrong_answer++;
      }
    }
    $score = $correct_answer - $wrong_answer;
    return $score < 0 ? 0 : $score;
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
    $data = $metadata = array();
    // Build the question answers header (add blank space for IE).
    $metadata[] = t('Match');
    if ($showpoints) {
      $metadata[] = t('Correct Answer');
    }
    $metadata[] = t('User answer');
    if ($showfeedback) {
      $metadata[] = t('Feedback');
    }

    $MatchingQuestion = new MatchingQuestion($this->question);
    $correct_answers = $MatchingQuestion->getCorrectAnswer();
    $user_answers = isset($this->answer['answer']) ? $this->answer['answer'] : $this->answer;

    foreach ($correct_answers as $correct_answer) {
      $answer_data = array();
      $id = $user_answers[$correct_answer['match_id']];
      $correct = isset($correct_answers[$id]) && $correct_answer['answer'] == $correct_answers[$id]['answer'];
      $answer_data['question'] = check_markup($correct_answer['question'], $this->question->format, FALSE);
      if ($showpoints)
        $answer_data['correct_answer'] = check_markup($correct_answer['answer'], $this->question->format, FALSE);
      $answer_data['user_answer'] = isset($correct_answers[$id]) ? check_markup($correct_answers[$id]['answer'], $this->question->format, FALSE) : NULL;
      if ($showfeedback) {
        $answer_data['feedback'] = check_markup($correct_answer['feedback'], $this->question->format, FALSE);
        $answer_data['is_correct'] = $correct;
      }
      $data[] = $answer_data;
    }

    return array(
      '#type' => 'markup',
      '#value' => theme('matching_response', $metadata, $data),
    );
  }
}
