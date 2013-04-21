<?php

/**
 * @file
 * Top-level mapping class.
 *
 * A mapping between an answer and feedback starts with this class. This class
 * then contains other operators that do the actual checks.
 */
class CqMapping extends CqAbstractMapping {

  /**
   * Is the mapping fully initialised?
   *
   * @var Boolean
   */
  private $inited = FALSE;
  /**
   * If this mapping matches, does that mean the answer is correct?
   *
   * @var Boolean
   */
  private $isCorrect;
  /**
   * If this mapping matches, should processing stop, or continue with following
   * mappings?
   *
   * @var boolean
   */
  private $stop = FALSE;
  /**
   * The id of the last match done.
   * Since most match-checks are fail-fast, this id is probably the one that
   * caused the mis-match.
   *
   * @var string
   *   The id of the item that was subject to the last match-check.
   */
  public $lastMatchedId = '';

  /**
   * Initialise this mapping.
   */
  private function init() {
    if (!$this->inited) {
      $this->inited = TRUE;
      $this->isCorrect = FALSE;
      if (isset($this->params['correct'])) {
        $this->isCorrect = $this->params['correct'];
      }
      if (isset($this->params['stop']) && $this->params['stop']) {
        $this->stop = TRUE;
      }
    }
  }

  /**
   * If this mapping matches, does that mean the answer is correct?
   *
   * @return boolean
   */
  public function getCorrect() {
    return $this->isCorrect;
  }

  /**
   * Set the stop parameter of this matching.
   * If this mapping matches, should processing stop, or continue with following
   * mappings?
   *
   * @param boolean $stop
   */
  public function setStopIfMatch($stop) {
    $this->stop = $stop;
  }

  /**
   * Get the stop parameter of this matching.
   * If this mapping matches, should processing stop, or continue with following
   * mappings?
   *
   * @return boolean
   */
  public function stopIfMatch() {
    return $this->stop;
  }

  /**
   * Implements CqAbstractMapping::evaluate()
   */
  public function evaluate() {
    $this->init();
    foreach ($this->children AS $id => $tempExpression) {
      if (!$tempExpression->evaluate()) {
        return FALSE;
      }
    }
    return TRUE;
  }

  /**
   * Get the feedback items of this mapping, for the given number of tries.
   *
   * @param int $tries
   *  The number of incorrect tries the student did.
   *
   * @return array of CqFeedback
   */
  public function getFeedbackItems($tries) {
    $retval = array();
    foreach ($this->feedback AS $feedbackItem) {
      if ($feedbackItem->inRange($tries)) {
        $retval[] = $feedbackItem;
      }
    }
    return $retval;
  }

  /**
   * Overrides CqAbstractMapping::getAllText()
   */
  public function getAllText() {
    $this->init();
    $retval = array();
    $retval['correct'] = $this->isCorrect;

    $retval['children'] = array(
      '#theme' => 'closedquestion_mapping_list',
      'items' => array(),
      'extended' => TRUE,
    );
    foreach ($this->children AS $child) {
      $retval['children']['items'][] = $child->getAllText();
    }

    if (count($this->feedback) > 0) {
      $retval['feedback'] = array(
        '#theme' => 'closedquestion_feedback_list',
        'extended' => TRUE,
      );
      foreach ($this->feedback AS $fbitem) {
        $retval['feedback']['items'][] = $fbitem->getAllText();
      }
    }

    $retval['#theme'] = 'closedquestion_mapping';
    return $retval;
  }

}
