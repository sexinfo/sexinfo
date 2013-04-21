<?php

/**
 * @file
 * CqRange checks if the answer of a Value question is in a certain range.
 * Used by CqQuestionValue.
 */
class CqRange {

  /**
   * The minimum value for this range to match, inclusive.
   *
   * @var number
   */
  private $minValue;
  /**
   * The maximum value for this range to match, inclusive.
   *
   * @var number
   */
  private $maxValue;
  /**
   * If this range matches, does that mean the answer is correct?
   */
  private $correct = 0;
  /**
   * Feedback items contained in this mapping.
   *
   * @var Array of CqFeedback
   */
  private $feedback = array();

  /**
   * Constructor for the Range object.
   *
   * @param DOMElement $node
   *   The XML node to use for initalisation
   * @param <type> $context
   *   CqQuestionInterface The question or other object that the mapping can query for
   *   things like the current answer, draggables, hotspots and the parsing of
   *   html.
   */
  public function __construct(DOMElement $node, $context) {
    module_load_include('inc.php', 'closedquestion', 'lib/XmlLib');

    $nodeList = $node->getElementsByTagName('minval');
    $item = $nodeList->item(0);
    if ($item != NULL) {
      $this->minValue = trim(cq_get_text_content($item, $context));
    }

    $nodeList = $node->getElementsByTagName('maxval');
    $item = $nodeList->item(0);
    if ($item != NULL) {
      $this->maxValue = trim(cq_get_text_content($item, $context));
    }

    foreach ($node->getElementsByTagName('feedback') as $fb) {
      $this->feedback[] = CqFeedback::newCqFeedback($fb, $context);
    }

    $attribs = $node->attributes;
    $item = $attribs->getNamedItem('correct');
    if ($item !== NULL) {
      $this->correct = (int) $item->value;
    }
  }

  /**
   * Getter for the correct property.
   *
   * @return int
   */
  public function getCorrect() {
    return $this->correct;
  }

  /**
   * Is the given answer in range?
   *
   * @param number $answer
   *   The answer to check.
   *
   * @return boolean
   */
  public function inRange($answer) {
    if ($answer !== NULL) {
      $answer = closedquestion_fix_number($answer);
      if ($this->minValue !== NULL && $answer < $this->minValue) {
        return FALSE;
      }
      if ($this->maxValue !== NULL && $answer > $this->maxValue) {
        return FALSE;
      }
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  /**
   * Is the given answer correct? That is: in range when correct is true, and
   * not in range when correct is not true.
   *
   * @param number $answer
   *   The answer to check.
   *
   * @return boolean
   */
  public function correctlyAnswered($answer) {
    return (!($this->inRange($answer) xor $this->correct));
  }

  /**
   * Get the feedback items associated with this range, for $tries tries.
   *
   * @param int $tries
   *   The number of incorrect attempts the student made for this question.
   *
   * @return array of CqFeedback
   */
  public function getFeedback($tries) {
    $retVal = array();
    foreach ($this->feedback as $fb) {
      if ($fb->inRange($tries)) {
        $retVal[] = $fb;
      }
    }
    return $retVal;
  }

  /**
   * Returns a html representation of this mapping, and it's children, for text
   * review.
   *
   * @return
   *   String the html representation.
   */
  public function getAllText() {
    $retval = array(
      'correct' => $this->correct,
      'minval' => $this->minValue,
      'maxval' => $this->maxValue,
    );

    if (count($this->feedback) > 0) {
      $retval['feedback'] = array(
        '#theme' => 'closedquestion_feedback_list',
        'extended' => TRUE,
      );
      foreach ($this->feedback AS $fbitem) {
        $retval['feedback']['items'][] = $fbitem->getAllText();
      }
    }

    $retval['#theme'] = 'closedquestion_range';
    return $retval;
  }

}
