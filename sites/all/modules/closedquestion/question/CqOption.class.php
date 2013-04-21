<?php

/**
 * @file
 * An option in a multiple choice question.
 */
class CqOption {

  /**
   * The identifier of this option.
   *
   * @var String
   */
  private $identifier;
  /**
   * The html to show to the user.
   *
   * @var String
   */
  private $text = '';
  /**
   * Is this option (part of) the correct answer?
   * 1 Yes
   * 0 No
   * -1 Selecting this option doesn't matter.
   *
   * @var int
   */
  private $correct = 0;
  /**
   * The list of CqFeedback items to use if this item is selected.
   *
   * @var array of CqFeedback
   */
  private $feedback = array();
  /**
   * The list of CqFeedback items to use if this item is not selected.
   *
   * @var array of CqFeedback
   */
  private $feedbackUnselected = array();
  /**
   * HTML for an extended description to use in for instance a mouse-over.
   *
   * @var String
   */
  private $description = '';

  /**
   * Creates a new CqOption.
   *
   * @param DOMElement $node
   *   containing the option definition
   * @param CqQuestionInterface $context
   *   CqQuestionInterface The question or other object that the mapping can query for
   *   things like the current answer, draggables, hotspots and the parsing of
   *   html.
   */
  public function __construct(DOMElement $node, $context) {

    foreach ($node->getElementsByTagName('choice') as $choice) {
      $this->text .= cq_get_text_content($choice, $context);
    }

    foreach ($node->getElementsByTagName('description') as $description) {
      $this->description .= cq_get_text_content($description, $context);
    }

    foreach ($node->getElementsByTagName('feedback') as $fb) {
      $this->feedback[] = CqFeedback::newCqFeedback($fb, $context);
    }

    foreach ($node->getElementsByTagName('feedbackunselected') as $fb) {
      $this->feedbackUnselected[] = CqFeedback::newCqFeedback($fb, $context);
    }

    $attribs = $node->attributes;
    $item = $attribs->getNamedItem('correct');
    if ($item !== NULL) {
      $this->correct = (int) $item->value;
    }
    $item = $attribs->getNamedItem('identifier');
    if ($item === NULL) {
      $item = $attribs->getNamedItem('id');
    }
    if ($item === NULL) {
      $item = $attribs->getNamedItem('name');
    }
    if ($item !== NULL) {
      $this->identifier = $item->nodeValue;
    }
  }

  /**
   * Getter for the identifier.
   *
   * @return int
   */
  public function getIdentifier() {
    return $this->identifier;
  }

  /**
   * Getter for the text
   *
   * @return string
   */
  public function getText() {
    return $this->text;
  }

  /**
   * Getter for the description
   *
   * @return string
   */
  public function getDescription() {
    return $this->description;
  }

  /**
   * Is this option (part of) the correct answer?
   * 1 Yes
   * 0 No
   * -1 Selecting this option doesn't matter.
   *
   * @return int
   */
  public function getCorrect() {
    return $this->correct;
  }

  /**
   * Check if the user gave the correct answer for this option.
   *
   * @param $answer
   *   A string longer than 1 character if the option is selected, a string of 1
   *   character or shorter of the option is not selected.
   *
   * @return boolean
   */
  public function correctlyAnswered($answer) {
    if (drupal_strlen($answer) <= 1 && $this->correct != 0) {
      return FALSE;
    }
    if (drupal_strlen($answer) > 1 && $this->correct == 0) {
      return FALSE;
    }
    return TRUE;
  }

  /**
   * Return the relevant feedback items for the given number of tries and the
   * given selected status.
   *
   * @param int $tries
   *   The number of times the sudent alreay tried to answer this question.
   * @param boolean $selected
   *  (Optional) Has the student selected this option? Defaults to TRUE.
   *
   * @return array of CqFeedback
   */
  public function getFeedback($tries, $selected = TRUE) {
    $retVal = array();
    if ($selected) {
      foreach ($this->feedback as $fb) {
        if ($fb->inRange($tries)) {
          $retVal[] = $fb;
        }
      }
    }
    else {
      foreach ($this->feedbackUnselected as $fb) {
        if ($fb->inRange($tries)) {
          $retVal[] = $fb;
        }
      }
    }
    return $retVal;
  }

  /**
   * Getter for feedback, the full list of feedback items used when the option
   * is selected.
   *
   * @return array of CqFeedback
   */
  public function getFeedbackItems() {
    return $this->feedback;
  }

  /**
   * Getter for feedbackUnselected, the full list of feedback items used when
   * the option is not selected.
   *
   * @return array of CqFeedback
   */
  public function getFeedbackUnselectedItems() {
    return $this->feedbackUnselected;
  }

  /**
   * Get all the text in the option, for easier reviewing for spelling, etc.
   *
   * @return array
   *   Themable form array.
   */
  public function getAllText() {
    $retval = array();
    $retval['#theme'] = 'closedquestion_option';
    $retval['identifier'] = $this->getIdentifier();
    $retval['correct'] = $this->getCorrect();
    $retval['text'] = $this->getText();
    $description = $this->getDescription();
    if ($description) {
      $retval['description'] = $description;
    }

    $feedback = $this->getFeedbackItems();
    if (count($feedback) > 0) {
      $retval['feedback'] = array(
        '#theme' => 'closedquestion_feedback_list',
        'extended' => TRUE,
      );
      foreach ($feedback AS $fbitem) {
        $retval['feedback']['items'][] = $fbitem->getAllText();
      }
    }

    $feedback = $this->getFeedbackUnselectedItems();
    if (count($feedback) > 0) {
      $retval['feedback_notselected'] = array(
        '#theme' => 'closedquestion_feedback_list',
        'extended' => TRUE,
      );
      foreach ($feedback AS $fbitem) {
        $retval['feedback_notselected']['items'][] = $fbitem->getAllText();
      }
    }
    return $retval;
  }

}
